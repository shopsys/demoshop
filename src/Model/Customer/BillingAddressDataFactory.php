<?php

declare(strict_types=1);

namespace App\Model\Customer;

use Shopsys\FrameworkBundle\Model\Customer\BillingAddress as BaseBillingAddress;
use Shopsys\FrameworkBundle\Model\Customer\BillingAddressData as BaseBillingAddressData;
use Shopsys\FrameworkBundle\Model\Customer\BillingAddressDataFactory as BaseBillingAddressDataFactory;

class BillingAddressDataFactory extends BaseBillingAddressDataFactory
{
    /**
     * @return \App\Model\Customer\BillingAddressData
     */
    public function create(): BaseBillingAddressData
    {
        return new BillingAddressData();
    }

    /**
     * @param \App\Model\Customer\BillingAddress $billingAddress
     * @return \App\Model\Customer\BillingAddressData
     */
    public function createFromBillingAddress(BaseBillingAddress $billingAddress): BaseBillingAddressData
    {
        $billingAddressData = new BillingAddressData();
        $this->fillFromBillingAddress($billingAddressData, $billingAddress);

        return $billingAddressData;
    }

    /**
     * @param \App\Model\Customer\BillingAddressData $billingAddressData
     * @param \App\Model\Customer\BillingAddress $billingAddress
     */
    protected function fillFromBillingAddress(BaseBillingAddressData $billingAddressData, BaseBillingAddress $billingAddress)
    {
        $billingAddressData->isCompanyWithMultipleUsers = $billingAddress->isCompanyWithMultipleUsers();
        parent::fillFromBillingAddress($billingAddressData, $billingAddress);
    }
}
