<?php

namespace Shopsys\ShopBundle\Model\Customer;

use Shopsys\FrameworkBundle\Model\Customer\BillingAddressData;
use Shopsys\FrameworkBundle\Model\Customer\CustomerData as BaseCustomerData;
use Shopsys\FrameworkBundle\Model\Customer\DeliveryAddressData;
use Shopsys\FrameworkBundle\Model\Customer\UserData;

class CustomerData extends BaseCustomerData
{
    /**
     * @var \Shopsys\FrameworkBundle\Model\Customer\UserData[]|null
     */
    public $companyUsersData;

    public function __construct(
        BillingAddressData $billingAddressData,
        DeliveryAddressData $deliveryAddressData,
        UserData $userData
    ) {
        $this->companyUsersData = [];
        parent::__construct($billingAddressData, $deliveryAddressData, $userData);
    }
}
