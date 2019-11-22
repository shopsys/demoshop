<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Model\Customer;

use Shopsys\FrameworkBundle\Model\Customer\CustomerData as BaseCustomerData;
use Shopsys\FrameworkBundle\Model\Customer\CustomerDataFactory as BaseCustomerDataFactory;
use Shopsys\FrameworkBundle\Model\Customer\User as BaseUser;

/**
 * @method \Shopsys\ShopBundle\Model\Customer\CustomerData createAmendedByOrder(\Shopsys\ShopBundle\Model\Customer\User $user, \Shopsys\ShopBundle\Model\Order\Order $order)
 */
class CustomerDataFactory extends BaseCustomerDataFactory
{
    /**
     * @return \Shopsys\ShopBundle\Model\Customer\CustomerData
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
     * @param \Shopsys\ShopBundle\Model\Customer\User $user
     * @return \Shopsys\ShopBundle\Model\Customer\CustomerData
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
