<?php

declare(strict_types=1);

namespace App\Model\Customer\User;

use App\Model\Customer\BillingAddress;
use Shopsys\FrameworkBundle\Component\String\DatabaseSearching;
use Shopsys\FrameworkBundle\Form\Admin\QuickSearch\QuickSearchFormData;
use Shopsys\FrameworkBundle\Model\Customer\BillingAddress as BaseBillingAddress;
use Shopsys\FrameworkBundle\Model\Customer\User\CustomerUserRepository as BaseCustomerUserRepository;

/**
 * @method \App\Model\Customer\User\CustomerUser|null findCustomerUserByEmailAndDomain(string $email, int $domainId)
 * @method \App\Model\Customer\User\CustomerUser|null getUserByEmailAndDomain(string $email, int $domainId)
 * @method \App\Model\Customer\User\CustomerUser getCustomerUserById(int $id)
 * @method \App\Model\Customer\User\CustomerUser|null findById(int $id)
 * @method \App\Model\Customer\User\CustomerUser|null findByIdAndLoginToken(int $id, string $loginToken)
 * @method \App\Model\Customer\User\CustomerUser|null getCustomerUserByEmailAndDomain(string $email, int $domainId)
 * @method \App\Model\Customer\User\CustomerUser getOneByUuid(string $uuid)
 */
class CustomerUserRepository extends BaseCustomerUserRepository
{
    /**
     * @param \App\Model\Customer\BillingAddress $billingAddress
     * @param int $domainId
     * @return \App\Model\Customer\User\CustomerUser[]
     */
    public function getUsersByBillingAddressAndDomain(BaseBillingAddress $billingAddress, int $domainId)
    {
        return $this->em->createQueryBuilder()
            ->select('u')
            ->from(CustomerUser::class, 'u')
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
            ->leftJoin(CustomerUser::class, 'u', 'WITH', 'ba.id = u.billingAddress')
            ->andWhere('u.domainId = :domainId')
            ->setParameter(':domainId', $domainId)
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
     * @param \App\Model\Customer\BillingAddress $billingAddress
     * @return array
     */
    public function getAllByBillingAddress(BillingAddress $billingAddress)
    {
        return $this->getCustomerUserRepository()->findBy([
            'billingAddress' => $billingAddress,
        ]);
    }

    /**
     * @param \App\Model\Customer\BillingAddress $billingAddress
     * @return \App\Model\Customer\User\CustomerUser
     */
    public function getUserByBillingAddress(BillingAddress $billingAddress)
    {
        return $this->getCustomerUserRepository()->findOneBy([
            'billingAddress' => $billingAddress,
        ]);
    }
}
