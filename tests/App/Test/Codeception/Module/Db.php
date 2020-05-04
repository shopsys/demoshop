<?php

declare(strict_types=1);

namespace Tests\App\Test\Codeception\Module;

use Codeception\Module\Db as BaseDb;
use Shopsys\FrameworkBundle\Component\Doctrine\DatabaseSchemaFacade;
use Tests\App\Test\Codeception\Helper\SymfonyHelper;

class Db extends BaseDb
{
    /**
     * Revert database to the original state
     */
    public function _afterSuite()
    {
        $this->_loadDump();
    }

    public function cleanup()
    {
        $symfonyHelper = $this->getModule(SymfonyHelper::class);
        /* @var $symfonyHelper \Tests\App\Test\Codeception\Helper\SymfonyHelper */
        $databaseSchemaFacade = $symfonyHelper->grabServiceFromContainer(DatabaseSchemaFacade::class);
        /* @var $databaseSchemaFacade \Shopsys\FrameworkBundle\Component\Doctrine\DatabaseSchemaFacade */
        $databaseSchemaFacade->dropSchemaIfExists('public');
    }

    /**
     * @inheritDoc
     */
    public function _loadDump($databaseKey = null, $databaseConfig = null)
    {
        $this->cleanup();
        return parent::_loadDump($databaseKey, $databaseConfig);
    }
}