<?php

declare(strict_types=1);

namespace Tests\ShopBundle\Functional\Model\Administrator\Security;

use Shopsys\FrameworkBundle\Model\Administrator\Activity\AdministratorActivityFacade;
use Shopsys\FrameworkBundle\Model\Administrator\Security\AdministratorFrontSecurityFacade;
use Shopsys\ShopBundle\DataFixtures\Demo\AdministratorDataFixture;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Tests\ShopBundle\Test\TransactionFunctionalTestCase;

class AdministratorFrontSecurityFacadeTest extends TransactionFunctionalTestCase
{
    public function testIsAdministratorLoggedNot()
    {
        $administratorFrontSecurityFacade = $this->getContainer()->get(AdministratorFrontSecurityFacade::class);
        /* @var $administratorFrontSecurityFacade \Shopsys\FrameworkBundle\Model\Administrator\Security\AdministratorFrontSecurityFacade */

        $this->assertFalse($administratorFrontSecurityFacade->isAdministratorLogged());
    }

    public function testIsAdministratorLogged()
    {
        $session = $this->getContainer()->get('session');
        /* @var $session \Symfony\Component\HttpFoundation\Session\SessionInterface */
        $administratorFrontSecurityFacade = $this->getContainer()->get(AdministratorFrontSecurityFacade::class);
        /* @var $administratorFrontSecurityFacade \Shopsys\FrameworkBundle\Model\Administrator\Security\AdministratorFrontSecurityFacade */

        $administrator = $this->getReference(AdministratorDataFixture::ADMINISTRATOR);
        /* @var $administrator \Shopsys\FrameworkBundle\Model\Administrator\Administrator */
        $password = '';
        $roles = $administrator->getRoles();
        $token = new UsernamePasswordToken($administrator, $password, AdministratorFrontSecurityFacade::ADMINISTRATION_CONTEXT, $roles);

        $session->set('_security_' . AdministratorFrontSecurityFacade::ADMINISTRATION_CONTEXT, serialize($token));

        $administratorActivityFacade = $this->getContainer()->get(AdministratorActivityFacade::class);
        /* @var $administratorActivityFacade \Shopsys\FrameworkBundle\Model\Administrator\Activity\AdministratorActivityFacade */
        $administratorActivityFacade->create($administrator, '127.0.0.1');

        $this->assertTrue($administratorFrontSecurityFacade->isAdministratorLogged());
    }
}
