<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Model\Administrator;

use Doctrine\ORM\QueryBuilder;
use Shopsys\FrameworkBundle\Model\Administrator\AdministratorRepository as BaseAdministratorRepository;

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
