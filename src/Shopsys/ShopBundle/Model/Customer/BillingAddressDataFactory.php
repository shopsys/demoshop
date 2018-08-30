<?php

namespace Shopsys\ShopBundle\Model\Customer;

use Shopsys\FrameworkBundle\Model\Customer\BillingAddress as BaseBillingAddress;
use Shopsys\FrameworkBundle\Model\Customer\BillingAddressData as BaseBillingAddressData;
use Shopsys\FrameworkBundle\Model\Customer\BillingAddressDataFactory as BaseBillingAddressDataFactory;

class BillingAddressDataFactory extends BaseBillingAddressDataFactory
{
    /**
     * @return \Shopsys\ShopBundle\Model\Customer\BillingAddressData
     */
    public function create(): BaseBillingAddressData
    {
        return new BillingAddressData();
    }

    /**
     * @param \Shopsys\ShopBundle\Model\Customer\BillingAddress $billingAddress
     * @return \Shopsys\ShopBundle\Model\Customer\BillingAddressData
     */
    public function createFromBillingAddress(BaseBillingAddress $billingAddress): BaseBillingAddressData
    {
        $billingAddressData = new BillingAddressData();
        $this->fillFromBillingAddress($billingAddressData, $billingAddress);

        return $billingAddressData;
    }

    /**
     * @param \Shopsys\ShopBundle\Model\Customer\BillingAddressData $billingAddressData
     * @param \Shopsys\ShopBundle\Model\Customer\BillingAddress $billingAddress
     */
    protected function fillFromBillingAddress(BaseBillingAddressData $billingAddressData, BaseBillingAddress $billingAddress)
    {
        $billingAddressData->isCompanyWithMultipleUsers = $billingAddress->getisCompanyWithMultipleUsers();
        parent::fillFromBillingAddress($billingAddressData, $billingAddress);
    }
}
