<?php

namespace Shopsys\ShopBundle\Model\Customer;

use Doctrine\ORM\Mapping as ORM;
use Shopsys\FrameworkBundle\Model\Customer\BillingAddress as BaseBillingAddress;
use Shopsys\FrameworkBundle\Model\Customer\DeliveryAddress;
use Shopsys\FrameworkBundle\Model\Customer\User as BaseUser;
use Shopsys\FrameworkBundle\Model\Customer\UserData as BaseUserData;

/**
 * @ORM\Table(
 *     name="users",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="email_domain", columns={"email", "domain_id"})
 *     },
 *     indexes={
 *         @ORM\Index(columns={"email"})
 *     }
 * )
 * @ORM\Entity
 */
class User extends BaseUser
{
    /**
     * @var \Shopsys\ShopBundle\Model\Customer\BillingAddress
     * @ORM\ManyToOne(targetEntity="Shopsys\FrameworkBundle\Model\Customer\BillingAddress")
     * @ORM\JoinColumn(name="billing_address_id", referencedColumnName="id", nullable=false)
     */
    protected $billingAddress;

    /**
     * @param \Shopsys\ShopBundle\Model\Customer\UserData $userData
     * @param \Shopsys\ShopBundle\Model\Customer\BillingAddress $billingAddress
     * @param \Shopsys\FrameworkBundle\Model\Customer\DeliveryAddress|null $deliveryAddress
     */
    public function __construct(
        BaseUserData $userData,
        BaseBillingAddress $billingAddress,
        DeliveryAddress $deliveryAddress = null
    ) {
        $this->billingAddress = $billingAddress;
        parent::__construct($userData, $billingAddress, $deliveryAddress);
    }

    /**
     * @return \Shopsys\ShopBundle\Model\Customer\BillingAddress
     */
    public function getBillingAddress()
    {
        return $this->billingAddress;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        if ($this->billingAddress->isCompanyCustomer()) {
            return $this->billingAddress->getCompanyName();
        }

        return $this->lastName . ' ' . $this->firstName;
    }
}
