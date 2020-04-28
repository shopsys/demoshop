<?php

declare(strict_types=1);

namespace App\Model\Customer;

use Shopsys\FrameworkBundle\Model\Customer\CustomerData as BaseCustomerData;
use Shopsys\FrameworkBundle\Model\Customer\CustomerDataFactory as BaseCustomerDataFactory;
use Shopsys\FrameworkBundle\Model\Customer\User as BaseUser;

/**
 * @method \App\Model\Customer\CustomerData createAmendedByOrder(\App\Model\Customer\User $user, \App\Model\Order\Order $order)
 */
class CustomerDataFactory extends BaseCustomerDataFactory
{
    /**
     * @return \App\Model\Customer\CustomerData
     */
    public function create(): BaseCustomerData
    {
        return new CustomerData(
            $this->billingAddressDataFactory->create(),
            $this->deliveryAddressDataFactory->create(),
            $this->userDataFactory->create()
        );
    }

    /**
     * @param \App\Model\Customer\User $user
     * @return \App\Model\Customer\CustomerData
     */
    public function createFromUser(BaseUser $user): BaseCustomerData
    {
        $customerData = new CustomerData(
            $this->billingAddressDataFactory->createFromBillingAddress($user->getBillingAddress()),
            $this->getDeliveryAddressDataFromUser($user),
            $this->userDataFactory->createFromUser($user)
        );

        return $customerData;
    }
}
