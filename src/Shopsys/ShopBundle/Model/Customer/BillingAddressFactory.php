<?php

namespace Shopsys\ShopBundle\Model\Customer;

use Shopsys\FrameworkBundle\Model\Customer\BillingAddress as BaseBillingAddress;
use Shopsys\FrameworkBundle\Model\Customer\BillingAddressData as BaseBillingAddressData;
use Shopsys\FrameworkBundle\Model\Customer\BillingAddressFactoryInterface;

class BillingAddressFactory implements BillingAddressFactoryInterface
{

    /**
     * @param \Shopsys\ShopBundle\Model\Customer\BillingAddressData $data
     * @return \Shopsys\ShopBundle\Model\Customer\BillingAddress
     */
    public function create(BaseBillingAddressData $data): BaseBillingAddress
    {
        return new BillingAddress($data);
    }
}
