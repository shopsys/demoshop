<?php

declare(strict_types=1);

namespace Tests\ShopBundle\Functional\Model\Administrator;

use DateTime;
use Shopsys\FrameworkBundle\Model\Administrator\AdministratorRepository;
use Shopsys\ShopBundle\DataFixtures\Demo\AdministratorDataFixture;
use Tests\ShopBundle\Test\TransactionFunctionalTestCase;

class AdministratorRepositoryTest extends TransactionFunctionalTestCase
{
    public function testGetByValidMultidomainLogin()
    {
        $validMultidomainLoginToken = 'validMultidomainLoginToken';
        $multidomainLoginTokenExpiration = new DateTime('+60 seconds');

        $administrator = $this->getReference(AdministratorDataFixture::ADMINISTRATOR);
        /* @var $administrator \Shopsys\FrameworkBundle\Model\Administrator\Administrator */
        $administratorRepository = $this->getContainer()->get(AdministratorRepository::class);
        /* @var $administratorRepository \Shopsys\FrameworkBundle\Model\Administrator\AdministratorRepository */

        $administrator->setMultidomainLoginTokenWithExpiration($validMultidomainLoginToken, $multidomainLoginTokenExpiration);
        $this->getEntityManager()->flush($administrator);

        $administratorFromDb = $administratorRepository->getByValidMultidomainLoginToken($validMultidomainLoginToken);

        $this->assertSame($administrator, $administratorFromDb);
    }

    public function testGetByValidMultidomainLoginTokenInvalidTokenException()
    {
        $validMultidomainLoginToken = 'validMultidomainLoginToken';
        $invalidMultidomainLoginToken = 'invalidMultidomainLoginToken';
        $multidomainLoginTokenExpiration = new DateTime('+60 seconds');

        $administrator = $this->getReference(AdministratorDataFixture::ADMINISTRATOR);
        /* @var $administrator \Shopsys\FrameworkBundle\Model\Administrator\Administrator */
        $administratorRepository = $this->getContainer()->get(AdministratorRepository::class);
        /* @var $administratorRepository \Shopsys\FrameworkBundle\Model\Administrator\AdministratorRepository */

        $administrator->setMultidomainLoginTokenWithExpiration($validMultidomainLoginToken, $multidomainLoginTokenExpiration);
        $this->getEntityManager()->flush($administrator);

        $this->expectException('\Shopsys\FrameworkBundle\Model\Administrator\Security\Exception\InvalidTokenException');

        $administratorRepository->getByValidMultidomainLoginToken($invalidMultidomainLoginToken);
    }

    public function testGetByValidMultidomainLoginTokenExpiredTokenException()
    {
        $validMultidomainLoginToken = 'validMultidomainLoginToken';
        $multidomainLoginTokenExpiration = new DateTime('-60 seconds');

        $administrator = $this->getReference(AdministratorDataFixture::ADMINISTRATOR);
        /* @var $administrator \Shopsys\FrameworkBundle\Model\Administrator\Administrator */
        $administratorRepository = $this->getContainer()->get(AdministratorRepository::class);
        /* @var $administratorRepository \Shopsys\FrameworkBundle\Model\Administrator\AdministratorRepository */

        $administrator->setMultidomainLoginTokenWithExpiration($validMultidomainLoginToken, $multidomainLoginTokenExpiration);
        $this->getEntityManager()->flush($administrator);

        $this->expectException('\Shopsys\FrameworkBundle\Model\Administrator\Security\Exception\InvalidTokenException');

        $administratorRepository->getByValidMultidomainLoginToken($validMultidomainLoginToken);
    }
}
