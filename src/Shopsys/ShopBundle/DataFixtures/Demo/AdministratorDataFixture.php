<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\DataFixtures\Demo;

use Doctrine\Common\Persistence\ObjectManager;
use Shopsys\FrameworkBundle\Component\DataFixture\AbstractReferenceFixture;
use Shopsys\FrameworkBundle\Model\Administrator\AdministratorDataFactory;
use Shopsys\FrameworkBundle\Model\Administrator\AdministratorDataFactoryInterface;
use Shopsys\FrameworkBundle\Model\Administrator\AdministratorFacade;

class AdministratorDataFixture extends AbstractReferenceFixture
{
    public const SUPERADMINISTRATOR = 'administrator_superadministrator';
    public const ADMINISTRATOR = 'administrator_administrator';

    /**
     * @var \Shopsys\FrameworkBundle\Model\Administrator\AdministratorFacade
     */
    protected $administratorFacade;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Administrator\AdministratorDataFactoryInterface
     */
    private $administratorDataFactory;

    /**
     * @param \Shopsys\FrameworkBundle\Model\Administrator\AdministratorFacade $administratorFacade
     * @param \Shopsys\FrameworkBundle\Model\Administrator\AdministratorDataFactoryInterface $administratorDataFactory
     */
    public function __construct(AdministratorFacade $administratorFacade, AdministratorDataFactoryInterface $administratorDataFactory)
    {
        $this->administratorFacade = $administratorFacade;
        $this->administratorDataFactory = $administratorDataFactory;
    }

    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->createAdministratorReference(1, self::SUPERADMINISTRATOR);
        $this->createAdministratorReference(2, self::ADMINISTRATOR);

        $administratorData = $this->administratorDataFactory->create();
        $administratorData->username = 'admintest';
        $administratorData->realName = 'Admin Test';
        $administratorData->email = 'no-reply@shopsys.com';
        $administratorData->password = 'admin123';

        $this->administratorFacade->create($administratorData);
    }

    /**
     * Administrators are created (with specific ids) in database migration.
     *
     * @param int $administratorId
     * @param string $referenceName
     * @see \Shopsys\FrameworkBundle\Migrations\Version20180702111015
     */
    protected function createAdministratorReference(int $administratorId, string $referenceName)
    {
        $administrator = $this->administratorFacade->getById($administratorId);
        $this->addReference($referenceName, $administrator);
    }
}
