<?php

namespace Shopsys\ShopBundle\Model\Customer;

use Shopsys\FrameworkBundle\Model\Customer\BillingAddressData as BaseBillingAddressData;

class BillingAddressData extends BaseBillingAddressData
{
    /**
     * @var bool
     */
    public $isCompanyWithMultipleUsers;

    public function __construct()
    {
        $this->isCompanyWithMultipleUsers = false;
        parent::__construct();
    }
}
