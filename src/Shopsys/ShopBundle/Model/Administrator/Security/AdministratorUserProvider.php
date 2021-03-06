<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Model\Administrator\Security;

use DateTime;
use Shopsys\FrameworkBundle\Model\Administrator\Administrator;
use Shopsys\FrameworkBundle\Model\Administrator\Security\AdministratorUserProvider as BaseAdministratorUserProvider;
use Shopsys\FrameworkBundle\Model\Security\TimelimitLoginInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @property \Shopsys\ShopBundle\Model\Administrator\AdministratorRepository $administratorRepository
 * @method __construct(\Shopsys\ShopBundle\Model\Administrator\AdministratorRepository $administratorRepository, \Shopsys\FrameworkBundle\Model\Administrator\Activity\AdministratorActivityFacade $administratorActivityFacade)
 * @method \Shopsys\ShopBundle\Model\Administrator\Administrator loadUserByUsername(string $username)
 */
class AdministratorUserProvider extends BaseAdministratorUserProvider
{
    /**
     * @param \Symfony\Component\Security\Core\User\UserInterface $userInterface
     * @return \Shopsys\ShopBundle\Model\Administrator\Administrator
     */
    public function refreshUser(UserInterface $userInterface)
    {
        $class = get_class($userInterface);
        if (!$this->supportsClass($class)) {
            $message = sprintf('Instances of "%s" are not supported.', $class);
            throw new \Symfony\Component\Security\Core\Exception\UnsupportedUserException($message);
        }

        /** @var \Shopsys\ShopBundle\Model\Administrator\Administrator $administrator */
        $administrator = $userInterface;

        $freshAdministrator = $this->administratorRepository->findById($administrator->getId());

        if ($administrator instanceof TimelimitLoginInterface) {
            if (time() - $administrator->getLastActivity()->getTimestamp() > 3600 * 5) {
                throw new \Symfony\Component\Security\Core\Exception\AuthenticationExpiredException('Admin was too long inactive.');
            }
            if ($freshAdministrator !== null) {
                $freshAdministrator->setLastActivity(new DateTime());
            }
        }

        if ($freshAdministrator === null) {
            throw new \Symfony\Component\Security\Core\Exception\UsernameNotFoundException('Unable to find an active admin');
        }

        if ($freshAdministrator instanceof Administrator) {
            $this->administratorActivityFacade->updateCurrentActivityLastActionTime($freshAdministrator);
        }

        return $freshAdministrator;
    }
}
