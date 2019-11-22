<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Model\Customer;

use Shopsys\FrameworkBundle\Model\Customer\BillingAddressData;
use Shopsys\FrameworkBundle\Model\Customer\CustomerData as BaseCustomerData;
use Shopsys\FrameworkBundle\Model\Customer\DeliveryAddressData;
use Shopsys\FrameworkBundle\Model\Customer\UserData;

/**
 * @property \Shopsys\ShopBundle\Model\Customer\UserData $userData
 * @property \Shopsys\ShopBundle\Model\Customer\BillingAddressData $billingAddressData
 */
class CustomerData extends BaseCustomerData
{
    /**
     * @var \Shopsys\ShopBundle\Model\Customer\UserData[]|null
     */
    public $companyUsersData;

    /**
     * @param \Shopsys\ShopBundle\Model\Customer\BillingAddressData $billingAddressData
     * @param \Shopsys\FrameworkBundle\Model\Customer\DeliveryAddressData $deliveryAddressData
     * @param \Shopsys\ShopBundle\Model\Customer\UserData $userData
     */
    public function __construct(
        BillingAddressData $billingAddressData,
        DeliveryAddressData $deliveryAddressData,
        UserData $userData
    ) {
        $this->companyUsersData = [];
        parent::__construct($billingAddressData, $deliveryAddressData, $userData);
    }
}
