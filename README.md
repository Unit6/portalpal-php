# unit6/portalpal

A client library for the PortalPal API.

This library is restricted for usage by **approved** clients who have been approved for access to the API.

## Requirements

 - PortalPal API Keys
 - PHP 5.6
 - cURL 7.43
 - JSON

## Installation

Install dependencies:

    composer install

Run unit tests:

    ./vendor/bin/phpunit --verbose

## Usage

Create a client application using credentials:

```php
use Unit6\PortalPal;

$client = new PortalPal\Client([
	'id' => '1',
	'key' => 'secret',
	'algorithm' => 'sha256'
]);

// ...

```

Request a single property:

```php

// ...

$property = $client->getProperty(123456);

echo sprintf('%s (ID: %d)', $property->getTitle(), $property->getId()) . PHP_EOL;

// ...

```

Request a collection of properties using a search:

```php

// ...

$search = (new PortalPal\Search())
    ->size(4)
    ->available()
    ->featured()
    ->sort('featuredDate', 'desc');

$collection = $client->getProperties($search);

foreach ($collection->getRows() as $property) {
    echo sprintf('%s (ID: %d)', $property->getTitle(), $property->getId()) . PHP_EOL;
}

// ...

```

## License

MIT, see LICENSE.