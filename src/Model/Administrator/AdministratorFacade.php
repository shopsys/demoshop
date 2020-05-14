<?php

declare(strict_types=1);

namespace App\Model\Administrator;

use Shopsys\FrameworkBundle\Model\Administrator\Administrator as BaseAdministrator;
use Shopsys\FrameworkBundle\Model\Administrator\AdministratorFacade as BaseAdministratorFacade;

/**
 * @property \App\Model\Administrator\AdministratorRepository $administratorRepository
 * @method __construct(\Doctrine\ORM\EntityManagerInterface $em, \App\Model\Administrator\AdministratorRepository $administratorRepository, \Shopsys\FrameworkBundle\Model\Administrator\AdministratorFactoryInterface $administratorFactory, \Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface $encoderFactory, \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $tokenStorage)
 * @method \App\Model\Administrator\Administrator create(\Shopsys\FrameworkBundle\Model\Administrator\AdministratorData $administratorData)
 * @method \App\Model\Administrator\Administrator edit(int $administratorId, \Shopsys\FrameworkBundle\Model\Administrator\AdministratorData $administratorData)
 * @method checkUsername(\App\Model\Administrator\Administrator $administrator, string $username)
 * @method setPassword(\App\Model\Administrator\Administrator $administrator, string $password)
 * @method \App\Model\Administrator\Administrator getById(int $administratorId)
 * @method __construct(\Doctrine\ORM\EntityManagerInterface $em, \App\Model\Administrator\AdministratorRepository $administratorRepository, \Shopsys\FrameworkBundle\Model\Administrator\AdministratorFactoryInterface $administratorFactory, \Shopsys\FrameworkBundle\Model\Administrator\Role\AdministratorRoleFacade $administratorRoleFacade, \Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface $encoderFactory, \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $tokenStorage)
 * @method setRolesChangedNow(\App\Model\Administrator\Administrator $administrator)
 */
class AdministratorFacade extends BaseAdministratorFacade
{
    /**
     * @param \App\Model\Administrator\Administrator $administrator
     */
    protected function checkForDelete(BaseAdministrator $administrator)
    {
        parent::checkForDelete($administrator);

        if ($administrator->isDefaultAdmin()) {
            throw new \App\Model\Administrator\Exception\DeletingDefaultAdminException();
        }
    }
}
