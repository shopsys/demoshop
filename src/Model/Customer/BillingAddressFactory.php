<?php

declare(strict_types=1);

namespace App\Model\Customer;

use Shopsys\FrameworkBundle\Model\Customer\BillingAddress as BaseBillingAddress;
use Shopsys\FrameworkBundle\Model\Customer\BillingAddressData as BaseBillingAddressData;
use Shopsys\FrameworkBundle\Model\Customer\BillingAddressFactoryInterface;

class BillingAddressFactory implements BillingAddressFactoryInterface
{
    /**
     * @param \App\Model\Customer\BillingAddressData $data
     * @return \App\Model\Customer\BillingAddress
     */
    public function create(BaseBillingAddressData $data): BaseBillingAddress
    {
        return new BillingAddress($data);
    }
}
