<?php

namespace Shopsys\ShopBundle\Model\Customer;

use Shopsys\FrameworkBundle\Model\Customer\BillingAddress;
use Shopsys\FrameworkBundle\Model\Customer\CustomerFacade as BaseCustomerFacade;

class CustomerFacade extends BaseCustomerFacade
{
    /**
     * @param \Shopsys\FrameworkBundle\Model\Customer\BillingAddress $billingAddress
     * @param int $domainId
     * @return \Shopsys\ShopBundle\Model\Customer\User[]
     */
    public function getUsersByBillingAddressAndDomain(BillingAddress $billingAddress, int $domainId)
    {
        return $this->userRepository->getUsersByBillingAddressAndDomain($billingAddress, $domainId);
    }
}
