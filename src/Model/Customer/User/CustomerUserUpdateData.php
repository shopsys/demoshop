<?php

declare(strict_types=1);

namespace App\Model\Customer\User;

use Shopsys\FrameworkBundle\Model\Customer\BillingAddressData;
use Shopsys\FrameworkBundle\Model\Customer\DeliveryAddressData;
use Shopsys\FrameworkBundle\Model\Customer\User\CustomerUserData;
use Shopsys\FrameworkBundle\Model\Customer\User\CustomerUserUpdateData as BaseCustomerUserUpdateData;

/**
 * @property \App\Model\Customer\User\CustomerUserData $customerUserData
 */
class CustomerUserUpdateData extends BaseCustomerUserUpdateData
{
    /**
     * @param \Shopsys\FrameworkBundle\Model\Customer\BillingAddressData $billingAddressData
     * @param \Shopsys\FrameworkBundle\Model\Customer\DeliveryAddressData $deliveryAddressData
     * @param \App\Model\Customer\User\CustomerUserData $customerUserData
     */
    public function __construct(
        BillingAddressData $billingAddressData,
        DeliveryAddressData $deliveryAddressData,
        CustomerUserData $customerUserData
    ) {
        parent::__construct($billingAddressData, $deliveryAddressData, $customerUserData);
    }
}
