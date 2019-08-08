<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Model\Customer;

use Doctrine\ORM\Mapping as ORM;
use Shopsys\FrameworkBundle\Model\Customer\BillingAddress as BaseBillingAddress;
use Shopsys\FrameworkBundle\Model\Customer\BillingAddressData as BaseBillingAddressData;

/**
 * @ORM\Table(name="billing_addresses")
 * @ORM\Entity
 */
class BillingAddress extends BaseBillingAddress
{
    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $isCompanyWithMultipleUsers;

    /**
     * @param \Shopsys\ShopBundle\Model\Customer\BillingAddressData $billingAddressData
     */
    public function __construct(BaseBillingAddressData $billingAddressData)
    {
        $this->isCompanyWithMultipleUsers = $billingAddressData->isCompanyWithMultipleUsers;
        parent::__construct($billingAddressData);
    }

    /**
     * @param \Shopsys\ShopBundle\Model\Customer\BillingAddressData $billingAddressData
     */
    public function edit(BaseBillingAddressData $billingAddressData)
    {
        $this->isCompanyWithMultipleUsers = $billingAddressData->isCompanyWithMultipleUsers;
        parent::__construct($billingAddressData);
    }

    /**
     * @return bool
     */
    public function isCompanyWithMultipleUsers()
    {
        return $this->isCompanyWithMultipleUsers;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
