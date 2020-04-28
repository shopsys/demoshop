<?php

declare(strict_types=1);

namespace App\Model\Customer;

use Shopsys\FrameworkBundle\Model\Customer\BillingAddressData;
use Shopsys\FrameworkBundle\Model\Customer\CustomerData as BaseCustomerData;
use Shopsys\FrameworkBundle\Model\Customer\DeliveryAddressData;
use Shopsys\FrameworkBundle\Model\Customer\UserData;

/**
 * @property \App\Model\Customer\UserData $userData
 * @property \App\Model\Customer\BillingAddressData $billingAddressData
 */
class CustomerData extends BaseCustomerData
{
    /**
     * @var \App\Model\Customer\UserData[]|null
     */
    public $companyUsersData;

    /**
     * @param \App\Model\Customer\BillingAddressData $billingAddressData
     * @param \Shopsys\FrameworkBundle\Model\Customer\DeliveryAddressData $deliveryAddressData
     * @param \App\Model\Customer\UserData $userData
     */
    public function __construct(
        BillingAddressData $billingAddressData,
        DeliveryAddressData $deliveryAddressData,
        UserData $userData
    ) {
        $this->companyUsersData = [];
        parent::__construct($billingAddressData, $deliveryAddressData, $userData);
    }
}
