<?php
/*
 * This file is part of the PortalPal package.
 *
 * (c) Unit6 <team@unit6websites.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Unit6\PortalPal;

/**
 * Test Collection of Properties
 *
 * Check for correct operation of the standard features.
 */
class CollectionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group wip
     */
    public function testGetClient()
    {
        global $options;

        $client = new PortalPal\Client($options);

        $this->assertInstanceOf(PortalPal\Client::class, $client);

        return $client;
    }

    /**
     * @depends testGetClient
     */
    public function testDefaultSearch(PortalPal\Client $client)
    {
        $search = new PortalPal\Search();

        $this->assertInstanceOf(PortalPal\Search::class, $search);

        $collection = $client->getProperties($search);

        $this->assertInstanceOf(PortalPal\Collection::class, $collection);

        return $collection;
    }

    /**
     * @depends testDefaultSearch
     */
    public function testCollectionMethods(PortalPal\Collection $collection)
    {
        $methods = [
            'getTotal',
            'getCount',
            'getPrevious',
            'getNext',
            'getRows'
        ];

        foreach ($methods as $method) {
            $this->assertTrue(method_exists($collection, $method));
        }
    }

    /**
     * @depends testDefaultSearch
     */
    public function testCollectionDefaultResult(PortalPal\Collection $collection)
    {
        $this->assertEquals(172, $collection->getTotal());
        $this->assertEquals(1, $collection->getCount());
        $this->assertCount(1, $collection->getRows());

        $property = $collection->getRows()[0];

        $this->assertInstanceOf(PortalPal\Property::class, $property);
    }

    /**
     * @depends testGetClient
     */
    public function testSearchWithSpecificSize(PortalPal\Client $client)
    {
        $size = 7;

        $search = (new PortalPal\Search())->size($size);

        $collection = $client->getProperties($search);

        $this->assertInstanceOf(PortalPal\Collection::class, $collection);

        $this->assertEquals($size, $collection->getCount());
        $this->assertCount($size, $collection->getRows());
    }

    /**
     * @depends testGetClient
     * @group wip
     */
    public function testSearchSortByDate(PortalPal\Client $client)
    {
        $search = (new PortalPal\Search())->featured()->sort('featuredDate', 'desc');

        $collection = $client->getProperties($search);

        $this->assertInstanceOf(PortalPal\Collection::class, $collection);

        $this->assertEquals(1, $collection->getTotal());
        $this->assertEquals(1, $collection->getCount());
        $this->assertCount(1, $collection->getRows());

        $property = $collection->getRows()[0];

        $this->assertInstanceOf(PortalPal\Property::class, $property);
    }

    /**
     * @depends testGetClient
     */
    public function testSearchByAddress(PortalPal\Client $client)
    {
        $address = 'Blythe Road';

        $search = (new PortalPal\Search())->address($address);

        $collection = $client->getProperties($search);

        $this->assertInstanceOf(PortalPal\Collection::class, $collection);

        $this->assertEquals(1, $collection->getTotal());
        $this->assertEquals(1, $collection->getCount());
        $this->assertCount(1, $collection->getRows());

        $property = $collection->getRows()[0];

        $this->assertInstanceOf(PortalPal\Property::class, $property);

        $this->assertEquals($address, $property->getAddressLine());
    }

    /**
     * @depends testGetClient
     */
    public function testSearchWithBody(PortalPal\Client $client)
    {
        $area = 'W2';

        $body = [
            'query' => [
                'bool' => [
                    'must' => [
                        [
                            'bool' => [
                                'should' => [
                                    [
                                        'match_phrase' => [
                                            'Area' => $area
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

        $search = new PortalPal\Search();

        $search->setBody($body);

        $collection = $client->getProperties($search);

        $this->assertInstanceOf(PortalPal\Collection::class, $collection);

        $this->assertEquals(26, $collection->getTotal());
        $this->assertEquals(1, $collection->getCount());
        $this->assertCount(1, $collection->getRows());

        $property = $collection->getRows()[0];

        $this->assertInstanceOf(PortalPal\Property::class, $property);

        $this->assertEquals($area, $property->getArea());
    }

    /**
     * @depends testGetClient
     */
    public function testSearchByArea(PortalPal\Client $client)
    {
        $area = 'W2';

        $search = (new PortalPal\Search())->area($area);

        $collection = $client->getProperties($search);

        $this->assertInstanceOf(PortalPal\Collection::class, $collection);

        $this->assertEquals(26, $collection->getTotal());
        $this->assertEquals(1, $collection->getCount());
        $this->assertCount(1, $collection->getRows());

        $property = $collection->getRows()[0];

        $this->assertInstanceOf(PortalPal\Property::class, $property);

        $this->assertEquals($area, $property->getArea());
    }

    /**
     * @depends testGetClient
     */
    public function testSearchByAreaList(PortalPal\Client $client)
    {
        $area = ['W2', 'NW10'];

        $search = (new PortalPal\Search())->area($area);

        $collection = $client->getProperties($search);

        $this->assertInstanceOf(PortalPal\Collection::class, $collection);

        $this->assertEquals(28, $collection->getTotal());
        $this->assertEquals(1, $collection->getCount());
        $this->assertCount(1, $collection->getRows());

        $property = $collection->getRows()[0];

        $this->assertInstanceOf(PortalPal\Property::class, $property);

        $this->assertContains($property->getArea(), $area);
    }

    /**
     * @depends testGetClient
     */
    public function testSearchByAvailability(PortalPal\Client $client)
    {
        $state = 1;

        $search = (new PortalPal\Search())->availability($state);

        $collection = $client->getProperties($search);

        $this->assertInstanceOf(PortalPal\Collection::class, $collection);

        $this->assertEquals(172, $collection->getTotal());
        $this->assertEquals(1, $collection->getCount());
        $this->assertCount(1, $collection->getRows());

        $property = $collection->getRows()[0];

        $this->assertInstanceOf(PortalPal\Property::class, $property);

        $this->assertNotEquals('Not Available', $property->getStatus());
    }

    /**
     * @depends testGetClient
     */
    public function testSearchForUnavailableProperties(PortalPal\Client $client)
    {
        $search = (new PortalPal\Search())->unavailable();

        $collection = $client->getProperties($search);

        $this->assertInstanceOf(PortalPal\Collection::class, $collection);

        $this->assertEquals(1, $collection->getTotal());
        $this->assertEquals(1, $collection->getCount());
        $this->assertCount(1, $collection->getRows());

        $property = $collection->getRows()[0];

        $this->assertInstanceOf(PortalPal\Property::class, $property);

        $this->assertEquals('Not Available', $property->getStatus());
    }

    /**
     * @depends testGetClient
     */
    public function testSearchForAvailableProperties(PortalPal\Client $client)
    {
        $search = (new PortalPal\Search())->available();

        $collection = $client->getProperties($search);

        $this->assertInstanceOf(PortalPal\Collection::class, $collection);

        $this->assertEquals(172, $collection->getTotal());
        $this->assertEquals(1, $collection->getCount());
        $this->assertCount(1, $collection->getRows());

        $property = $collection->getRows()[0];

        $this->assertInstanceOf(PortalPal\Property::class, $property);

        $this->assertNotEquals('Not Available', $property->getStatus());
    }

    /**
     * @depends testGetClient
     */
    public function testSearchByBathroomsMin(PortalPal\Client $client)
    {
        $min = 5;

        $search = (new PortalPal\Search())->bathrooms(['min' => $min]);

        $collection = $client->getProperties($search);

        $this->assertInstanceOf(PortalPal\Collection::class, $collection);

        $this->assertEquals(3, $collection->getTotal());
        $this->assertEquals(1, $collection->getCount());
        $this->assertCount(1, $collection->getRows());

        $property = $collection->getRows()[0];

        $this->assertInstanceOf(PortalPal\Property::class, $property);

        $this->assertGreaterThanOrEqual($min, $property->getBathrooms());
    }

    /**
     * @depends testGetClient
     */
    public function testSearchByBathroomsMax(PortalPal\Client $client)
    {
        $max = 5;

        $search = (new PortalPal\Search())->bathrooms(['max' => $max]);

        $collection = $client->getProperties($search);

        $this->assertInstanceOf(PortalPal\Collection::class, $collection);

        $this->assertEquals(170, $collection->getTotal());
        $this->assertEquals(1, $collection->getCount());
        $this->assertCount(1, $collection->getRows());

        $property = $collection->getRows()[0];

        $this->assertInstanceOf(PortalPal\Property::class, $property);

        $this->assertLessThanOrEqual($max, $property->getBathrooms());
    }

    /**
     * @depends testGetClient
     */
    public function testSearchByBathroomsRange(PortalPal\Client $client)
    {
        $min = 5;
        $max = 6;

        $search = (new PortalPal\Search())->bathrooms(['min' => $min, 'max' => $max]);

        $collection = $client->getProperties($search);

        $this->assertInstanceOf(PortalPal\Collection::class, $collection);

        $this->assertEquals(2, $collection->getTotal());
        $this->assertEquals(1, $collection->getCount());
        $this->assertCount(1, $collection->getRows());

        $property = $collection->getRows()[0];

        $this->assertInstanceOf(PortalPal\Property::class, $property);

        $this->assertGreaterThanOrEqual($min, $property->getBathrooms());
        $this->assertLessThanOrEqual($max, $property->getBathrooms());
    }

    /**
     * @depends testGetClient
     */
    public function testSearchByBedroomsMin(PortalPal\Client $client)
    {
        $min = 5;

        $search = (new PortalPal\Search())->bedrooms(['min' => $min]);

        $collection = $client->getProperties($search);

        $this->assertInstanceOf(PortalPal\Collection::class, $collection);

        $this->assertEquals(15, $collection->getTotal());
        $this->assertEquals(1, $collection->getCount());
        $this->assertCount(1, $collection->getRows());

        $property = $collection->getRows()[0];

        $this->assertInstanceOf(PortalPal\Property::class, $property);

        $this->assertGreaterThanOrEqual($min, $property->getBedrooms());
    }

    /**
     * @depends testGetClient
     */
    public function testSearchByBedroomsMax(PortalPal\Client $client)
    {
        $max = 5;

        $search = (new PortalPal\Search())->bedrooms(['max' => $max]);

        $collection = $client->getProperties($search);

        $this->assertInstanceOf(PortalPal\Collection::class, $collection);

        $this->assertEquals(166, $collection->getTotal());
        $this->assertEquals(1, $collection->getCount());
        $this->assertCount(1, $collection->getRows());

        $property = $collection->getRows()[0];

        $this->assertInstanceOf(PortalPal\Property::class, $property);

        $this->assertLessThanOrEqual($max, $property->getBedrooms());
    }

    /**
     * @depends testGetClient
     */
    public function testSearchByBedroomsRange(PortalPal\Client $client)
    {
        $min = 5;
        $max = 6;

        $search = (new PortalPal\Search())->bedrooms(['min' => $min, 'max' => $max]);

        $collection = $client->getProperties($search);

        $this->assertInstanceOf(PortalPal\Collection::class, $collection);

        $this->assertEquals(12, $collection->getTotal());
        $this->assertEquals(1, $collection->getCount());
        $this->assertCount(1, $collection->getRows());

        $property = $collection->getRows()[0];

        $this->assertInstanceOf(PortalPal\Property::class, $property);

        $this->assertGreaterThanOrEqual($min, $property->getBedrooms());
        $this->assertLessThanOrEqual($max, $property->getBedrooms());
    }

    /**
     * @depends testGetClient
     */
    public function testSearchByCategory(PortalPal\Client $client)
    {
        $category = 'Lettings';

        $search = (new PortalPal\Search())->category($category);

        $collection = $client->getProperties($search);

        $this->assertInstanceOf(PortalPal\Collection::class, $collection);

        $this->assertEquals(70, $collection->getTotal());
        $this->assertEquals(1, $collection->getCount());
        $this->assertCount(1, $collection->getRows());

        $property = $collection->getRows()[0];

        $this->assertInstanceOf(PortalPal\Property::class, $property);

        $this->assertEquals($category, $property->getCategory());
    }

    /**
     * @depends testGetClient
     */
    public function testSearchSalesProperties(PortalPal\Client $client)
    {
        $search = (new PortalPal\Search())->sales();

        $collection = $client->getProperties($search);

        $this->assertInstanceOf(PortalPal\Collection::class, $collection);

        $this->assertEquals(102, $collection->getTotal());
        $this->assertEquals(1, $collection->getCount());
        $this->assertCount(1, $collection->getRows());

        $property = $collection->getRows()[0];

        $this->assertInstanceOf(PortalPal\Property::class, $property);

        $this->assertEquals('Sales', $property->getCategory());
    }

    /**
     * @depends testGetClient
     */
    public function testSearchLettingsProperties(PortalPal\Client $client)
    {
        $search = (new PortalPal\Search())->lettings();

        $collection = $client->getProperties($search);

        $this->assertInstanceOf(PortalPal\Collection::class, $collection);

        $this->assertEquals(70, $collection->getTotal());
        $this->assertEquals(1, $collection->getCount());
        $this->assertCount(1, $collection->getRows());

        $property = $collection->getRows()[0];

        $this->assertInstanceOf(PortalPal\Property::class, $property);

        $this->assertEquals('Lettings', $property->getCategory());
    }

    /**
     * @depends testGetClient
     */
    public function testSearchByClassification(PortalPal\Client $client)
    {
        $classification = 'Houses';

        $search = (new PortalPal\Search())->classification($classification);

        $collection = $client->getProperties($search);

        $this->assertInstanceOf(PortalPal\Collection::class, $collection);

        $this->assertEquals(45, $collection->getTotal());
        $this->assertEquals(1, $collection->getCount());
        $this->assertCount(1, $collection->getRows());

        $property = $collection->getRows()[0];

        $this->assertInstanceOf(PortalPal\Property::class, $property);

        $this->assertEquals($classification, $property->getClassification());
    }

    /**
     * @depends testGetClient
     * @group wip
     */
    public function testSearchByCreatedDate(PortalPal\Client $client)
    {
        $dt = new DateTime('2016-07-06 16:06:36.000000');

        $search = (new PortalPal\Search())->createdDate($dt);

        $collection = $client->getProperties($search);

        $this->assertInstanceOf(PortalPal\Collection::class, $collection);

        $this->assertEquals(21, $collection->getTotal());
        $this->assertEquals(1, $collection->getCount());
        $this->assertCount(1, $collection->getRows());

        $property = $collection->getRows()[0];

        $this->assertInstanceOf(PortalPal\Property::class, $property);

        $this->assertEquals($dt, $property->getCreatedDate());
    }

    /**
     * @depends testGetClient
     */
    public function testSearchByFeature(PortalPal\Client $client)
    {
        $term = 'garden';
        $number = 9;

        $search = (new PortalPal\Search())->feature($term, $number);

        $collection = $client->getProperties($search);

        $this->assertInstanceOf(PortalPal\Collection::class, $collection);

        $this->assertEquals(1, $collection->getTotal());
        $this->assertEquals(1, $collection->getCount());
        $this->assertCount(1, $collection->getRows());

        $property = $collection->getRows()[0];

        $this->assertInstanceOf(PortalPal\Property::class, $property);

        $this->assertEquals($term, $property->getFeature($number));
    }

    /**
     * @depends testGetClient
     */
    public function testSearchByFeatured(PortalPal\Client $client)
    {
        $search = (new PortalPal\Search())->featured();

        $collection = $client->getProperties($search);

        $this->assertInstanceOf(PortalPal\Collection::class, $collection);

        $this->assertEquals(1, $collection->getTotal());
        $this->assertEquals(1, $collection->getCount());
        $this->assertCount(1, $collection->getRows());

        $property = $collection->getRows()[0];

        $this->assertInstanceOf(PortalPal\Property::class, $property);

        $this->assertTrue($property->isFeatured());

        $dt = new DateTime('2016-09-13 12:57:08.000000');

        $this->assertEquals($dt, $property->getFeaturedDate());
    }

    /**
     * @depends testGetClient
     */
    public function testSearchByFeatures(PortalPal\Client $client)
    {
        $features = [
            'pet friendly',
            'garden'
        ];

        $search = (new PortalPal\Search())->features($features);

        $collection = $client->getProperties($search);

        $this->assertInstanceOf(PortalPal\Collection::class, $collection);

        $this->assertEquals(1, $collection->getTotal());
        $this->assertEquals(1, $collection->getCount());
        $this->assertCount(1, $collection->getRows());

        $property = $collection->getRows()[0];

        $this->assertInstanceOf(PortalPal\Property::class, $property);

        $this->assertContains('garden', $property->getFeatures());
        $this->assertContains('pet friendly', $property->getFeature(5), $message = null, $ignoreCase = true);
    }

    /**
     * @depends testGetClient
     */
    public function testSearchBySpecificFeatures(PortalPal\Client $client)
    {
        $features = [
            ['garden', 4],
            ['pet friendly', 5]
        ];

        $search = (new PortalPal\Search())->features($features);

        $collection = $client->getProperties($search);

        $this->assertInstanceOf(PortalPal\Collection::class, $collection);

        $this->assertEquals(1, $collection->getTotal());
        $this->assertEquals(1, $collection->getCount());
        $this->assertCount(1, $collection->getRows());

        $property = $collection->getRows()[0];

        $this->assertInstanceOf(PortalPal\Property::class, $property);

        $this->assertContains('garden', $property->getFeature(4), $message = null, $ignoreCase = true);
        $this->assertContains('pet friendly', $property->getFeature(5), $message = null, $ignoreCase = true);
    }

    /**
     * @depends testGetClient
     */
    public function testSearchByFurnished(PortalPal\Client $client)
    {
        $furnished = 'Furnished or Unfurnished';

        $search = (new PortalPal\Search())->furnished($furnished);

        $collection = $client->getProperties($search);

        $this->assertInstanceOf(PortalPal\Collection::class, $collection);

        $this->assertEquals(18, $collection->getTotal());
        $this->assertEquals(1, $collection->getCount());
        $this->assertCount(1, $collection->getRows());

        $property = $collection->getRows()[0];

        $this->assertInstanceOf(PortalPal\Property::class, $property);

        $this->assertEquals($furnished, $property->getFurnished());
    }

    /**
     * @depends testGetClient
     */
    public function testSearchByKeyword(PortalPal\Client $client)
    {
        $keyword = 'swimming pool';

        $search = (new PortalPal\Search())->keyword($keyword);

        $collection = $client->getProperties($search);

        $this->assertInstanceOf(PortalPal\Collection::class, $collection);

        $this->assertEquals(1, $collection->getTotal());
        $this->assertEquals(1, $collection->getCount());
        $this->assertCount(1, $collection->getRows());

        $property = $collection->getRows()[0];

        $this->assertInstanceOf(PortalPal\Property::class, $property);

        $this->assertContains($keyword, $property->getDescription(), $message = null, $ignoreCase = true);
    }

    /**
     * @depends testGetClient
     */
    public function testSearchByPostcode(PortalPal\Client $client)
    {
        $postcode = 'W14 0HD';

        $search = (new PortalPal\Search())->postcode($postcode);

        $collection = $client->getProperties($search);

        $this->assertInstanceOf(PortalPal\Collection::class, $collection);

        $this->assertEquals(1, $collection->getTotal());
        $this->assertEquals(1, $collection->getCount());
        $this->assertCount(1, $collection->getRows());

        $property = $collection->getRows()[0];

        $this->assertInstanceOf(PortalPal\Property::class, $property);

        $this->assertEquals($postcode, $property->getPostcode());
    }

    /**
     * @depends testGetClient
     */
    public function testSearchByPriceMin(PortalPal\Client $client)
    {
        $min = 6900;

        $search = (new PortalPal\Search())->price(['min' => $min]);

        $collection = $client->getProperties($search);

        $this->assertInstanceOf(PortalPal\Collection::class, $collection);

        $this->assertEquals(106, $collection->getTotal());
        $this->assertEquals(1, $collection->getCount());
        $this->assertCount(1, $collection->getRows());

        $property = $collection->getRows()[0];

        $this->assertInstanceOf(PortalPal\Property::class, $property);

        $this->assertGreaterThanOrEqual($min, $property->getPriceBase());
    }

    /**
     * @depends testGetClient
     */
    public function testSearchByPriceMax(PortalPal\Client $client)
    {
        $max = 570000;

        $search = (new PortalPal\Search())->price(['max' => $max]);

        $collection = $client->getProperties($search);

        $this->assertInstanceOf(PortalPal\Collection::class, $collection);

        $this->assertEquals(84, $collection->getTotal());
        $this->assertEquals(1, $collection->getCount());
        $this->assertCount(1, $collection->getRows());

        $property = $collection->getRows()[0];

        $this->assertInstanceOf(PortalPal\Property::class, $property);

        $this->assertLessThanOrEqual($max, $property->getPriceBase());
    }

    /**
     * @depends testGetClient
     */
    public function testSearchByPriceRange(PortalPal\Client $client)
    {
        $min = 6900;
        $max = 570000;

        $search = (new PortalPal\Search())->price(['min' => $min, 'max' => $max]);

        $collection = $client->getProperties($search);

        $this->assertInstanceOf(PortalPal\Collection::class, $collection);

        $this->assertEquals(18, $collection->getTotal());
        $this->assertEquals(1, $collection->getCount());
        $this->assertCount(1, $collection->getRows());

        $property = $collection->getRows()[0];

        $this->assertInstanceOf(PortalPal\Property::class, $property);

        $this->assertGreaterThanOrEqual($min, $property->getPriceBase());
        $this->assertLessThanOrEqual($max, $property->getPriceBase());
    }

    /**
     * @depends testGetClient
     */
    public function testSearchByPropertyId(PortalPal\Client $client)
    {
        $propertyId = '1867826';

        $search = (new PortalPal\Search())->propertyId($propertyId);

        $collection = $client->getProperties($search);

        $this->assertInstanceOf(PortalPal\Collection::class, $collection);

        $this->assertEquals(1, $collection->getTotal());
        $this->assertEquals(1, $collection->getCount());
        $this->assertCount(1, $collection->getRows());

        $property = $collection->getRows()[0];

        $this->assertInstanceOf(PortalPal\Property::class, $property);
        $this->assertEquals($propertyId, $property->getId());
    }

    /**
     * @depends testGetClient
     */
    public function testSearchByRadius(PortalPal\Client $client)
    {
        $lat = 51.506850;
        $lon = -0.126318;
        $distance = '4km';

        $search = (new PortalPal\Search())->radius($lat, $lon, $distance);

        $collection = $client->getProperties($search);

        $this->assertInstanceOf(PortalPal\Collection::class, $collection);

        $this->assertEquals(1, $collection->getTotal());
        $this->assertEquals(1, $collection->getCount());
        $this->assertCount(1, $collection->getRows());

        $property = $collection->getRows()[0];

        $this->assertInstanceOf(PortalPal\Property::class, $property);

        $this->assertEquals('51.5159974732568', $property->getLatitude());
        $this->assertEquals('-0.179251887154933', $property->getLongitude());
    }

    /**
     * @depends testGetClient
     */
    public function testSearchByReceptionsMin(PortalPal\Client $client)
    {
        $min = 1;

        $search = (new PortalPal\Search())->receptions(['min' => $min]);

        $collection = $client->getProperties($search);

        $this->assertInstanceOf(PortalPal\Collection::class, $collection);

        $this->assertEquals(158, $collection->getTotal());
        $this->assertEquals(1, $collection->getCount());
        $this->assertCount(1, $collection->getRows());

        $property = $collection->getRows()[0];

        $this->assertInstanceOf(PortalPal\Property::class, $property);

        $this->assertGreaterThanOrEqual($min, $property->getReceptions());
    }

    /**
     * @depends testGetClient
     */
    public function testSearchByReceptionsMax(PortalPal\Client $client)
    {
        $max = 1;

        $search = (new PortalPal\Search())->receptions(['max' => $max]);

        $collection = $client->getProperties($search);

        $this->assertInstanceOf(PortalPal\Collection::class, $collection);

        $this->assertEquals(142, $collection->getTotal());
        $this->assertEquals(1, $collection->getCount());
        $this->assertCount(1, $collection->getRows());

        $property = $collection->getRows()[0];

        $this->assertInstanceOf(PortalPal\Property::class, $property);

        $this->assertLessThanOrEqual($max, $property->getReceptions());
    }

    /**
     * @depends testGetClient
     */
    public function testSearchByReceptionsRange(PortalPal\Client $client)
    {
        $min = 1;
        $max = 3;

        $search = (new PortalPal\Search())->receptions(['min' => $min, 'max' => $max]);

        $collection = $client->getProperties($search);

        $this->assertInstanceOf(PortalPal\Collection::class, $collection);

        $this->assertEquals(156, $collection->getTotal());
        $this->assertEquals(1, $collection->getCount());
        $this->assertCount(1, $collection->getRows());

        $property = $collection->getRows()[0];

        $this->assertInstanceOf(PortalPal\Property::class, $property);

        $this->assertGreaterThanOrEqual($min, $property->getReceptions());
        $this->assertLessThanOrEqual($max, $property->getReceptions());
    }

    /**
     * @depends testGetClient
     */
    public function testSearchByStatus(PortalPal\Client $client)
    {
        $status = 'SSTC';

        $search = (new PortalPal\Search())->status($status);

        $collection = $client->getProperties($search);

        $this->assertInstanceOf(PortalPal\Collection::class, $collection);

        $this->assertEquals(49, $collection->getTotal());
        $this->assertEquals(1, $collection->getCount());
        $this->assertCount(1, $collection->getRows());

        $property = $collection->getRows()[0];

        $this->assertInstanceOf(PortalPal\Property::class, $property);

        $this->assertEquals($status, $property->getStatus());
    }

    /**
     * @depends testGetClient
     */
    public function testSearchByType(PortalPal\Client $client)
    {
        $type = 'Duplex';

        $search = (new PortalPal\Search())->type($type);

        $collection = $client->getProperties($search);

        $this->assertInstanceOf(PortalPal\Collection::class, $collection);

        $this->assertEquals(5, $collection->getTotal());
        $this->assertEquals(1, $collection->getCount());
        $this->assertCount(1, $collection->getRows());

        $property = $collection->getRows()[0];

        $this->assertInstanceOf(PortalPal\Property::class, $property);

        $this->assertEquals($type, $property->getType());
    }

    /**
     * @depends testGetClient
     * @group wip
     */
    public function testSearchByUpdateDate(PortalPal\Client $client)
    {
        $dt = new DateTime('2016-09-23 11:29:51.000000');

        $search = (new PortalPal\Search())->updatedDate($dt);

        $collection = $client->getProperties($search);

        $this->assertInstanceOf(PortalPal\Collection::class, $collection);

        $this->assertEquals(6, $collection->getTotal());
        $this->assertEquals(1, $collection->getCount());
        $this->assertCount(1, $collection->getRows());

        $property = $collection->getRows()[0];

        $this->assertInstanceOf(PortalPal\Property::class, $property);

        $this->assertEquals($dt, $property->getUpdatedDate());
    }
}