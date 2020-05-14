<?php

declare(strict_types=1);

namespace App\Model\Customer\User;

use Shopsys\FrameworkBundle\Model\Customer\User\CustomerUser as BaseCustomerUser;
use Shopsys\FrameworkBundle\Model\Customer\User\CustomerUserUpdateData as BaseCustomerData;
use Shopsys\FrameworkBundle\Model\Customer\User\CustomerUserUpdateDataFactory as BaseCustomerDataFactory;

/**
 * @method \App\Model\Customer\User\CustomerUserUpdateData createAmendedByOrder(\App\Model\Customer\User\CustomerUser $customerUser, \App\Model\Order\Order $order)
 * @method \App\Model\Customer\User\CustomerUserUpdateData createFromCustomerUser(\App\Model\Customer\User\CustomerUser $customerUser)
 * @method \App\Model\Customer\User\CustomerUserUpdateData createAmendedByOrder(\App\Model\Customer\User\CustomerUser $customerUser, \App\Model\Order\Order $order, \Shopsys\FrameworkBundle\Model\Customer\DeliveryAddress|null $deliveryAddress)
 */
class CustomerUserUpdateDataFactory extends BaseCustomerDataFactory
{
    /**
     * @return \App\Model\Customer\User\CustomerUserUpdateData
     */
    public function create(): BaseCustomerData
    {
        return new CustomerUserUpdateData(
            $this->billingAddressDataFactory->create(),
            $this->deliveryAddressDataFactory->create(),
            $this->customerUserDataFactory->create()
        );
    }

    /**
     * @param \App\Model\Customer\User\CustomerUser $customerUser
     * @return \App\Model\Customer\User\CustomerUserUpdateData
     */
    public function createFromUser(BaseCustomerUser $customerUser): BaseCustomerData
    {
        $customerUserUpdateData = new CustomerUserUpdateData(
            $this->billingAddressDataFactory->createFromBillingAddress($customerUser->getBillingAddress()),
            $this->getDeliveryAddressDataFromCustomerUser($customerUser),
            $this->customerUserDataFactory->createFromCustomerUser($customerUser)
        );

        return $customerUserUpdateData;
    }
}
