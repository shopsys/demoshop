<?php

namespace Shopsys\ShopBundle\Model\Customer;

use Doctrine\ORM\Mapping as ORM;
use Shopsys\FrameworkBundle\Model\Customer\BillingAddress as BaseBillingAddress;
use Shopsys\FrameworkBundle\Model\Customer\DeliveryAddress;
use Shopsys\FrameworkBundle\Model\Customer\User as BaseUser;
use Shopsys\FrameworkBundle\Model\Customer\UserData as BaseUserData;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

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
     * @ORM\ManyToOne(targetEntity="Shopsys\ShopBundle\Model\Customer\BillingAddress", cascade={"persist"})
     * @ORM\JoinColumn(name="billing_address_id", referencedColumnName="id", nullable=false)
     */
    protected $billingAddress;

    /**
     * @var int
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    protected $discount;

    /**
     * @param \Shopsys\ShopBundle\Model\Customer\UserData $userData
     * @param \Shopsys\ShopBundle\Model\Customer\BillingAddress $billingAddress
     * @param \Shopsys\FrameworkBundle\Model\Customer\DeliveryAddress|null $deliveryAddress
     * @param \Shopsys\ShopBundle\Model\Customer\User|null $userByEmail
     */
    public function __construct(
        BaseUserData $userData,
        BaseBillingAddress $billingAddress,
        ?DeliveryAddress $deliveryAddress,
        ?self $userByEmail
    ) {
        $this->discount = $userData->discount;
        parent::__construct($userData, $billingAddress, $deliveryAddress, $userByEmail);
    }

    /**
     * @param \Shopsys\ShopBundle\Model\Customer\UserData $userData
     * @param \Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface $encoderFactory
     */
    public function edit(BaseUserData $userData, EncoderFactoryInterface $encoderFactory)
    {
        $this->discount = $userData->discount;
        parent::edit($userData, $encoderFactory);
    }

    /**
     * @return int
     */
    public function getDiscount(): int
    {
        return $this->discount;
    }
}
