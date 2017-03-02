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
 * PortalPal Class
 *
 * Create an property resource.
 */
class Property
{
    /**
     * Category for properties for sale.
     *
     * @var string
     */
    const CATEGORY_SALES = 'Sales';

    /**
     * Category for properties to rent.
     *
     * @var string
     */
    const CATEGORY_LETTINGS = 'Lettings';

    /**
     * Address
     *
     * @var array
     */
    private $address;

    /**
     * Price
     *
     * @var integer
     */
    private $price;

    /**
     * Price Base
     *
     * @var integer
     */
    private $priceBase;

    /**
     * Area
     *
     * @var string
     */
    private $area;

    /**
     * Available Date
     *
     * @var DateTime
     */
    private $availableDate;

    /**
     * Bathrooms
     *
     * @var integer
     */
    private $bathrooms;

    /**
     * Bedrooms
     *
     * @var integer
     */
    private $bedrooms;

    /**
     * Brochures
     *
     * @var array
     */
    private $brochures;

    /**
     * Company UUID
     *
     * @var string
     */
    private $companyId;

    /**
     * Company Name
     *
     * @var string
     */
    private $companyName;

    /**
     * Classification (CriteriaType)
     *
     * @var string
     */
    private $classification;

    /**
     * Updated Date
     *
     * @var DateTime
     */
    private $updatedDate;

    /**
     * Photos Text
     *
     * @var array
     */
    private $photosText;

    /**
     * EPC Documents
     *
     * @var array
     */
    private $epcDocuments;

    /**
     * EPC Images
     *
     * @var array
     */
    private $epcImages;

    /**
     * List of Features
     *
     * @var array
     */
    private $features;

    /**
     * Featured
     *
     * @var boolean
     */
    private $featured;

    /**
     * Featured Date
     *
     * @var DateTime
     */
    private $featuredDate;

    /**
     * Fees Text
     *
     * @var string
     */
    private $feesText;

    /**
     * Fees URL
     *
     * @var string
     */
    private $feesUrl;

    /**
     * Floor Plans
     *
     * @var array
     */
    private $floorPlans;

    /**
     * Floors
     *
     * @var integer
     */
    private $floors;

    /**
     * Furnished
     *
     * @var string
     */
    private $furnished;

    /**
     * Ground Rent
     *
     * @var string
     */
    private $groundRent;

    /**
     * HIP Documents
     *
     * @var array
     */
    private $hipDocuments;

    /**
     * Created Date (InsertedDate)
     *
     * @var DateTime
     */
    private $createdDate;

    /**
     * keywords
     *
     * @var string
     */
    private $keywords;

    /**
     * Marketing Description
     *
     * @var string
     */
    private $description;

    /**
     * New Home
     *
     * @var string
     */
    private $newHome;

    /**
     * officeEmail
     *
     * @var string
     */
    private $officeEmail;

    /**
     * Office ID
     *
     * @var string
     */
    private $officeId;

    /**
     * Office Manager
     *
     * @var string
     */
    private $officeManager;

    /**
     * Office Name
     *
     * @var string
     */
    private $officeName;

    /**
     * Office Phone
     *
     * @var string
     */
    private $officePhone;

    /**
     * Office URL
     *
     * @var string
     */
    private $officeUrl;

    /**
     * Outside Space
     *
     * @var string
     */
    private $outsideSpace;

    /**
     * Parking
     *
     * @var string
     */
    private $parking;

    /**
     * Photos
     *
     * @var array
     */
    private $photos;

    /**
     * Postcode 1 (Outward Code)
     *
     * @var string
     */
    private $postcodeOut;

    /**
     * Postcode 2 (Inward Code)
     *
     * @var string
     */
    private $postcodeIn;

    /**
     * Postcode
     *
     * @var string
     */
    private $postcode;

    /**
     * Price Qualifier
     *
     * @var string
     */
    private $priceQualifier;

    /**
     * Display Price
     *
     * @var string
     */
    private $priceDisplay;

    /**
     * Internal Primary Key UUID (PropertyPK)
     *
     * @var string
     */
    private $primaryKey;

    /**
     * Type of Property (PropertyType)
     *
     * @var string
     */
    private $type;

    /**
     * Receptions
     *
     * @var string
     */
    private $receptions;

    /**
     * Rental Period
     *
     * @var string
     */
    private $rentPeriod;

    /**
     * Marketing Description (Rich Text Format)
     *
     * @var string
     */
    private $descriptionRtf;

    /**
     * Selling State
     *
     * @var string
     */
    private $sellingState;

    /**
     * Service Charge
     *
     * @var string
     */
    private $serviceCharge;

    /**
     * Service Provided
     *
     * @var string
     */
    private $serviceProvided;

    /**
     * Property Title
     *
     * @var string
     */
    private $title;

    /**
     * Summary Description
     *
     * @var string
     */
    private $summary;

    /**
     * Tenure
     *
     * @var string
     */
    private $tenure;

    /**
     * Tenure Type
     *
     * @var string
     */
    private $tenureType;

    /**
     * Town
     *
     * @var string
     */
    private $town;

    /**
     * Category
     *
     * @var string
     */
    private $category;

    /**
     * Video Links
     *
     * @var array
     */
    private $videos;

    /**
     * Video Labels
     *
     * @var array
     */
    private $videosText;

    /**
     * Property Identifier (WebID)
     *
     * @var string
     */
    private $id;

    /**
     * WebLink URLs
     *
     * @var array
     */
    private $urls;

    /**
     * Status (WebStatus)
     *
     * @var string
     */
    private $status;

    /**
     * Latitude
     *
     * @var float
     */
    private $latitude;

    /**
     * Longitude
     *
     * @var float
     */
    private $longitude;

    /**
     * Creating a Property
     *
     * @param array $params Property parameters
     *
     * @return self
     */
    public function __construct(array $params = [])
    {
        if ( ! empty($params)) $this->setParameters($params);
    }

    /**
     * Parse Response to Property
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
     * Get Property Parameters
     *
     * @return array
     */
    public function getParameters()
    {
        return Transform::fromProperty($this);
    }

    /**
     * Set Property Parameters
     *
     * @param array $row Property data.
     *
     * @return string
     */
    private function setParameters(array $row)
    {
        Transform::toProperty($this, $row);
    }

    /**
     * Get Address
     *
     * @return array
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Get Address Line
     *
     * @param string $glue Delimiter for address line.
     *
     * @return array
     */
    public function getAddressLine($glue = ', ')
    {
        return implode($glue, $this->getAddress());
    }

    /**
     * Get Price
     *
     * @return integer
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Get Price Base
     *
     * @return integer
     */
    public function getPriceBase()
    {
        return $this->priceBase;
    }

    /**
     * Get Area
     *
     * @return string
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * Get Available Date
     *
     * @param string|null $format DateTime format.
     * @param string|null $default Return, if not DateTime.
     *
     * @return DateTime|string
     */
    public function getAvailableDate($format = null, $default = null)
    {
        $dt = $this->availableDate;

        if ( ! ($dt instanceof DateTime)) {
            return $default;
        }

        return is_null($format) ? $dt : $dt->format($format);
    }

    /**
     * Get Bathrooms
     *
     * @return integer
     */
    public function getBathrooms()
    {
        return $this->bathrooms;
    }

    /**
     * Get Bedrooms
     *
     * @return integer
     */
    public function getBedrooms()
    {
        return $this->bedrooms;
    }

    /**
     * Get Brochures
     *
     * @return array
     */
    public function getBrochures()
    {
        return $this->brochures;
    }

    /**
     * Get Company UUID
     *
     * @return string
     */
    public function getCompanyId()
    {
        return $this->companyId;
    }

    /**
     * Get Company Name
     *
     * @return string
     */
    public function getCompanyName()
    {
        return $this->companyName;
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
     * Is Classification
     *
     * @return boolean
     */
    public function classificationOf($type)
    {
        return $this->getClassification() === $type;
    }

    /**
     * Is Commercial Property Type
     *
     * @return boolean
     */
    public function isCommerical()
    {
        return $this->classificationOf('Commercial Property');
    }

    /**
     * Get Updated Date
     *
     * @param string|null $format DateTime format.
     * @param string|null $default Return, if not DateTime.
     *
     * @return DateTime
     */
    public function getUpdatedDate($format = null, $default = null)
    {
        $dt = $this->updatedDate;

        if ( ! ($dt instanceof DateTime)) {
            return $default;
        }

        return is_null($format) ? $dt : $dt->format($format);
    }

    /**
     * Get EPC Documents
     *
     * @return array
     */
    public function getEpcDocuments()
    {
        return $this->epcDocuments;
    }

    /**
     * Get EPC Images
     *
     * @return array
     */
    public function getEpcImages()
    {
        return $this->epcImages;
    }

    /**
     * Get Feature
     *
     * @param integer $i Index position.
     *
     * @return string|null
     */
    public function getFeature($i = 0)
    {
        return isset($this->features[$i]) ? $this->features[$i] : null;
    }

    /**
     * Get List of Features
     *
     * @return array
     */
    public function getFeatures()
    {
        return $this->features;
    }

    /**
     * Get Featured
     *
     * @return boolean
     */
    public function getFeatured()
    {
        return $this->featured;
    }

    /**
     * Is Featured
     *
     * @return boolean
     */
    public function isFeatured()
    {
        return $this->getFeatured() ? true : false;
    }

    /**
     * Get Featured Date
     *
     * @param string|null $format DateTime format.
     * @param string|null $default Return, if not DateTime.
     *
     * @return DateTime
     */
    public function getFeaturedDate($format = null, $default = null)
    {
        $dt = $this->featuredDate;

        if ( ! ($dt instanceof DateTime)) {
            return $default;
        }

        return is_null($format) ? $dt : $dt->format($format);
    }

    /**
     * Get Fees Text
     *
     * @return string
     */
    public function getFeesText()
    {
        return $this->feesText;
    }

    /**
     * Get Fees URL
     *
     * @return string
     */
    public function getFeesUrl()
    {
        return $this->feesUrl;
    }

    /**
     * Get Fees
     *
     * @return array
     */
    public function getFees()
    {
        return [
            'url' => $this->getFeesUrl(),
            'text' => $this->getFeesText()
        ];
    }

    /**
     * Get Floor Plans
     *
     * @return array
     */
    public function getFloorPlans()
    {
        return $this->floorPlans;
    }

    /**
     * Get Floors
     *
     * @return integer
     */
    public function getFloors()
    {
        return $this->floors;
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
     * Get Ground Rent
     *
     * @return string
     */
    public function getGroundRent()
    {
        return $this->groundRent;
    }

    /**
     * Get HIP Documents
     *
     * @return array
     */
    public function getHipDocuments()
    {
        return $this->hipDocuments;
    }

    /**
     * Get Creation Date
     *
     * @return DateTime
     */
    public function getCreatedDate($format = null, $default = null)
    {
        $dt = $this->createdDate;

        if ( ! ($dt instanceof DateTime)) {
            return $default;
        }

        return is_null($format) ? $dt : $dt->format($format);
    }

    /**
     * Get keywords
     *
     * @return string
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * Get Marketing Description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get New Home
     *
     * @return string
     */
    public function getNewHome()
    {
        return $this->newHome;
    }

    /**
     * Is New Home
     *
     * @return boolean
     */
    public function isNewHome()
    {
        return $this->getNewHome() === 'Y';
    }

    /**
     * Get officeEmail
     *
     * @return string
     */
    public function getOfficeEmail()
    {
        return $this->officeEmail;
    }

    /**
     * Get Office ID
     *
     * @return string
     */
    public function getOfficeId()
    {
        return $this->officeId;
    }

    /**
     * Get Office Manager
     *
     * @return string
     */
    public function getOfficeManager()
    {
        return $this->officeManager;
    }

    /**
     * Get Office Name
     *
     * @return string
     */
    public function getOfficeName()
    {
        return $this->officeName;
    }

    /**
     * Get Office Phone
     *
     * @return string
     */
    public function getOfficePhone()
    {
        return $this->officePhone;
    }

    /**
     * Get Office URL
     *
     * @return string
     */
    public function getOfficeUrl()
    {
        return $this->officeUrl;
    }

    /**
     * Get Office
     *
     * @return array
     */
    public function getOffice()
    {
        return [
            'id' => $this->getOfficeId(),
            'manager' => $this->getOfficeManager(),
            'email' => $this->getOfficeEmail(),
            'name' => $this->getOfficeName(),
            'phone' => $this->getOfficePhone(),
            'url' => $this->getOfficeUrl()
        ];
    }

    /**
     * Get Outside Space
     *
     * @return string
     */
    public function getOutsideSpace()
    {
        return $this->outsideSpace;
    }

    /**
     * Get Parking
     *
     * @return string
     */
    public function getParking()
    {
        return $this->parking;
    }

    /**
     * Get Photos
     *
     * @return array
     */
    public function getPhotos()
    {
        return $this->photos;
    }

    /**
     * Get Photos Text
     *
     * @return array
     */
    public function getPhotosText()
    {
        return $this->photosText;
    }

    /**
     * Get Photo
     *
     * @param integer $i Index position.
     *
     * @return array|null
     */
    public function getPhoto($i = 0)
    {
        return isset($this->photos[$i]) ? $this->photos[$i] : null;
    }

    /**
     * Get Photo URL
     *
     * @param integer $i Index position.
     *
     * @return string|null
     */
    public function getPhotoUrl($i = 0)
    {
        return isset($this->photos[$i]['url']) ? $this->photos[$i]['url'] : null;
    }

    /**
     * Get Text
     *
     * @param integer $i Index position.
     *
     * @return string|null
     */
    public function getPhotoText($i = 0)
    {
        return isset($this->photos[$i]['text']) ? $this->photos[$i]['text'] : null;
    }

    /**
     * Get Postcode 1 (Outward Code)
     *
     * @return string
     */
    public function getPostcodeOut()
    {
        return $this->postcodeOut;
    }

    /**
     * Get Postcode 2 (Inward Code)
     *
     * @return string
     */
    public function getPostcodeIn()
    {
        return $this->postcodeIn;
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
     * Get Price Qualifier
     *
     * @return string
     */
    public function getPriceQualifier()
    {
        return $this->priceQualifier;
    }

    /**
     * Get Display Price
     *
     * @return string
     */
    public function getPriceDisplay()
    {
        return $this->priceDisplay;
    }

    /**
     * Get Internal UUID (PropertyPK)
     *
     * @return string
     */
    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }

    /**
     * Get Property Type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get Receptions
     *
     * @return integer
     */
    public function getReceptions()
    {
        return $this->receptions;
    }

    /**
     * Get Rental Period
     *
     * @return string
     */
    public function getRentPeriod()
    {
        return $this->rentPeriod;
    }

    /**
     * Get Marketing Description (Rich Text Format)
     *
     * @return string
     */
    public function getDescriptionRtf()
    {
        return $this->descriptionRtf;
    }

    /**
     * Get Selling State
     *
     * @return string
     */
    public function getSellingState()
    {
        return $this->sellingState;
    }

    /**
     * Get Service Charge
     *
     * @return string
     */
    public function getServiceCharge()
    {
        return $this->serviceCharge;
    }

    /**
     * Get Service Provided
     *
     * @return string
     */
    public function getServiceProvided()
    {
        return $this->serviceProvided;
    }

    /**
     * Get Property Title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Get Summary Description
     *
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * Get Tenure
     *
     * @return string
     */
    public function getTenure()
    {
        return $this->tenure;
    }

    /**
     * Get Tenure Type
     *
     * @return string
     */
    public function getTenureType()
    {
        return $this->tenureType;
    }

    /**
     * Get Town
     *
     * @return string
     */
    public function getTown()
    {
        return $this->town;
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
     * Is Category Matching
     *
     * @param string $category
     *
     * @return boolean
     */
    public function categoryOf($category)
    {
        return $this->getCategory() === $category;
    }

    /**
     * Is Sales
     *
     * @return boolean
     */
    public function isSales()
    {
        return $this->categoryOf(self::CATEGORY_SALES);
    }

    /**
     * Is Lettings
     *
     * @return boolean
     */
    public function isLettings()
    {
        return $this->categoryOf(self::CATEGORY_LETTINGS);
    }

    /**
     * Get Videos
     *
     * @return array
     */
    public function getVideos()
    {
        $videos = [];

        if ($this->videos) {
            foreach ($this->videos as $i => $link) {
                $videos[] = [
                    'url' => $link,
                    'text' => isset($this->videosText[$i]) ? $this->videosText[$i] : null
                ];
            }
        }

        return $videos;
    }

    /**
     * Get Property Identifier (WebID)
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get WebLink URLs
     *
     * @return array
     */
    public function getUrls()
    {
        return $this->urls;
    }

    /**
     * Get Status (WebStatus)
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Check Status (WebStatus)
     *
     * @return boolean
     */
    public function statusOf($status)
    {
        return $this->getStatus() === $status;
    }

    /**
     * Get Latitude
     *
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Get Longitude
     *
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Get Coordinates
     *
     * @return array
     */
    public function getCordinates()
    {
        return [
            'lat' => $this->getLongitude(),
            'lon' => $this->getLatitude()
        ];
    }
}