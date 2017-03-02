<?php
/*
 * This file is part of the PortalPal package.
 *
 * (c) Unit6 <team@unit6websites.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Unit6\PortalPal;

use InvalidArgumentException;
use RuntimeException;
use UnexpectedValueException;

use Unit6\HTTP\Body;
use Unit6\HTTP\Client\Request;
use Unit6\HTTP\Client\Response;
use Unit6\HTTP\Headers;
use Unit6\HTTP\URI;
use Unit6\Hawk;

/**
 * PortalPal Client Class
 *
 * Create a client instance.
 */
final class Client
{
    /**
     * API Version
     *
     * @var string
     */
    const VERSION = 'v1';

    /**
     * HTTP Content-Type
     *
     * @var string
     */
    private $contentType = 'application/json';

    /**
     * API Endpoint
     *
     * @var string
     */
    private $endpoint = 'https://api.portalpal.co';

    /**
     * API Identifier
     *
     * @var string
     */
    private $id;

    /**
     * API Key (Secret)
     *
     * @var string
     */
    private $key;

    /**
     * API Algorithm
     *
     * @var string
     */
    private $algorithm = 'sha256';

    /**
     * API User Agent
     *
     * @var string
     */
    private $userAgent;

    /**
     * API Message from Last Request
     *
     * @var array
     */
    private $message;

    /**
     * Client constructor
     *
     * @param array $options Your PortalPal client and service keys.
     *
     * @return void
     */
    public function __construct(array $options)
    {
        if ( ! function_exists('curl_init')) {
            throw new RuntimeException('cURL is required');
        }

        if ( ! function_exists('json_decode')) {
            throw new RuntimeException('JSON is required');
        }

        if (empty($options)) {
            throw new InvalidArgumentException('PortalPal credentials required');
        }

        if (isset($options['endpoint'])) {
            $this->endpoint = $options['endpoint'];
        }

        if ( ! isset($options['id']) || empty($options['id'])) {
            throw new InvalidArgumentException('PortalPal "id" missing');
        }

        $this->id = $options['id'];

        if ( ! isset($options['key']) || empty($options['key'])) {
            throw new InvalidArgumentException('PortalPal "key" missing');
        }

        $this->key = $options['key'];

        if (isset($options['algorithm'])) {
            $this->algorithm = $options['algorithm'];
        }
    }

    /**
     * Get API Version
     *
     * @return string
     */
    public function getVersion()
    {
        return self::VERSION;
    }

    /**
     * Get API Identifier
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get API Key (Secret)
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Get API Algorithm
     *
     * @return string
     */
    public function getAlgorithm()
    {
        return $this->algorithm;
    }

    /**
     * Get API Credentials
     *
     * @return array
     */
    public function getCredentials()
    {
        return [
            'id' => $this->getId(),
            'key' => $this->getKey(),
            'algorithm' => $this->getAlgorithm()
        ];
    }

    /**
     * Get HTTP Content-Type
     *
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * Get API Endpoint
     *
     * @return string
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * Get API Request URL
     *
     * @param string $uri    API resource to request.
     * @param array  $params Query parameters.
     *
     * @return string
     */
    public function getRequestUrl($uri, array $params = [])
    {
        $url = $this->getEndpoint() . '/' . $this->getVersion() . '/' . $uri;

        if ( ! empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        return sprintf('%s', $url);
    }

    /**
     * Set Timeout
     *
     * @param int $timeout
     *
     * @return void
     */
    public function setTimeout($timeout = 3)
    {
        $this->timeout = $timeout;
    }

    /**
     * Generate User Agent for API Request
     *
     * @return string
     */
    private function getUserAgent()
    {
        if ($this->userAgent) {
            return $this->userAgent;
        }

        $arch = (bool)((1<<32)-1) ? 'x64' : 'x86';

        $data = [
            'os.name'      => php_uname('s'),
            'os.version'   => php_uname('r'),
            'os.arch'      => $arch,
            'lang'         => 'php',
            'lang.version' => phpversion(),
            'api.version'  => self::VERSION,
            'owner'        => 'unit6/portalpal'
        ];

        $this->userAgent = http_build_query($data, '', ';');

        return $this->userAgent;
    }

    /**
     * Get Last API Response Message
     *
     * @return $array
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set Last API Response Message
     *
     * @param array $message Response from API containing a type, slug and text.
     *
     * @return void
     */
    public function setMessage(array $message)
    {
        $this->message = $message;
    }

    /**
     * Get Authorization Header
     *
     * Calculate the authorization header for the request using
     * client credentials.
     *
     * @param string $uri     Full endpoint URI.
     * @param string $method  Valid HTTP method
     * @param array  $payload Request body to be sent.
     *
     * @return string
     */
    private function getAuthorization($uri, $method, $payload)
    {
        $options = [
            'content_type' => $this->getContentType(),
            'credentials' => $this->getCredentials(),
            'payload' => $payload
        ];

        return Hawk\Client::header($uri, $method, $options);
    }

    /**
     * Get Authentication from Response
     *
     * @param Response $response API response.
     *
     * @return string
     */
    private function isAuthenticated(Response $response, array $artifacts)
    {
        $options = [
            'headers' => [
                'content-type' => $response->getHeaderLine('content-type'),
                'server-authorization' => $response->getHeaderLine('server-authorization')
            ]
        ];

        return Hawk\Client::authenticate($options, $this->getCredentials(), $artifacts);
    }

    /**
     * Sends request to PortalPal API
     *
     * @param string $resource URI of API resource
     * @param array  $params   Query string parameters
     *
     * @return array
     */
    public function request($resource, array $params = [])
    {
        $body = null;
        $method = 'GET';

        $url = $this->getRequestUrl($resource, $params);

        $authorization = $this->getAuthorization($url, $method, $body);

        $headers = new Headers();
        $headers->set('Authorization', $authorization['field']);
        $headers->set('Content-Type', $this->getContentType());
        #$headers->set('X-PortalPal-User-Agent', $this->getUserAgent());

        $request = new Request($method, $url, $headers, $body);

        // other cURL options.
        $options = [];

        try {
            $response = $request->send($options);
        } catch (UnexpectedValueException $e) {
            var_dump($e->getMessage()); exit;

            // handle cURL related errors.
            // see: http://php.net/curl.constants#93950
            $errno = $e->getCode();

            if ($errno === CURLE_SSL_CACERT) { // 60
                // Peer certificate cannot be authenticated with known CA certificates.
                throw new GatewayException(sprintf('PortalPal SSL certificate could not be validated; %s', $e->getMessage()), $errno);
            } elseif ($errno === CURLE_OPERATION_TIMEOUTED) { // 28 or CURLE_OPERATION_TIMEDOUT.
                // Operation timeout. The specified time-out period was reached according to the conditions.
                throw new GatewayException(sprintf('PortalPal timeout or possible order failure; %s', $e->getMessage()), $errno);
            } else {
                throw new GatewayException(sprintf('PortalPal is currently unavailable, please try again later; %s', $e->getMessage()), $errno);
            }
        }

        if ( ! $this->isAuthenticated($response, $authorization['artifacts'])) {
            throw new GatewayException('PortalPal response authentication failed');
        }

        $contents = $response->getBody()->getContents();
        $result = json_decode($contents, $assoc = true);

        if ($errno = json_last_error()) {
            throw new GatewayException(sprintf('PortalPal response JSON malformed; %s', json_last_error_msg()), $errno);
        }

        /*
        echo 'Server-Authorization: ' . $response->getHeaderLine('server-authorization') . PHP_EOL;
        echo 'Content-Type: ' . $response->getHeaderLine('content-type') . PHP_EOL;
        echo 'Status Code: ' . $response->getStatusCode() . PHP_EOL;
        echo 'Reason Phrase: ' . $response->getReasonPhrase() . PHP_EOL;
        echo 'Contents: ' . PHP_EOL . $contents . PHP_EOL;
        exit;
        */

        /*
        if (isset($result['message'])) {
            $this->setMessage($result['message']);
        }
        */

        return [
            'statusCode' => $response->getStatusCode(),
            'reasonPhrase' => $response->getReasonPhrase(),
            'content' => $result
            #'content' => $result['content']
        ];
    }

    /**
     * Check Status Page
     *
     * Determine whether or not the service is online.
     *
     * @return boolean
     */
    public function isOK()
    {
        $url = $this->getEndpoint() . '/_status';

        $response = (new Request('GET', $url))->send();

        return $response->getReasonPhrase() === 'OK';
    }

    /**
     * Get Properties
     *
     * @param Search $search
     *
     * @return Collection
     */
    public function getProperties(Search $search)
    {
        $params = $search->getParameters();

        $response = $this->request('properties', $params);

        return Collection::parse($response);
    }

    /**
     * Get Property
     *
     * @param integer $id     Propery WebID.
     * @param array   $params Additional query string parameters.
     *
     * @return Property
     */
    public function getProperty($id, array $params = [])
    {
        if ( ! is_numeric($id)) {
            throw new InvalidArgumentException('Property identifer (WebID) must be a number');
        }

        $defaults = [
            'format' => 1
        ];

        $params = array_merge($defaults, $params);
        $resource = sprintf('properties/%d', (integer) $id);

        $response = $this->request($resource, $params);

        return Property::parse($response);
    }
}
