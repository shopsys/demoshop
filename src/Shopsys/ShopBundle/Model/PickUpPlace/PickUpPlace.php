<?php

namespace Shopsys\ShopBundle\Model\PickUpPlace;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(
 *     name="pick_up_place",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="place_unique", columns={"transport_type", "place_id"})}
 *     )
 * @ORM\Entity
 */
class PickUpPlace
{
    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $transportType;

    /**
     * ID from pickup system
     * @var int
     * @ORM\Column(type="integer")
     */
    private $placeId;

    /**
     * @var string
     * @ORM\Column(type="string", length=10)
     */
    private $countryCode;

    /**
     * @var string
     * @ORM\Column(type="string", length=250)
     */
    private $city;

    /**
     * @var string
     * @ORM\Column(type="string", length=250)
     */
    private $street;

    /**
     * @var string
     * @ORM\Column(type="string", length=30)
     */
    private $postCode;

    /**
     * @var float
     * @ORM\Column(type="float")
     */
    private $gpsLatitude;

    /**
     * @var float
     * @ORM\Column(type="float")
     */
    private $gpsLongitude;

    /**
     * @var string
     * @ORM\Column(type="string", length=250)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $pending;

    /**
     * @param \Shopsys\ShopBundle\Model\PickUpPlace\PickUpPlaceData $pickUpPlaceData
     */
    public function __construct(PickUpPlaceData $pickUpPlaceData)
    {
        $this->placeId = $pickUpPlaceData->placeId;
        $this->transportType = $pickUpPlaceData->transportType;
        $this->countryCode = $pickUpPlaceData->countryCode;
        $this->city = $pickUpPlaceData->city;
        $this->street = $pickUpPlaceData->street;
        $this->postCode = $pickUpPlaceData->postCode;
        $this->gpsLatitude = $pickUpPlaceData->gpsLatitude;
        $this->gpsLongitude = $pickUpPlaceData->gpsLongitude;
        $this->name = $pickUpPlaceData->name;
        $this->description = $pickUpPlaceData->description;
        $this->active = true;
        $this->pending = false;
    }

    /**
     * @param \Shopsys\ShopBundle\Model\PickUpPlace\PickUpPlaceData $pickUpPlaceData
     */
    public function edit(PickUpPlaceData $pickUpPlaceData)
    {
        $this->placeId = $pickUpPlaceData->placeId;
        $this->transportType = $pickUpPlaceData->transportType;
        $this->countryCode = $pickUpPlaceData->countryCode;
        $this->city = $pickUpPlaceData->city;
        $this->street = $pickUpPlaceData->street;
        $this->postCode = $pickUpPlaceData->postCode;
        $this->gpsLatitude = $pickUpPlaceData->gpsLatitude;
        $this->gpsLongitude = $pickUpPlaceData->gpsLongitude;
        $this->name = $pickUpPlaceData->name;
        $this->description = $pickUpPlaceData->description;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTransportType()
    {
        return $this->transportType;
    }

    /**
     * @return int
     */
    public function getPlaceId()
    {
        return $this->placeId;
    }

    /**
     * @return string
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @return string
     */
    public function getPostCode()
    {
        return $this->postCode;
    }

    /**
     * @return float
     */
    public function getGpsLatitude()
    {
        return $this->gpsLatitude;
    }

    /**
     * @return float
     */
    public function getGpsLongitude()
    {
        return $this->gpsLongitude;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @return bool
     */
    public function isPending()
    {
        return $this->pending;
    }

    public function markAsPending()
    {
        $this->pending = true;
    }

    public function markAsNotPending()
    {
        $this->pending = false;
    }

    public function markAsInactive()
    {
        $this->active = false;
        $this->pending = false;
    }

    /**
     * @return string
     */
    public function getFullAddress()
    {
        return sprintf(
            '%s, %s, %s',
            $this->getStreet(),
            $this->getCity(),
            $this->getPostCode()
        );
    }
}
