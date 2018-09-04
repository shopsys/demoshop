<?php

namespace Shopsys\ShopBundle\Model\Customer;

use Shopsys\FrameworkBundle\Component\String\DatabaseSearching;
use Shopsys\FrameworkBundle\Form\Admin\QuickSearch\QuickSearchFormData;
use Shopsys\FrameworkBundle\Model\Customer\BillingAddress as BaseBillingAddress;
use Shopsys\FrameworkBundle\Model\Customer\UserRepository as BaseUserRepository;

class UserRepository extends BaseUserRepository
{
    /**
     * @param \Shopsys\ShopBundle\Model\Customer\BillingAddress $billingAddress
     * @param int $domainId
     * @return \Shopsys\ShopBundle\Model\Customer\User[]
     */
    public function getUsersByBillingAddressAndDomain(BaseBillingAddress $billingAddress, int $domainId)
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

    /**
     * @param int $domainId
     * @param \Shopsys\FrameworkBundle\Form\Admin\QuickSearch\QuickSearchFormData $quickSearchData
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getCustomerListQueryBuilderByQuickSearchData(
        $domainId,
        QuickSearchFormData $quickSearchData
    ) {
        $queryBuilder = $this->em->createQueryBuilder()
            ->select('
                ba.id,
                MAX(ba.city) city,
                MAX(ba.street) street,
                MAX(CASE WHEN ba.companyCustomer = true OR ba.isCompanyWithMultipleUsers = true	
                        THEN ba.companyName
                        ELSE CONCAT(u.lastName, \' \', u.firstName)
                    END) AS name')
            ->from(BillingAddress::class, 'ba')
            ->leftJoin(User::class, 'u', 'WITH', 'ba.id = u.billingAddress')
            ->groupBy('ba.id');

        if ($quickSearchData->text !== null && $quickSearchData->text !== '') {
            $queryBuilder
                ->andWhere('
                    (
                        NORMALIZE(u.lastName) LIKE NORMALIZE(:text)
                        OR
                        NORMALIZE(u.email) LIKE NORMALIZE(:text)
                        OR
                        NORMALIZE(ba.companyName) LIKE NORMALIZE(:text)
                    )');
            $querySearchText = DatabaseSearching::getFullTextLikeSearchString($quickSearchData->text);
            $queryBuilder->setParameter('text', $querySearchText);
        }

        return $queryBuilder;
    }

    /**
     * @param \Shopsys\ShopBundle\Model\Customer\BillingAddress $billingAddress
     * @return array
     */
    public function getAllByBillingAddress(BillingAddress $billingAddress)
    {
        return $this->getUserRepository()->findBy([
            'billingAddress' => $billingAddress,
        ]);
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Customer\BillingAddress $billingAddress
     * @return \Shopsys\ShopBundle\Model\Customer\User
     */
    public function getUserByBillingAddress(BillingAddress $billingAddress)
    {
        return $this->getUserRepository()->findOneBy([
            'billingAddress' => $billingAddress,
        ]);
    }
}
