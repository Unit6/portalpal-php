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
 * Test Client of Properties
 *
 * Check for correct operation of the standard features.
 */
class ClientTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group wip
     */
    public function testValidOptions()
    {
        global $options;

        $this->assertArrayHasKey('id', $options);
        $this->assertArrayHasKey('key', $options);

        $this->assertNotEmpty($options['id']);
        $this->assertNotEmpty($options['key']);

        return $options;
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testExceptionWithEmptyOptions()
    {
        $client = new PortalPal\Client([]);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testExceptionWithEmptyId()
    {
        $client = new PortalPal\Client(['key' => 'foobar']);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testExceptionWithEmptyKey()
    {
        $client = new PortalPal\Client(['id' => 'foobar']);
    }

    /**
     * @depends testValidOptions
     * @group wip
     */
    public function testCreateInstance(array $options)
    {
        $client = new PortalPal\Client($options);

        $this->assertInstanceOf(PortalPal\Client::class, $client);

        return $client;
    }

    /**
     * @depends testCreateInstance
     */
    public function testSericeOK(PortalPal\Client $client)
    {
        $this->assertTrue($client->isOK());
    }

    /**
     * @depends testCreateInstance
     */
    public function testNullOnInvalidPropertyId(PortalPal\Client $client)
    {
        $property = $client->getProperty(1);

        $this->assertInstanceOf(PortalPal\Property::class, $property);
        $this->assertNull($property->getId());
    }

    /**
     * @depends testCreateInstance
     * @expectedException InvalidArgumentException
     */
    public function testExceptionOnMalformedPropertyId(PortalPal\Client $client)
    {
        $property = $client->getProperty('foobar');
    }

    /**
     * @depends testCreateInstance
     */
    public function testGetSingleProperty(PortalPal\Client $client)
    {
        $property = $client->getProperty(WEB_ID);

        $this->assertInstanceOf(PortalPal\Property::class, $property);
        $this->assertEquals(WEB_ID, $property->getId());
    }

    /**
     * @depends testCreateInstance
     * @group wip
     */
    public function testGetCollectionOfProperties(PortalPal\Client $client)
    {
        $search = (new PortalPal\Search())->size(1);

        $properties = $client->getProperties($search);

        $this->assertInstanceOf(PortalPal\Collection::class, $properties);
    }
}