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
 * Test Property Features
 *
 * Check for correct operation of the Property class.
 */
class PropertyTest extends PHPUnit_Framework_TestCase
{
    public function testGetProperty()
    {
        global $options;

        $client = new PortalPal\Client($options);
        $property = $client->getProperty(WEB_ID);

        $this->assertTrue($property instanceof PortalPal\Property);

        return $property;
    }

    /**
     * @depends testGetProperty
     */
    public function testPropertyMethods(PortalPal\Property $property)
    {
        $methods = [
            'getAddress',
            'getPrice',
            'getPriceBase',
            'getArea',
            'getAvailableDate',
            'getBathrooms',
            'getBedrooms',
            'getBrochures',
            'getCompanyId',
            'getCompanyName',
            'getClassification',
            'getUpdatedDate',
            'getEpcDocuments',
            'getEpcImages',
            'getFeature',
            'getFeatures',
            'getFeatured',
            'getFeaturedDate',
            'getFeesText',
            'getFeesUrl',
            'getFees',
            'getFloorPlans',
            'getFloors',
            'getFurnished',
            'getGroundRent',
            'getHipDocuments',
            'getCreatedDate',
            'getKeywords',
            'getDescription',
            'getNewHome',
            'getOfficeEmail',
            'getOfficeId',
            'getOfficeManager',
            'getOfficeName',
            'getOfficePhone',
            'getOfficeUrl',
            'getOutsideSpace',
            'getParking',
            'getPhotos',
            'getPhotosText',
            'getPostcodeOut',
            'getPostcodeIn',
            'getPostcode',
            'getPriceQualifier',
            'getPriceDisplay',
            'getPrimaryKey',
            'getType',
            'getReceptions',
            'getRentPeriod',
            'getDescriptionRtf',
            'getSellingState',
            'getServiceCharge',
            'getServiceProvided',
            'getTitle',
            'getSummary',
            'getTenure',
            'getTenureType',
            'getTown',
            'getCategory',
            'getId',
            'getUrls',
            'getStatus',
            'getLatitude',
            'getLongitude'
        ];

        foreach ($methods as $method) {
            $this->assertTrue(method_exists($property, $method));
        }
    }

    /**
     * @depends testGetProperty
     */
    public function testTypeOfAvailableDate(PortalPal\Property $property)
    {
        $this->assertInstanceOf(DateTime::class, $property->getAvailableDate());
    }

    /**
     * @depends testGetProperty
     */
    public function testPropertyCriteriaOf(PortalPal\Property $property)
    {
        $this->assertTrue(is_bool($property->classificationOf('Commercial Property')));
    }

    /**
     * @depends testGetProperty
     */
    public function testPropertyIsCommercial(PortalPal\Property $property)
    {
        $this->assertTrue(is_bool($property->isCommerical()));
    }

    /**
     * @depends testGetProperty
     */
    public function testTypeOfUpdatedDate(PortalPal\Property $property)
    {
        $this->assertInstanceOf(DateTime::class, $property->getUpdatedDate());
    }

    /**
     * @depends testGetProperty
     */
    public function testPropertyIsFeatured(PortalPal\Property $property)
    {
        $this->assertTrue(is_bool($property->isFeatured()));
    }

    /**
     * @depends testGetProperty
     */
    public function testTypeOfFeatureDate(PortalPal\Property $property)
    {
        $this->assertInstanceOf(DateTime::class, $property->getFeaturedDate());
    }

    /**
     * @depends testGetProperty
     */
    public function testPropertyFees(PortalPal\Property $property)
    {
        $fees = $property->getFees();

        $this->assertArrayHasKey('url', $fees);
        $this->assertArrayHasKey('text', $fees);
    }

    /**
     * @depends testGetProperty
     */
    public function testTypeOfCreatedDate(PortalPal\Property $property)
    {
        $this->assertInstanceOf(DateTime::class, $property->getCreatedDate());
    }

    /**
     * @depends testGetProperty
     */
    public function testPropertyIsNewHome(PortalPal\Property $property)
    {
        $this->assertTrue(is_bool($property->isNewHome()));
    }

    /**
     * @depends testGetProperty
     */
    public function testPropertyOffice(PortalPal\Property $property)
    {
        $office = $property->getOffice();

        $this->assertArrayHasKey('id', $office);
        $this->assertArrayHasKey('manager', $office);
        $this->assertArrayHasKey('email', $office);
        $this->assertArrayHasKey('name', $office);
        $this->assertArrayHasKey('phone', $office);
        $this->assertArrayHasKey('url', $office);
    }

    /**
     * @depends testGetProperty
     */
    public function testPropertyCategoryOf(PortalPal\Property $property)
    {
        $this->assertTrue($property->categoryOf('Sales'));
    }

    /**
     * @depends testGetProperty
     */
    public function testPropertyIsSales(PortalPal\Property $property)
    {
        $this->assertTrue(is_bool($property->isSales()));
    }

    /**
     * @depends testGetProperty
     */
    public function testPropertyIsLettings(PortalPal\Property $property)
    {
        $this->assertTrue(is_bool($property->isLettings()));
    }

    /**
     * @depends testGetProperty
     */
    public function testPropertyStatusOf(PortalPal\Property $property)
    {
        $this->assertTrue($property->statusOf('Available'));
    }

    /**
     * @depends testGetProperty
     */
    public function testPropertyCoordinates(PortalPal\Property $property)
    {
        $cordinates = $property->getCordinates();

        $this->assertArrayHasKey('lat', $cordinates);
        $this->assertArrayHasKey('lon', $cordinates);
    }

    /**
     * @depends testGetProperty
     */
    public function testPropertyPhotosIsArray(PortalPal\Property $property)
    {
        $photos = $property->getPhotos();

        $this->assertTrue(is_array($photos));
    }

    /**
     * @depends testGetProperty
     */
    public function testPropertyVideosIsArray(PortalPal\Property $property)
    {
        $videos = $property->getVideos();

        $this->assertTrue(is_array($videos));
    }
}