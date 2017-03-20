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
 * Test Search of Properties
 *
 * Check for correct operation of the standard features.
 */
class SearchTest extends PHPUnit_Framework_TestCase
{
    public function testCreateSearch()
    {
        $search = new PortalPal\Search();

        $this->assertInstanceOf(PortalPal\Search::class, $search);

        $params = $search->getParameters();

        $this->assertArrayHasKey('size', $params);
        $this->assertArrayHasKey('format', $params);
        $this->assertArrayHasKey('from', $params);
        $this->assertArrayHasKey('sort', $params);
        $this->assertArrayHasKey('availability', $params);

        $this->assertEquals(1, $params['size']);
        $this->assertEquals(1, $params['format']);
        $this->assertEquals(0, $params['from']);
        $this->assertEquals(1, $params['availability']);

        return $search;
    }

    /**
     * @depends testCreateSearch
     */
    public function testSearchSize(PortalPal\Search $search)
    {
        $count = rand();

        $search = $search->size($count);

        $this->assertEquals($count, $search->getSize());

        $params = $search->getParameters();

        $this->assertArrayHasKey('size', $params);
        $this->assertEquals($count, $params['size']);
    }

    /**
     * @depends testCreateSearch
     */
    public function testSearchFrom(PortalPal\Search $search)
    {
        $offset = rand();

        $search = $search->from($offset);

        $this->assertEquals($offset, $search->getFrom());

        $params = $search->getParameters();

        $this->assertArrayHasKey('from', $params);
        $this->assertEquals($offset, $params['from']);
    }

    /**
     * @depends testCreateSearch
     */
    public function testSearchFormat(PortalPal\Search $search)
    {
        $format = rand(0, 1);

        $search = $search->format($format);

        $this->assertEquals($format, $search->getFormat());

        $params = $search->getParameters();

        $this->assertArrayHasKey('format', $params);
        $this->assertEquals($format, $params['format']);
    }

    /**
     * @depends testCreateSearch
     */
    public function testSearchSort(PortalPal\Search $search)
    {
        $search = $search->sort('priceBase', 'asc');

        $this->assertEquals(['by' => 'priceBase', 'order' => 'asc'], $search->getSort());

        $params = $search->getParameters();

        $this->assertArrayHasKey('sort', $params);
        $this->assertEquals('price_base:asc', $params['sort']);
    }

    /**
     * @depends testCreateSearch
     */
    public function testSearchAddress(PortalPal\Search $search)
    {
        $address = uniqid();

        $search = $search->address($address);

        $this->assertEquals($address, $search->getAddress());

        $params = $search->getParameters();

        $this->assertArrayHasKey('address', $params);
        $this->assertEquals($address, $params['address']);
    }

    /**
     * @depends testCreateSearch
     */
    public function testSearchArea(PortalPal\Search $search)
    {
        $area = 'W2';

        $search = $search->area($area);

        $this->assertEquals($area, $search->getArea());

        $params = $search->getParameters();

        $this->assertArrayHasKey('area', $params);
        $this->assertEquals($area, $params['area']);
    }

    /**
     * @depends testCreateSearch
     */
    public function testSearchAreaList(PortalPal\Search $search)
    {
        $area = ['W2', 'NW10'];

        $search = $search->area($area);

        $this->assertEquals($area, $search->getArea());

        $params = $search->getParameters();

        $this->assertArrayHasKey('area', $params);
        $this->assertEquals($area, $params['area']);
    }

    /**
     * @depends testCreateSearch
     */
    public function testSearchAvailability(PortalPal\Search $search)
    {
        $state = rand(0, 1);

        $search = $search->availability($state);

        $this->assertEquals($state, $search->getAvailability());

        $params = $search->getParameters();

        $this->assertArrayHasKey('availability', $params);
        $this->assertEquals($state, $params['availability']);
    }

    /**
     * @depends testCreateSearch
     */
    public function testSearchForUnavailableProperties(PortalPal\Search $search)
    {
        $state = 0;

        $search = $search->unavailable();

        $this->assertEquals($state, $search->getAvailability());

        $params = $search->getParameters();

        $this->assertArrayHasKey('availability', $params);
        $this->assertEquals($state, $params['availability']);
    }

    /**
     * @depends testCreateSearch
     */
    public function testSearchForAvailableProperties(PortalPal\Search $search)
    {
        $state = 1;

        $search = $search->available();

        $this->assertEquals($state, $search->getAvailability());

        $params = $search->getParameters();

        $this->assertArrayHasKey('availability', $params);
        $this->assertEquals($state, $params['availability']);
    }

    /**
     * @depends testCreateSearch
     */
    public function testSearchBathrooms(PortalPal\Search $search)
    {
        $number = rand();

        $search = $search->bathrooms($number);

        $this->assertEquals($number, $search->getBathrooms());

        $params = $search->getParameters();

        $this->assertArrayHasKey('bathrooms', $params);
        $this->assertEquals($number, $params['bathrooms']);
    }

    /**
     * @depends testCreateSearch
     */
    public function testSearchBathroomsRange(PortalPal\Search $search)
    {
        $range = [
            'min' => rand(1, 5),
            'max' => rand(6, 10)
        ];

        $search = $search->bathrooms($range);

        $this->assertEquals($range, $search->getBathrooms());

        $params = $search->getParameters();

        $this->assertArrayHasKey('bathrooms_min', $params);
        $this->assertArrayHasKey('bathrooms_max', $params);
        $this->assertEquals($range['min'], $params['bathrooms_min']);
        $this->assertEquals($range['max'], $params['bathrooms_max']);
    }

    /**
     * @depends testCreateSearch
     */
    public function testSearchBedrooms(PortalPal\Search $search)
    {
        $number = rand();

        $search = $search->bedrooms($number);

        $this->assertEquals($number, $search->getBedrooms());

        $params = $search->getParameters();

        $this->assertArrayHasKey('bedrooms', $params);
        $this->assertEquals($number, $params['bedrooms']);
    }

    /**
     * @depends testCreateSearch
     */
    public function testSearchBedroomsRange(PortalPal\Search $search)
    {
        $range = [
            'min' => rand(1, 5),
            'max' => rand(6, 10)
        ];

        $search = $search->bedrooms($range);

        $this->assertEquals($range, $search->getBedrooms());

        $params = $search->getParameters();

        $this->assertArrayHasKey('bedrooms_min', $params);
        $this->assertArrayHasKey('bedrooms_max', $params);
        $this->assertEquals($range['min'], $params['bedrooms_min']);
        $this->assertEquals($range['max'], $params['bedrooms_max']);
    }

    /**
     * @depends testCreateSearch
     */
    public function testSearchCategory(PortalPal\Search $search)
    {
        $category = uniqid();

        $search = $search->category($category);

        $this->assertEquals($category, $search->getCategory());

        $params = $search->getParameters();

        $this->assertArrayHasKey('category', $params);
        $this->assertEquals($category, $params['category']);
    }

    /**
     * @depends testCreateSearch
     */
    public function testSearchSales(PortalPal\Search $search)
    {
        $category = PortalPal\Search::CATEGORY_SALES;

        $search = $search->sales();

        $this->assertEquals($category, $search->getCategory());

        $params = $search->getParameters();

        $this->assertArrayHasKey('category', $params);
        $this->assertEquals($category, $params['category']);
    }

    /**
     * @depends testCreateSearch
     */
    public function testSearchLettings(PortalPal\Search $search)
    {
        $category = PortalPal\Search::CATEGORY_LETTINGS;

        $search = $search->lettings();

        $this->assertEquals($category, $search->getCategory());

        $params = $search->getParameters();

        $this->assertArrayHasKey('category', $params);
        $this->assertEquals($category, $params['category']);
    }

    /**
     * @depends testCreateSearch
     */
    public function testSearchClassification(PortalPal\Search $search)
    {
        $classification = uniqid();

        $search = $search->classification($classification);

        $this->assertEquals($classification, $search->getClassification());

        $params = $search->getParameters();

        $this->assertArrayHasKey('classification', $params);
        $this->assertEquals($classification, $params['classification']);
    }

    /**
     * @depends testCreateSearch
     */
    public function testSearchCreatedDate(PortalPal\Search $search)
    {
        $dt = new DateTime();

        $search = $search->createdDate($dt);

        $this->assertEquals($dt, $search->getCreatedDate());

        $params = $search->getParameters();

        $this->assertArrayHasKey('created', $params);
        $this->assertEquals($dt->getTimestamp(), $params['created']);
    }

    /**
     * @depends testCreateSearch
     */
    public function testSearchFeatured(PortalPal\Search $search)
    {
        $state = rand(0, 1);

        $search = $search->featured($state);

        $this->assertEquals($state, $search->getFeatured());

        $params = $search->getParameters();

        $this->assertArrayHasKey('featured', $params);
        $this->assertEquals($state, $params['featured']);
    }

    /**
     * @depends testCreateSearch
     */
    public function testSearchFurnished(PortalPal\Search $search)
    {
        $type = uniqid();

        $search = $search->furnished($type);

        $this->assertEquals($type, $search->getFurnished());

        $params = $search->getParameters();

        $this->assertArrayHasKey('furnished', $params);
        $this->assertEquals($type, $params['furnished']);
    }

    /**
     * @depends testCreateSearch
     */
    public function testSearchKeyword(PortalPal\Search $search)
    {
        $term = uniqid();

        $search = $search->keyword($term);

        $this->assertEquals($term, $search->getKeyword());

        $params = $search->getParameters();

        $this->assertArrayHasKey('keyword', $params);
        $this->assertEquals($term, $params['keyword']);
    }

    /**
     * @depends testCreateSearch
     */
    public function testSearchPostcode(PortalPal\Search $search)
    {
        $postcode = uniqid();

        $search = $search->postcode($postcode);

        $this->assertEquals($postcode, $search->getPostcode());

        $params = $search->getParameters();

        $this->assertArrayHasKey('postcode', $params);
        $this->assertEquals($postcode, $params['postcode']);
    }

    /**
     * @depends testCreateSearch
     */
    public function testSearchPrice(PortalPal\Search $search)
    {
        $amount = rand();

        $search = $search->price($amount);

        $this->assertEquals($amount, $search->getPrice());

        $params = $search->getParameters();

        $this->assertArrayHasKey('price', $params);
        $this->assertEquals($amount, $params['price']);
    }

    /**
     * @depends testCreateSearch
     */
    public function testSearchPriceRange(PortalPal\Search $search)
    {
        $range = [
            'min' => rand(1000, 10000),
            'max' => rand(11000, 20000)
        ];

        $search = $search->price($range);

        $this->assertEquals($range, $search->getPrice());

        $params = $search->getParameters();

        $this->assertArrayHasKey('price_min', $params);
        $this->assertArrayHasKey('price_max', $params);
        $this->assertEquals($range['min'], $params['price_min']);
        $this->assertEquals($range['max'], $params['price_max']);
    }

    /**
     * @depends testCreateSearch
     */
    public function testSearchPropertyId(PortalPal\Search $search)
    {
        $id = uniqid();

        $search = $search->propertyId($id);

        $this->assertEquals($id, $search->getPropertyId());

        $params = $search->getParameters();

        $this->assertArrayHasKey('property_id', $params);
        $this->assertEquals($id, $params['property_id']);
    }

    /**
     * @depends testCreateSearch
     */
    public function testSearchRadius(PortalPal\Search $search)
    {
        $radius = [
            'lat' => rand(0, 10) / 10,
            'lon' => rand(0, 10) / 10,
            'distance' => '20km'
        ];

        $search = $search->radius($radius['lat'], $radius['lon'], $radius['distance']);

        $this->assertEquals($radius, $search->getRadius());

        $params = $search->getParameters();

        $this->assertArrayHasKey('lat', $params);
        $this->assertArrayHasKey('lon', $params);
        $this->assertArrayHasKey('distance', $params);

        $this->assertEquals($radius['lat'], $params['lat']);
        $this->assertEquals($radius['lon'], $params['lon']);
        $this->assertEquals($radius['distance'], $params['distance']);
    }

    /**
     * @depends testCreateSearch
     */
    public function testSearchReceptions(PortalPal\Search $search)
    {
        $number = rand();

        $search = $search->receptions($number);

        $this->assertEquals($number, $search->getReceptions());

        $params = $search->getParameters();

        $this->assertArrayHasKey('receptions', $params);
        $this->assertEquals($number, $params['receptions']);
    }

    /**
     * @depends testCreateSearch
     */
    public function testSearchReceptionsRange(PortalPal\Search $search)
    {
        $range = [
            'min' => rand(1, 5),
            'max' => rand(6, 10)
        ];

        $search = $search->receptions($range);

        $this->assertEquals($range, $search->getReceptions());

        $params = $search->getParameters();

        $this->assertArrayHasKey('receptions_min', $params);
        $this->assertArrayHasKey('receptions_max', $params);
        $this->assertEquals($range['min'], $params['receptions_min']);
        $this->assertEquals($range['max'], $params['receptions_max']);
    }

    /**
     * @depends testCreateSearch
     */
    public function testSearchStatus(PortalPal\Search $search)
    {
        $status = uniqid();

        $search = $search->status($status);

        $this->assertEquals($status, $search->getStatus());

        $params = $search->getParameters();

        $this->assertArrayHasKey('status', $params);
        $this->assertEquals($status, $params['status']);
    }

    /**
     * @depends testCreateSearch
     */
    public function testSearchType(PortalPal\Search $search)
    {
        $type = uniqid();

        $search = $search->type($type);

        $this->assertEquals($type, $search->getType());

        $params = $search->getParameters();

        $this->assertArrayHasKey('type', $params);
        $this->assertEquals($type, $params['type']);
    }

    /**
     * @depends testCreateSearch
     */
    public function testSearchUpdatedDate(PortalPal\Search $search)
    {
        $dt = new DateTime();

        $search = $search->updatedDate($dt);

        $this->assertEquals($dt, $search->getUpdatedDate());

        $params = $search->getParameters();

        $this->assertArrayHasKey('updated', $params);
        $this->assertEquals($dt->getTimestamp(), $params['updated']);
    }

    /**
     * @depends testCreateSearch
     */
    public function testSearchFeature(PortalPal\Search $search)
    {
        $term = 'garden';
        $number = 4;

        $search = $search->feature($term, $number);

        $this->assertEquals([['term' => $term, 'number' => $number]], $search->getFeatures());

        $params = $search->getParameters();

        $this->assertArrayHasKey('features', $params);
        $this->assertEquals(sprintf('%s:%d', $term, $number), $params['features']);
    }

    /**
     * @depends testCreateSearch
     */
    public function testSearchFeatures(PortalPal\Search $search)
    {
        $term1 = 'pet friendly';
        $term2 = 'garden';

        $featuresInput = [$term1, $term2];
        $featuresList = [['term' => $term1, 'number' => null], ['term' => $term2, 'number' => null]];
        $featuresParameter = sprintf('%s;%s', $term1, $term2);

        $search = $search->features($featuresInput);

        $this->assertEquals($featuresList, $search->getFeatures());

        $params = $search->getParameters();

        $this->assertArrayHasKey('features', $params);
        $this->assertEquals($featuresParameter, $params['features']);
    }

    /**
     * @depends testCreateSearch
     */
    public function testSearchFeaturesInSpecificFields(PortalPal\Search $search)
    {
        $term1 = 'pet friendly'; $number1 = 4;
        $term2 = 'garden'; $number2 = 5;

        $featuresInput = [[$term1, $number1], [$term2, $number2]];
        $featuresList = [['term' => $term1, 'number' => $number1], ['term' => $term2, 'number' => $number2]];
        $featuresParameter = sprintf('%s:%d;%s:%d', $term1, $number1, $term2, $number2);

        $search = $search->features($featuresInput);

        $this->assertEquals($featuresList, $search->getFeatures());

        $params = $search->getParameters();

        $this->assertArrayHasKey('features', $params);
        $this->assertEquals($featuresParameter, $params['features']);
    }
}