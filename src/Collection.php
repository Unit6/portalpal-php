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
use UnexpectedValueException;

/**
 * Collection Class
 *
 * Create an collection instance.
 */
class Collection
{
    /**
     * Total
     *
     * @var integer
     */
    private $total = 0;

    /**
     * Count
     *
     * @var integer
     */
    private $count = 0;

    /**
     * Previous
     *
     * @var string
     */
    private $previous;

    /**
     * Next
     *
     * @var string
     */
    private $next;

    /**
     * Rows
     *
     * @var array
     */
    private $rows = [];

    /**
     * Creating a Collection
     *
     * @param array $params Collection parameters
     *
     * @return self
     */
    public function __construct(array $params = [])
    {
        if ( ! empty($params)) $this->setParameters($params);
    }

    /**
     * Parse Response to Collection
     *
     * @param array $response Property response.
     *
     * @return self
     */
    public static function parse(array $response)
    {
        $content = [];

        if ($response['reasonPhrase'] === 'OK' && ! empty($response['content'])) {
            $content = $response['content'];
        }

        return new self($content);
    }

    /**
     * Set Collection Parameters
     *
     * @param array $content Collection data.
     *
     * @return string
     */
    private function setParameters(array $content)
    {
        $this->total = $content['total'];
        $this->count = $content['count'];
        $this->previous = $content['previous'];
        $this->next = $content['next'];
        $this->rows = [];

        foreach ($content['rows'] as $i => $row) {
            $this->rows[$i] = new Property($row);
        }
    }

    /**
     * Total
     *
     * @return integer
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Count
     *
     * @return integer
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Previous
     *
     * @return string
     */
    public function getPrevious()
    {
        return $this->previous;
    }

    /**
     * Next
     *
     * @return string
     */
    public function getNext()
    {
        return $this->next;
    }

    /**
     * Collection Rows
     *
     * @return array
     */
    public function getRows()
    {
        return $this->rows;
    }
}