<?php

declare(strict_types=1);

namespace App\Model\Customer\User;

use Doctrine\ORM\Mapping as ORM;
use Shopsys\FrameworkBundle\Model\Customer\User\CustomerUser as BaseCustomerUser;
use Shopsys\FrameworkBundle\Model\Customer\User\CustomerUserData as BaseCustomerUserData;

/**
 * @ORM\Table(
 *     name="customer_users",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="email_domain", columns={"email", "domain_id"})
 *     },
 *     indexes={
 *         @ORM\Index(columns={"email"})
 *     }
 * )
 * @ORM\Entity
 * @method \App\Model\Customer\BillingAddress getBillingAddress()
 */
class CustomerUser extends BaseCustomerUser
{
    /**
     * @var int
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    protected $discount;

    /**
     * @param \App\Model\Customer\User\CustomerUserData $customerUserData
     */
    public function __construct(
        BaseCustomerUserData $customerUserData
    ) {
        parent::__construct($customerUserData);
        $this->discount = $customerUserData->discount;
    }

    /**
     * @param \App\Model\Customer\User\CustomerUserData $customerUserData
     */
    public function edit(BaseCustomerUserData $customerUserData)
    {
        parent::edit($customerUserData);
        $this->discount = $customerUserData->discount;
    }

    /**
     * @return int
     */
    public function getDiscount(): int
    {
        return $this->discount;
    }
}
