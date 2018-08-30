<?php

namespace Shopsys\ShopBundle\Model\Customer;

use Shopsys\FrameworkBundle\Model\Customer\BillingAddress as BaseBillingAddress;
use Shopsys\FrameworkBundle\Model\Customer\DeliveryAddress as BaseDeliveryAddress;
use Shopsys\FrameworkBundle\Model\Customer\User as BaseUser;
use Shopsys\FrameworkBundle\Model\Customer\UserData as BaseUserData;
use Shopsys\FrameworkBundle\Model\Customer\UserFactoryInterface;

class UserFactory implements UserFactoryInterface
{
    /**
     * @param \Shopsys\ShopBundle\Model\Customer\UserData $userData
     * @param \Shopsys\ShopBundle\Model\Customer\BillingAddress $billingAddress
     * @param \Shopsys\FrameworkBundle\Model\Customer\DeliveryAddress|null $deliveryAddress
     * @return \Shopsys\ShopBundle\Model\Customer\User
     */
    public function create(
        BaseUserData $userData,
        BaseBillingAddress $billingAddress,
        ?BaseDeliveryAddress $deliveryAddress
    ): BaseUser {
        return new User($userData, $billingAddress, $deliveryAddress);
    }
}
