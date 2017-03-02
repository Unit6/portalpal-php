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

use DateTime;

/**
 * Search Class
 *
 * Create an search terms.
 */
class Search
{
    /**
     * Sales Category
     *
     * @var string
     */
    const CATEGORY_SALES = 'Sales';

    /**
     * Lettings Category
     *
     * @var string
     */
    const CATEGORY_LETTINGS = 'Lettings';

    /**
     * Results Size
     *
     * Determine the number of rows to return.
     *
     * @var integer
     */
    private $size = 1;

    /**
     * Results From
     *
     * Determine the starting row number or offset for the result set.
     *
     * @var integer
     */
    private $from = 0;

    /**
     * Format Results
     *
     * Formatted results are structured in a more convenient way but
     * column names will also be different.
     *
     * @var integer
     */
    private $format = 1;

    /**
     * Results Sorting
     *
     * @var array
     */
    private $sort;

    /**
     * Property Availability
     *
     * Exclude (1) properties with WebStatus of 'Not Available' or not (0).
     * Default is to exclude 'Not Available' properties from resutls.
     *
     * @var integer
     */
    private $availability = 1;

    /**
     * Property Address
     *
     * Perform a multi-phrase match against address related fields:
     * 'AddressLine*', 'ShortAddress', 'Town', 'PostcodeFull'
     *
     * @var string
     */
    private $address;

    /**
     * Property Bathrooms
     *
     * Match against a number of bathrooms.
     *
     * @var integer|array
     */
    private $bathrooms;

    /**
     * Property Bedrooms
     *
     * Match against a number of bedrooms.
     *
     * @var integer|array
     */
    private $bedrooms;

    /**
     * Property Category
     *
     * Match against 'UploadType' field as either 'Sales' or 'Lettings'.
     *
     * @var string
     */
    private $category;

    /**
     * Property Classification
     *
     * Match against 'CriteriaType' field.
     *
     * @var string
     */
    private $classification;

    /**
     * Property Created Date
     *
     * Match against 'InsertDate' field.
     *
     * @var DateTime
     */
    private $createdDate;

    /**
     * Property Feature
     *
     * Match against any 'Feature*' field (0-9).
     *
     * @var string
     */
    private $feature;

    /**
     * Property Featured
     *
     * Match against 'FeaturedProperty' field.
     *
     * @var string
     */
    private $featured;

    /**
     * Property Features
     *
     * Match against multiple 'Feature*' fields (0-9).
     *
     * @var array
     */
    private $features;

    /**
     * Property Furnished
     *
     * Match against 'Furnished' field.
     *
     * @var string
     */
    private $furnished;

    /**
     * Property Keyword
     *
     * Perform a fuzzy match against the following fields:
     * 'MarketingDescription', 'Postcode1', 'ShortAddress',
     * 'SummaryDescription', 'Town'
     *
     * @var string
     */
    private $keyword;

    /**
     * Property Postcode
     *
     * Prefix phrase match against 'PostcodeFull' field.
     *
     * @var string
     */
    private $postcode;

    /**
     * Property Price
     *
     * Match against a number of receptions.
     *
     * @var integer|array
     */
    private $price;

    /**
     * Property Identifier
     *
     * Match against either a numeric 'WebID' or UUID for 'PropertyPK'.
     *
     * @var integer|string
     */
    private $propertyId;

    /**
     * Property Search Radius
     *
     * Find properties within specified coordinates (lat/lon) and distance.
     *
     * @var array
     */
    private $radius;

    /**
     * Property Receptions
     *
     * Match against a number of receptions.
     *
     * @var integer|array
     */
    private $receptions;

    /**
     * Property Status
     *
     * Match against the 'WebStatus' field.
     *
     * @var string
     */
    private $status;

    /**
     * Property Type
     *
     * Match against the 'PropertyType' field.
     *
     * @var string
     */
    private $type;

    /**
     * Property Updated
     *
     * Match against the 'DateLastUpdated' field.
     *
     * @var DateTime
     */
    private $updatedDate;

    /**
     * Creating a Search
     *
     * @param array $params Search parameters
     *
     * @return self
     */
    public function __construct(array $params = [])
    {
        if ( ! empty($params)) $this->params = $params;
    }

    /**
     * Get Result Size
     *
     * @return integer
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Get Result From
     *
     * @return integer
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Get Result Format
     *
     * @return integer
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * Get Result Sort
     *
     * @return array
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * Get Address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Get Availability
     *
     * @return integer
     */
    public function getAvailability()
    {
        return $this->availability;
    }

    /**
     * Get Bathrooms
     *
     * @return integer|array
     */
    public function getBathrooms()
    {
        return $this->bathrooms;
    }

    /**
     * Get Bedrooms
     *
     * @return integer|array
     */
    public function getBedrooms()
    {
        return $this->bedrooms;
    }

    /**
     * Get Category
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Get Classification
     *
     * @return string
     */
    public function getClassification()
    {
        return $this->classification;
    }

    /**
     * Get Created Date
     *
     * @return DateTime
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * Get Featured
     *
     * @return string
     */
    public function getFeatured()
    {
        return $this->featured;
    }

    /**
     * Get Features
     *
     * @return array
     */
    public function getFeatures()
    {
        return $this->features;
    }

    /**
     * Get Furnished
     *
     * @return string
     */
    public function getFurnished()
    {
        return $this->furnished;
    }

    /**
     * Get Keyword
     *
     * @return string
     */
    public function getKeyword()
    {
        return $this->keyword;
    }

    /**
     * Get Postcode
     *
     * @return string
     */
    public function getPostcode()
    {
        return $this->postcode;
    }

    /**
     * Get Price
     *
     * @return integer|array
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Get Property Identifier
     *
     * @return integer|string
     */
    public function getPropertyId()
    {
        return $this->propertyId;
    }

    /**
     * Get Radius
     *
     * @return array
     */
    public function getRadius()
    {
        return $this->radius;
    }

    /**
     * Get Receptions
     *
     * @return integer|array
     */
    public function getReceptions()
    {
        return $this->receptions;
    }

    /**
     * Get Status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Get Type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get Updated Date
     *
     * @return DateTime
     */
    public function getUpdatedDate()
    {
        return $this->updatedDate;
    }

    /**
     * Get Property Parameters
     *
     * @return array
     */
    public function getParameters()
    {
        return Transform::fromSearch($this);
    }

    /**
     * Result Size
     *
     * Set number of rows to return.
     *
     * @param integer $number
     *
     * @return self
     */
    public function size($number)
    {
        $clone = clone $this;
        $clone->size = (integer) $number;

        return $clone;
    }

    /**
     * Result From (Offset)
     *
     * Set number of rows to start from.
     *
     * @param integer $number
     *
     * @return self
     */
    public function from($number)
    {
        $clone = clone $this;
        $clone->from = (integer) $number;

        return $clone;
    }

    /**
     * Format Result
     *
     * Set number of rows to return.
     *
     * @param boolean $format
     *
     * @return self
     */
    public function format($format = true)
    {
        $clone = clone $this;
        $clone->format = $format ? 1 : 0;

        return $clone;
    }

    /**
     * Property Address
     *
     * @param string $address
     *
     * @return self
     */
    public function address($address)
    {
        $clone = clone $this;
        $clone->address = $address;

        return $clone;
    }

    /**
     * Property Availability
     *
     * @param integer $state
     *
     * @return self
     */
    public function availability($state)
    {
        $clone = clone $this;
        $clone->availability = $state ? 1 : 0;

        return $clone;
    }

    /**
     * Available Properties Only
     *
     * Convenience method for availability parameter.
     *
     * @return self
     */
    public function available()
    {
        $clone = clone $this;
        return $clone->availability(1);
    }

    /**
     * Unavailable Properties Only
     *
     * Convenience method for availability parameter.
     *
     * @return self
     */
    public function unavailable()
    {
        $clone = clone $this;
        return $clone->availability(0);
    }

    /**
     * Property Bathrooms
     *
     * @param integer|array $number Specific number or range.
     *
     * @return self
     */
    public function bathrooms($number)
    {
        $clone = clone $this;
        if (is_numeric($number)) {
            $clone->bathrooms = (integer) $number;
        } elseif (is_array($number)) {
            $clone->bathrooms = [];
            if (isset($number['min']) && is_numeric($number['min'])) $clone->bathrooms['min'] = (integer) $number['min'];
            if (isset($number['max']) && is_numeric($number['max'])) $clone->bathrooms['max'] = (integer) $number['max'];
        }

        return $clone;
    }

    /**
     * Property Bedrooms
     *
     * @param integer|array $number Specific number or range.
     *
     * @return self
     */
    public function bedrooms($number)
    {
        $clone = clone $this;
        if (is_numeric($number)) {
            $clone->bedrooms = (integer) $number;
        } elseif (is_array($number)) {
            $clone->bedrooms = [];
            if (isset($number['min']) && is_numeric($number['min'])) $clone->bedrooms['min'] = (integer) $number['min'];
            if (isset($number['max']) && is_numeric($number['max'])) $clone->bedrooms['max'] = (integer) $number['max'];
        }

        return $clone;
    }

    /**
     * Property Category
     *
     * @param string
     *
     * @return self
     */
    public function category($category)
    {
        $clone = clone $this;
        $clone->category = $category;

        return $clone;
    }

    /**
     * Properties For Sale
     *
     * Convenience method for setting category to 'Sales'.
     *
     * @param string
     *
     * @return self
     */
    public function sales()
    {
        $clone = clone $this;
        return $clone->category(self::CATEGORY_SALES);
    }

    /**
     * Properties To Rent
     *
     * Convenience method for setting category to 'Lettings'.
     *
     * @param string
     *
     * @return self
     */
    public function lettings()
    {
        $clone = clone $this;
        return $clone->category(self::CATEGORY_LETTINGS);
    }

    /**
     * Properties Classification
     *
     * @param string $classification
     *
     * @return self
     */
    public function classification($classification)
    {
        $clone = clone $this;
        $clone->classification = $classification;

        return $clone;
    }

    /**
     * Properties Created Date
     *
     * @param DateTime
     *
     * @return self
     */
    public function createdDate(DateTime $dt)
    {
        $clone = clone $this;
        $clone->createdDate = $dt;

        return $clone;
    }

    /**
     * Featured Property
     *
     * @param string       $term   Term to filter by.
     * @param integer|null $number Numbered feature field to match term against.
     *
     * @return self
     */
    public function feature($term, $number = null)
    {
        $clone = clone $this;
        $clone->features[] = ['term' => $term, 'number' => $number];

        return $clone;
    }

    /**
     * Featured Property
     *
     * @param boolean $featured
     *
     * @return self
     */
    public function featured($featured = 1)
    {
        $clone = clone $this;
        $clone->featured = $featured;

        return $clone;
    }

    /**
     * Featured Property
     *
     * @param array $terms Features to filter by.
     *
     * @return self
     */
    public function features(array $terms)
    {
        $clone = clone $this;
        foreach ($terms as $feature) {
            if (is_string($feature)) {
                $clone = $clone->feature($feature);
            } elseif (is_array($feature)) {
                @list($term, $number) = $feature;
                $clone = $clone->feature($term, $number);
            }
        }

        return $clone;
    }

    /**
     * Property Furnished
     *
     * @param string $state
     *
     * @return self
     */
    public function furnished($state)
    {
        $clone = clone $this;
        $clone->furnished = $state;

        return $clone;
    }

    /**
     * Property Keyword
     *
     * @param string $term
     *
     * @return self
     */
    public function keyword($term)
    {
        $clone = clone $this;
        $clone->keyword = $term;

        return $clone;
    }

    /**
     * Property Postcode
     *
     * @param string $postcode
     *
     * @return self
     */
    public function postcode($postcode)
    {
        $clone = clone $this;
        $clone->postcode = $postcode;

        return $clone;
    }

    /**
     * Property Price
     *
     * @param integer|array $number Specific number or range.
     *
     * @return self
     */
    public function price($number)
    {
        $clone = clone $this;
        if (is_numeric($number)) {
            $clone->price = (integer) $number;
        } elseif (is_array($number)) {
            $clone->price = [];
            if (isset($number['min']) && is_numeric($number['min'])) $clone->price['min'] = (integer) $number['min'];
            if (isset($number['max']) && is_numeric($number['max'])) $clone->price['max'] = (integer) $number['max'];
        }

        return $clone;
    }

    /**
     * Property Identifier
     *
     * @param integer|string $id
     *
     * @return self
     */
    public function propertyId($id)
    {
        $clone = clone $this;
        $clone->propertyId = $id;

        return $clone;
    }

    /**
     * Property Search Radius
     *
     * @param float $lat
     * @param float $lon
     * @param string $distance
     *
     * @return self
     */
    public function radius($lat, $lon, $distance = '20km')
    {
        $clone = clone $this;
        $clone->radius = [
            'lat' => $lat,
            'lon' => $lon,
            'distance' => $distance
        ];

        return $clone;
    }

    /**
     * Property Receptions
     *
     * @param integer|array $number Specific number or range.
     *
     * @return self
     */
    public function receptions($number)
    {
        $clone = clone $this;
        if (is_numeric($number)) {
            $clone->receptions = (integer) $number;
        } elseif (is_array($number)) {
            $clone->receptions = [];
            if (isset($number['min']) && is_numeric($number['min'])) $clone->receptions['min'] = (integer) $number['min'];
            if (isset($number['max']) && is_numeric($number['max'])) $clone->receptions['max'] = (integer) $number['max'];
        }

        return $clone;
    }

    /**
     * Property Status
     *
     * @param string|array $status
     *
     * @return self
     */
    public function status($status)
    {
        $clone = clone $this;
        $clone->status = $status;

        return $clone;
    }

    /**
     * Property Type
     *
     * @param string $type
     *
     * @return self
     */
    public function type($type)
    {
        $clone = clone $this;
        $clone->type = $type;

        return $clone;
    }

    /**
     * Properties Updated Date
     *
     * @param DateTime
     *
     * @return self
     */
    public function updatedDate(DateTime $dt)
    {
        $clone = clone $this;
        $clone->updatedDate = $dt;

        return $clone;
    }

    /**
     * Sort Results
     *
     * @param string $by    Sort results by column.
     * @param string $order Sort order (asc|desc).
     *
     * @return self
     */
    public function sort($by, $order = 'asc')
    {
        $clone = clone $this;
        $clone->sort = ['by' => $by, 'order' => strtolower($order)];

        return $clone;
    }
}