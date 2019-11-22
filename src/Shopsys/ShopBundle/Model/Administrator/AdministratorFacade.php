<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Model\Administrator;

use Shopsys\FrameworkBundle\Model\Administrator\Administrator as BaseAdministrator;
use Shopsys\FrameworkBundle\Model\Administrator\AdministratorFacade as BaseAdministratorFacade;

/**
 * @property \Shopsys\ShopBundle\Model\Administrator\AdministratorRepository $administratorRepository
 * @method __construct(\Doctrine\ORM\EntityManagerInterface $em, \Shopsys\ShopBundle\Model\Administrator\AdministratorRepository $administratorRepository, \Shopsys\FrameworkBundle\Model\Administrator\AdministratorFactoryInterface $administratorFactory, \Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface $encoderFactory, \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $tokenStorage)
 * @method \Shopsys\ShopBundle\Model\Administrator\Administrator create(\Shopsys\FrameworkBundle\Model\Administrator\AdministratorData $administratorData)
 * @method \Shopsys\ShopBundle\Model\Administrator\Administrator edit(int $administratorId, \Shopsys\FrameworkBundle\Model\Administrator\AdministratorData $administratorData)
 * @method checkUsername(\Shopsys\ShopBundle\Model\Administrator\Administrator $administrator, string $username)
 * @method setPassword(\Shopsys\ShopBundle\Model\Administrator\Administrator $administrator, string $password)
 * @method \Shopsys\ShopBundle\Model\Administrator\Administrator getById(int $administratorId)
 */
class AdministratorFacade extends BaseAdministratorFacade
{
    /**
     * @param \Shopsys\ShopBundle\Model\Administrator\Administrator $administrator
     */
    protected function checkForDelete(BaseAdministrator $administrator)
    {
        parent::checkForDelete($administrator);

        if ($administrator->isDefaultAdmin()) {
            throw new \Shopsys\ShopBundle\Model\Administrator\Exception\DeletingDefaultAdminException();
        }
    }
}
