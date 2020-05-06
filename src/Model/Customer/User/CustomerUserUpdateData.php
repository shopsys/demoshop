<?php

declare(strict_types=1);

namespace App\Model\Customer\User;

use Shopsys\FrameworkBundle\Model\Customer\BillingAddressData;
use Shopsys\FrameworkBundle\Model\Customer\DeliveryAddressData;
use Shopsys\FrameworkBundle\Model\Customer\User\CustomerUserData;
use Shopsys\FrameworkBundle\Model\Customer\User\CustomerUserUpdateData as BaseCustomerUserUpdateData;

/**
 * @property \App\Model\Customer\User\CustomerUserData $customerUserData
 * @property \App\Model\Customer\BillingAddressData $billingAddressData
 */
class CustomerUserUpdateData extends BaseCustomerUserUpdateData
{
    /**
     * @var \App\Model\Customer\User\CustomerUserData[]|null
     */
    public $companyUsersData;

    /**
     * @param \App\Model\Customer\BillingAddressData $billingAddressData
     * @param \Shopsys\FrameworkBundle\Model\Customer\DeliveryAddressData $deliveryAddressData
     * @param \App\Model\Customer\User\CustomerUserData $customerUserData
     */
    public function __construct(
        BillingAddressData $billingAddressData,
        DeliveryAddressData $deliveryAddressData,
        CustomerUserData $customerUserData
    ) {
        $this->companyUsersData = [];
        parent::__construct($billingAddressData, $deliveryAddressData, $customerUserData);
    }
}
