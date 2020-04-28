<?php

declare(strict_types=1);

namespace Tests\ShopBundle\Functional\Model\Administrator\Security;

use Shopsys\FrameworkBundle\Model\Administrator\Security\AdministratorFrontSecurityFacade;
use App\DataFixtures\Demo\AdministratorDataFixture;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Tests\ShopBundle\Test\TransactionFunctionalTestCase;

class AdministratorFrontSecurityFacadeTest extends TransactionFunctionalTestCase
{
    /**
     * @var \Shopsys\FrameworkBundle\Model\Administrator\Security\AdministratorFrontSecurityFacade
     * @inject
     */
    private $administratorFrontSecurityFacade;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Administrator\Activity\AdministratorActivityFacade
     * @inject
     */
    private $administratorActivityFacade;

    public function testIsAdministratorLoggedNot()
    {
        $this->assertFalse($this->administratorFrontSecurityFacade->isAdministratorLogged());
    }

    public function testIsAdministratorLogged()
    {
        /* @var $session \Symfony\Component\HttpFoundation\Session\SessionInterface */
        $session = $this->getContainer()->get('session');
        /* @var $administrator \Shopsys\FrameworkBundle\Model\Administrator\Administrator */
        $administrator = $this->getReference(AdministratorDataFixture::ADMINISTRATOR);
        $password = '';
        $roles = $administrator->getRoles();
        $token = new UsernamePasswordToken($administrator, $password, AdministratorFrontSecurityFacade::ADMINISTRATION_CONTEXT, $roles);

        $session->set('_security_' . AdministratorFrontSecurityFacade::ADMINISTRATION_CONTEXT, serialize($token));

        $this->administratorActivityFacade->create($administrator, '127.0.0.1');

        $this->assertTrue($this->administratorFrontSecurityFacade->isAdministratorLogged());
    }
}
