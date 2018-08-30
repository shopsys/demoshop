<?php

namespace Shopsys\ShopBundle\Model\Customer;

use Shopsys\FrameworkBundle\Model\Customer\BillingAddress;
use Shopsys\FrameworkBundle\Model\Customer\UserRepository as BaseUserRepository;

class UserRepository extends BaseUserRepository
{
    /**
     * @param \Shopsys\FrameworkBundle\Model\Customer\BillingAddress $billingAddress
     * @param int $domainId
     * @return \Shopsys\ShopBundle\Model\Customer\User[]
     */
    public function getUsersByBillingAddressAndDomain(BillingAddress $billingAddress, int $domainId)
    {
        return $this->em->createQueryBuilder()
            ->select('u')
            ->from(User::class, 'u')
            ->andWhere('u.domainId = :domain')
            ->andWhere('u.billingAddress = :billingAddress')
            ->setParameter(':domain', $domainId)
            ->setParameter(':billingAddress', $billingAddress)
            ->getQuery()->getResult();
    }
}
