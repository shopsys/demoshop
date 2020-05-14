<?php

declare(strict_types=1);

namespace App\Model\Customer\User;

use Shopsys\FrameworkBundle\Model\Customer\BillingAddressData as BaseBillingAddressData;
use Shopsys\FrameworkBundle\Model\Customer\DeliveryAddressData;
use Shopsys\FrameworkBundle\Model\Customer\User\CustomerUserData as BaseCustomerUserData;
use Shopsys\FrameworkBundle\Model\Customer\User\CustomerUserUpdateData as BaseCustomerUserUpdateData;
use Shopsys\FrameworkBundle\Model\Customer\User\CustomerUserUpdateDataFactory as BaseCustomerDataFactory;

/**
 * @method \App\Model\Customer\User\CustomerUserUpdateData createFromCustomerUser(\App\Model\Customer\User\CustomerUser $customerUser)
 * @method \App\Model\Customer\User\CustomerUserUpdateData createAmendedByOrder(\App\Model\Customer\User\CustomerUser $customerUser, \App\Model\Order\Order $order, \Shopsys\FrameworkBundle\Model\Customer\DeliveryAddress|null $deliveryAddress)
 * @method \App\Model\Customer\User\CustomerUserUpdateData create()
 */
class CustomerUserUpdateDataFactory extends BaseCustomerDataFactory
{
    /**
     * @param \App\Model\Customer\BillingAddressData $billingAddressData
     * @param \Shopsys\FrameworkBundle\Model\Customer\DeliveryAddressData $deliveryAddressData
     * @param \App\Model\Customer\User\CustomerUserData $customerUserData
     * @return \App\Model\Customer\User\CustomerUserUpdateData
     */
    protected function createInstance(
        BaseBillingAddressData $billingAddressData,
        DeliveryAddressData $deliveryAddressData,
        BaseCustomerUserData $customerUserData
    ): BaseCustomerUserUpdateData {
        return new CustomerUserUpdateData($billingAddressData, $deliveryAddressData, $customerUserData);
    }
}
