<?php
/**
 * This file is part of the PortalPal package.
 *
 * (c) Unit6 <team@unit6websites.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'config.php';

use Unit6\PortalPal;

$client = new PortalPal\Client($options);

/*
$property = $client->getProperty('2342635');

echo sprintf('%s (ID: %d)', $property->getTitle(), $property->getId()) . PHP_EOL;
exit;
*/

$search = (new PortalPal\Search())->size(4);

$body = [
    'query' => [
        'bool' => [
            'must' => [
                [
                    'bool' => [
                        'should' => [
                            [
                                'match_phrase' => [
                                    'Area' => 'W2'
                                ]
                            ]
                        ],
                        'minimum_should_match' => 1,
                        'boost' => 1
                    ]
                ]
            ]
        ]
    ]
];

$search->setBody($body);

/*
$search = (new PortalPal\Search())
    ->size(4)
    ->feature('garden');
*/

$collection = $client->getProperties($search);

foreach ($collection->getRows() as $property) {
    echo sprintf('%s (ID: %d)', $property->getTitle(), $property->getId()) . PHP_EOL;
}