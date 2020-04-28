<?php

declare(strict_types=1);

namespace App\Model\Administrator;

use Doctrine\ORM\QueryBuilder;
use Shopsys\FrameworkBundle\Model\Administrator\AdministratorRepository as BaseAdministratorRepository;

/**
 * @method \App\Model\Administrator\Administrator|null findById(int $administratorId)
 * @method \App\Model\Administrator\Administrator getById(int $administratorId)
 * @method \App\Model\Administrator\Administrator getByValidMultidomainLoginToken(string $multidomainLoginToken)
 * @method \App\Model\Administrator\Administrator|null findByUserName(string $administratorUserName)
 * @method \App\Model\Administrator\Administrator getByUserName(string $administratorUserName)
 * @method \App\Model\Administrator\Administrator[] getAllSuperadmins()
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
