<?php
/**
 * This file is part of the PortalPal package.
 *
 * (c) Unit6 <team@unit6websites.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// set the default timezone
date_default_timezone_set('UTC');

// create new session
session_start();

$path = dirname(__FILE__);

require realpath($path . '/../autoload.php');
require realpath($path . '/../vendor/autoload.php');

function getCredentials($path)
{
    $location = $path . '/.keys';

    if ( ! is_readable($location)) {
        throw new UnexpectedValueException('PortalPal test client and service keys required: ' . $location);
    }

    $data = [];

    foreach (file($location) as $line) {
        list($key, $value) = explode(':', $line, 2);
        $data[$key] = trim($value);
    }

    return $data;
};

$credentials = getCredentials($path);

$options = $credentials;
$options['endpoint'] = 'http://localhost:8081';
#$options['endpoint'] = 'https://api-dev.portalpal.co';

define('INVALID_ID', 'fff0000f-0000-0000-00ff-00000f00f00f');
define('PROPERTY_PK', '00000000-E5F2-4A4A-979A-6234E0EFF5B5');
define('WEB_ID', '569365');