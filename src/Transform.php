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
use ReflectionClass;
use ReflectionProperty;

/**
 * PortalPal Transform
 *
 * Create an property resource.
 */
class Transform
{
    /**
     * Convert from API result to Property
     *
     * @param Property $property Instance of the property.
     * @param array    $row      Result of property data.
     *
     * @return void
     */
    public static function toProperty(Property &$property, array $row)
    {
        $rc = new ReflectionClass($property);

        foreach (self::$fieldsIn as $name => $field) {
            $key = $field['alias'];
            $type = $field['type'];

            if ( ! isset($row[$name])) {
                continue;
            }

            $value = $row[$name];

            if (empty($value) && ! is_numeric($value)) {
                continue;
            }

            $rp = $rc->getProperty($key);

            if ( ! ($rp instanceof ReflectionProperty)) {
                throw new UnexpectedValueException('Undefined property: ' . $key);
            }

            if ($type === 'DateTime') {
                $value = DateTime::createFromFormat('Y-m-d\TH:i:s+', $value);
            } elseif ($type === 'array') {
                $value = (array) $value;
            } elseif ($type === 'integer') {
                $value = (integer) $value;
            } elseif ($type === 'boolean') {
                $value = (boolean) $value;
            } elseif ($type === 'string') {
                $value = (string) $value;
            } elseif ($type === 'float') {
                $value = (float) $value;
            }

            $rp->setAccessible(true);
            $rp->setValue($property, $value);
        }
    }

    /**
     * Convert from Property to API parameters
     *
     * @param Property $property Instance of Property.
     *
     * @return array
     */
    public static function fromProperty(Property $property)
    {
        $params = [
            'id' => $property->getId()
        ];

        return $params;
    }

    /**
     * Convert from Search to API parameters
     *
     * @param Search $search Instance of Search.
     *
     * @return array
     */
    public static function fromSearch(Search &$search)
    {
        $params = [
            'size' => $search->getSize(),
            'format' => $search->getFormat(),
            'from' => $search->getFrom()
        ];

        $sort = $search->getSort();

        $sortBy = isset($sort['by']) && isset(self::$fieldsOut[$sort['by']]) ? self::$fieldsOut[$sort['by']] : null;
        $sortOrder = isset($sort['order']) && in_array($sort['order'], ['asc', 'desc']) ? $sort['order'] : null;

        $params['sort'] = sprintf('%s:%s', $sortBy ?: '_id', $sortOrder ?: 'asc');

        if ($address = $search->getAddress()) {
            $params['address'] = $address;
        }

        $availability = $search->getAvailability();
        if (is_integer($availability)) {
            $params['availability'] = $availability;
        }

        $bathrooms = $search->getBathrooms();
        if (is_integer($bathrooms)) {
            $params['bathrooms'] = $bathrooms;
        } elseif (is_array($bathrooms)) {
            if (isset($bathrooms['min'])) $params['bathrooms_min'] = $bathrooms['min'];
            if (isset($bathrooms['max'])) $params['bathrooms_max'] = $bathrooms['max'];
        }

        $bedrooms = $search->getBedrooms();
        if (is_integer($bedrooms)) {
            $params['bedrooms'] = $bedrooms;
        } elseif (is_array($bedrooms)) {
            if (isset($bedrooms['min'])) $params['bedrooms_min'] = $bedrooms['min'];
            if (isset($bedrooms['max'])) $params['bedrooms_max'] = $bedrooms['max'];
        }

        if ($category = $search->getCategory()) {
            $params['category'] = $category;
        }

        if ($classification = $search->getClassification()) {
            $params['classification'] = $classification;
        }

        $createdDate = $search->getCreatedDate();
        if ($createdDate instanceof DateTime) {
            $params['created'] = $createdDate->getTimestamp();
        }

        $featured = $search->getFeatured();
        if (is_integer($featured)) {
            $params['featured'] = $featured;
        }

        if ($features = $search->getFeatures()) {
            $terms = [];

            // Append optional number to the term for corresponding feature field.
            foreach ($features as $feature) {
                $terms[] = $feature['term'] . (isset($feature['number']) ? sprintf(':%d', $feature['number']) : '');
            }

            if ( ! empty($terms)) $params['features'] = implode(';', $terms);
        }

        if ($furnished = $search->getFurnished()) {
            $params['furnished'] = $furnished;
        }

        if ($keyword = $search->getKeyword()) {
            $params['keyword'] = $keyword;
        }

        if ($postcode = $search->getPostcode()) {
            $params['postcode'] = $postcode;
        }

        $price = $search->getPrice();
        if (is_integer($price)) {
            $params['price'] = $price;
        } elseif (is_array($price)) {
            if (isset($price['min'])) $params['price_min'] = $price['min'];
            if (isset($price['max'])) $params['price_max'] = $price['max'];
        }

        if ($propertyId = $search->getPropertyId()) {
            $params['property_id'] = $propertyId;
        }

        $radius = $radius = $search->getRadius();
        if (is_array($radius) && isset($radius['lat'], $radius['lon'], $radius['distance'])) {
            $params['lat'] = $radius['lat'];
            $params['lon'] = $radius['lon'];
            $params['distance'] = $radius['distance'];
        }

        $receptions = $search->getReceptions();
        if (is_integer($receptions)) {
            $params['receptions'] = $receptions;
        } elseif (is_array($receptions)) {
            if (isset($receptions['min'])) $params['receptions_min'] = $receptions['min'];
            if (isset($receptions['max'])) $params['receptions_max'] = $receptions['max'];
        }

        if ($status = $search->getStatus()) {
            $params['status'] = $status;
        }

        if ($type = $search->getType()) {
            $params['type'] = $type;
        }

        $updatedDate = $search->getUpdatedDate();
        if ($updatedDate instanceof DateTime) {
            $params['updated'] = $updatedDate->getTimestamp();
        }

        return $params;
    }

    protected static $fieldsOut = [
        'address' => 'address',
        'price' => 'price',
        'priceBase' => 'price_base',
        'area' => 'area',
        'availableDate' => 'available_at',
        'bathrooms' => 'bathrooms',
        'bedrooms' => 'bedrooms',
        'brochures' => 'brochures',
    //  'buildingName' => 'building_name',
    //  'buildingNumber' => 'building_number',
        'companyId' => 'company_id',
        'companyName' => 'company_name',
    //  'country' => 'country',
    //  'countryCode' => 'country_code',
    //  'county' => 'county',
        'classification' => 'criteria_type',
        'updatedDate' => 'updated_at',
    //  'dependentLocality' => 'dependent_locality',
        'photosText' => 'photos_text',
        'epcDocuments' => 'epc_docs',
        'epcImages' => 'epc_images',
        'features' => 'features',
        'featured' => 'featured',
        'featuredDate' => 'featured_at',
        'feesText' => 'fees_text',
        'feesUrl' => 'fees_url',
        'floorPlans' => 'floor_plans',
        'floors' => 'floors',
        'furnished' => 'furnished',
    //  'geoLat' => 'geo_lat',
    //  'geoLng' => 'geo_lng',
        'groundRent' => 'ground_rent',
        'hipDocuments' => 'hip_docs',
        'createdDate' => 'inserted_at',
        'keywords' => 'keywords',
        'description' => 'description',
    //  'descriptionStr' => 'description_str',
        'newHome' => 'new_home',
        'officeEmail' => 'office_email',
        'officeId' => 'office_id',
        'officeManager' => 'office_manager',
        'officeName' => 'office_name',
        'officePhone' => 'office_phone',
        'officeUrl' => 'office_url',
        'outsideSpace' => 'outside_space',
        'parking' => 'parking',
        'photos' => 'photos',
    //  'portalId' => 'portal_id',
    //  'portalOptions' => 'portal_options',
    //  'portalReference' => 'portal_reference',
    //  'postalZone' => 'postal_zone',
        'postcodeOut' => 'postcode_1',
        'postcodeIn' => 'postcode_2',
        'postcode' => 'postcode',
    //  'postTown' => 'post_town',
        'priceQualifier' => 'price_qualifier',
        'priceDisplay' => 'price_display',
        'primaryKey' => 'property_id',
        'type' => 'property_type',
    //  'propertyTypeId' => 'property_type_id',
        'receptions' => 'receptions',
        'rentPeriod' => 'rent_period',
        'descriptionRtf' => 'description_rtf',
        'sellingState' => 'selling_state',
        'serviceCharge' => 'service_charge',
        'serviceProvided' => 'service_provided',
    //  'sharedCommission' => 'shared_commission',
        'title' => 'title',
    //  'stopUpload' => 'stop_upload',
    //  'street' => 'street',
    //  'subBuildingName' => 'sub_building_name',
        'summary' => 'summary',
    //  'summaryStr' => 'summary_str',
        'tenure' => 'tenure',
    //  'tenureTypeId' => 'tenure_type_id',
        'tenureType' => 'tenure_type',
        'town' => 'town',
        'category' => 'category',
        'videos' => 'videos',
        'videosText' => 'videos_text',
        'id' => 'id',
        'urls' => 'urls',
        'status' => 'status',
        'latitude' => 'latitude',
        'longitude' => 'longitude'
    ];

    protected static $fieldsIn = [
        'address' => ['type' => 'array', 'alias' => 'address'],
        'price' => ['type' => 'integer', 'alias' => 'price'],
        'price_base' => ['type' => 'integer', 'alias' => 'priceBase'],
        'area' => ['type' => 'string', 'alias' => 'area'],
        'available_at' => ['type' => 'DateTime', 'alias' => 'availableDate'],
        'bathrooms' => ['type' => 'integer', 'alias' => 'bathrooms'],
        'bedrooms' => ['type' => 'integer', 'alias' => 'bedrooms'],
        'brochures' => ['type' => 'array', 'alias' => 'brochures'],
    //  'building_name' => ['type' => 'string', 'alias' => 'buildingName'],
    //  'building_number' => ['type' => 'string', 'alias' => 'buildingNumber'],
        'company_id' => ['type' => 'string', 'alias' => 'companyId'],
        'company_name' => ['type' => 'string', 'alias' => 'companyName'],
    //  'country' => ['type' => 'string', 'alias' => 'country'],
    //  'country_code' => ['type' => 'string', 'alias' => 'countryCode'],
    //  'county' => ['type' => 'string', 'alias' => 'county'],
        'criteria_type' => ['type' => 'string', 'alias' => 'classification'],
        'updated_at' => ['type' => 'DateTime', 'alias' => 'updatedDate'],
    //  'dependent_locality' => ['type' => 'string', 'alias' => 'dependentLocality'],
        'photos_text' => ['type' => 'array', 'alias' => 'photosText'],
        'epc_docs' => ['type' => 'array', 'alias' => 'epcDocuments'],
        'epc_images' => ['type' => 'array', 'alias' => 'epcImages'],
        'features' => ['type' => 'array', 'alias' => 'features'],
        'featured' => ['type' => 'boolean', 'alias' => 'featured'],
        'featured_at' => ['type' => 'DateTime', 'alias' => 'featuredDate'],
        'fees_text' => ['type' => 'array', 'alias' => 'feesText'],
        'fees_url' => ['type' => 'string', 'alias' => 'feesUrl'],
        'floor_plans' => ['type' => 'array', 'alias' => 'floorPlans'],
        'floors' => ['type' => 'integer', 'alias' => 'floors'],
        'furnished' => ['type' => 'string', 'alias' => 'furnished'],
    //  'geo_lat' => ['type' => 'float', 'alias' => 'geoLat'],
    //  'geo_lng' => ['type' => 'float', 'alias' => 'geoLng'],
        'ground_rent' => ['type' => 'integer', 'alias' => 'groundRent'],
        'hip_docs' => ['type' => 'array', 'alias' => 'hipDocuments'],
        'inserted_at' => ['type' => 'DateTime', 'alias' => 'createdDate'],
        'keywords' => ['type' => 'string', 'alias' => 'keywords'],
        'description' => ['type' => 'string', 'alias' => 'description'],
    //  'description_str' => ['type' => 'string', 'alias' => 'descriptionStr'],
        'new_home' => ['type' => 'string', 'alias' => 'newHome'],
        'office_email' => ['type' => 'string', 'alias' => 'officeEmail'],
        'office_id' => ['type' => 'string', 'alias' => 'officeId'],
        'office_manager' => ['type' => 'string', 'alias' => 'officeManager'],
        'office_name' => ['type' => 'string', 'alias' => 'officeName'],
        'office_phone' => ['type' => 'string', 'alias' => 'officePhone'],
        'office_url' => ['type' => 'string', 'alias' => 'officeUrl'],
        'outside_space' => ['type' => 'string', 'alias' => 'outsideSpace'],
        'parking' => ['type' => 'string', 'alias' => 'parking'],
        'photos' => ['type' => 'array', 'alias' => 'photos'],
    //  'portal_id' => ['type' => 'string', 'alias' => 'portalId'],
    //  'portal_options' => ['type' => 'string', 'alias' => 'portalOptions'],
    //  'portal_reference' => ['type' => 'string', 'alias' => 'portalReference'],
    //  'postal_zone' => ['type' => 'string', 'alias' => 'postalZone'],
        'postcode_1' => ['type' => 'string', 'alias' => 'postcodeOut'],
        'postcode_2' => ['type' => 'string', 'alias' => 'postcodeIn'],
        'postcode' => ['type' => 'string', 'alias' => 'postcode'],
    //  'post_town' => ['type' => 'string', 'alias' => 'postTown'],
        'price_qualifier' => ['type' => 'string', 'alias' => 'priceQualifier'],
        'price_display' => ['type' => 'string', 'alias' => 'priceDisplay'],
        'property_id' => ['type' => 'string', 'alias' => 'primaryKey'],
        'property_type' => ['type' => 'string', 'alias' => 'type'],
    //  'property_type_id' => ['type' => 'string', 'alias' => 'propertyTypeId'],
        'receptions' => ['type' => 'integer', 'alias' => 'receptions'],
        'rent_period' => ['type' => 'string', 'alias' => 'rentPeriod'],
        'description_rtf' => ['type' => 'string', 'alias' => 'descriptionRtf'],
        'selling_state' => ['type' => 'string', 'alias' => 'sellingState'],
        'service_charge' => ['type' => 'integer', 'alias' => 'serviceCharge'],
        'service_provided' => ['type' => 'string', 'alias' => 'serviceProvided'],
    //  'shared_commission' => ['type' => 'string', 'alias' => 'sharedCommission'],
        'title' => ['type' => 'string', 'alias' => 'title'],
    //  'stop_upload' => ['type' => 'boolean', 'alias' => 'stopUpload'],
    //  'street' => ['type' => 'string', 'alias' => 'street'],
    //  'sub_building_name' => ['type' => 'string', 'alias' => 'subBuildingName'],
        'summary' => ['type' => 'string', 'alias' => 'summary'],
    //  'summary_str' => ['type' => 'string', 'alias' => 'summaryStr'],
        'tenure' => ['type' => 'string', 'alias' => 'tenure'],
    //  'tenure_type_id' => ['type' => 'string', 'alias' => 'tenureTypeId'],
        'tenure_type' => ['type' => 'string', 'alias' => 'tenureType'],
        'town' => ['type' => 'string', 'alias' => 'town'],
        'category' => ['type' => 'string', 'alias' => 'category'],
        'videos' => ['type' => 'array', 'alias' => 'videos'],
        'videos_text' => ['type' => 'array', 'alias' => 'videosText'],
        'id' => ['type' => 'integer', 'alias' => 'id'],
        'urls' => ['type' => 'array', 'alias' => 'urls'],
        'status' => ['type' => 'string', 'alias' => 'status'],
        'latitude' => ['type' => 'float', 'alias' => 'latitude'],
        'longitude' => ['type' => 'float', 'alias' => 'longitude']
    ];
}