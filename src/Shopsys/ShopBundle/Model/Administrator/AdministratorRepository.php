<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Model\Administrator;

use Doctrine\ORM\QueryBuilder;
use Shopsys\FrameworkBundle\Model\Administrator\AdministratorRepository as BaseAdministratorRepository;

/**
 * @method \Shopsys\ShopBundle\Model\Administrator\Administrator|null findById(int $administratorId)
 * @method \Shopsys\ShopBundle\Model\Administrator\Administrator getById(int $administratorId)
 * @method \Shopsys\ShopBundle\Model\Administrator\Administrator getByValidMultidomainLoginToken(string $multidomainLoginToken)
 * @method \Shopsys\ShopBundle\Model\Administrator\Administrator|null findByUserName(string $administratorUserName)
 * @method \Shopsys\ShopBundle\Model\Administrator\Administrator getByUserName(string $administratorUserName)
 * @method \Shopsys\ShopBundle\Model\Administrator\Administrator[] getAllSuperadmins()
 */
class AdministratorRepository extends BaseAdministratorRepository
{
    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getAllListableQueryBuilder(): QueryBuilder
    {
        return $this->getAdministratorRepository()
            ->createQueryBuilder('a')
            ->where('a.superadmin = :isSuperadmin')
            ->setParameter('isSuperadmin', false)
            ->andWhere('a.id != :defaultAdminId')
            ->setParameter('defaultAdminId', Administrator::DEFAULT_ADMIN_ID);
    }
}
