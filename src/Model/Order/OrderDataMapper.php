<?php

declare(strict_types=1);

namespace App\Model\Order;

use App\Model\Country\CountryFacade;
use App\Model\Transport\Transport;
use Shopsys\FrameworkBundle\Model\Order\FrontOrderData as BaseFrontOrderData;
use Shopsys\FrameworkBundle\Model\Order\OrderDataFactoryInterface;
use Shopsys\FrameworkBundle\Model\Order\OrderDataMapper as BaseOrderDataMapper;

/**
 * @property \App\Model\Order\OrderDataFactory $orderDataFactory
 */
class OrderDataMapper extends BaseOrderDataMapper
{
    /**
     * @var \App\Model\Country\CountryFacade
     */
    protected $countryFacade;

    /**
     * @param \App\Model\Order\OrderDataFactory $orderDataFactory
     * @param \App\Model\Country\CountryFacade $countryFacade
     */
    public function __construct(OrderDataFactoryInterface $orderDataFactory, CountryFacade $countryFacade)
    {
        parent::__construct($orderDataFactory);
        $this->countryFacade = $countryFacade;
    }

    /**
     * @param \App\Model\Order\FrontOrderData $frontOrderData
     * @return \App\Model\Order\OrderData
     */
    public function getOrderDataFromFrontOrderData(BaseFrontOrderData $frontOrderData)
    {
        /** @var \App\Model\Order\OrderData $orderData */
        $orderData = parent::getOrderDataFromFrontOrderData($frontOrderData);

        $orderData->pickUpPlace = $orderData->transport !== null && $orderData->transport->getType() === Transport::TYPE_ZASILKOVNA ?
                $frontOrderData->pickUpPlace : null;

        $isPickUpPlaceTransport = $orderData->transport !== null && $orderData->transport->isPickUpPlaceType();
        if ($isPickUpPlaceTransport && $orderData->pickUpPlace !== null) {
            $this->fillPickUpPlaceDeliveryData($orderData, $frontOrderData);
        }

        return $orderData;
    }

    /**
     * @param \App\Model\Order\OrderData $orderData
     * @param \App\Model\Order\FrontOrderData $frontOrderData
     */
    protected function fillPickUpPlaceDeliveryData(OrderData $orderData, FrontOrderData $frontOrderData)
    {
        $orderData->deliveryFirstName = $frontOrderData->firstName;
        $orderData->deliveryLastName = $frontOrderData->lastName;
        $frontOrderData->deliveryAddressSameAsBillingAddress = false;
        $orderData->deliveryAddressSameAsBillingAddress = $frontOrderData->deliveryAddressSameAsBillingAddress;
        $orderData->deliveryAddressSameAsBillingAddress = false;
        $frontOrderData->deliveryCompanyName = $orderData->pickUpPlace->getName();
        $orderData->deliveryCompanyName = $frontOrderData->deliveryCompanyName;
        $orderData->deliveryTelephone = null;
        $frontOrderData->deliveryStreet = $orderData->pickUpPlace->getStreet();
        $orderData->deliveryStreet = $frontOrderData->deliveryStreet;
        $frontOrderData->deliveryCity = $orderData->pickUpPlace->getCity();
        $orderData->deliveryCity = $frontOrderData->deliveryCity;
        $frontOrderData->deliveryPostcode = $orderData->pickUpPlace->getPostCode();
        $orderData->deliveryPostcode = $frontOrderData->deliveryPostcode;
        $frontOrderData->deliveryCountry = $this->countryFacade->getByCode($orderData->pickUpPlace->getCountryCode());
        $orderData->deliveryCountry = $frontOrderData->deliveryCountry;
    }
}
