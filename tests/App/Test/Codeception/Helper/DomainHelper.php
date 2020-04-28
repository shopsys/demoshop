<?php

declare(strict_types=1);

namespace Tests\App\Test\Codeception\Helper;

use Codeception\Module;
use Codeception\TestInterface;
use Shopsys\FrameworkBundle\Component\Domain\Domain;
use Tests\App\Test\Codeception\Module\StrictWebDriver;

class DomainHelper extends Module
{
    /**
     * {@inheritDoc}
     */
    public function _before(TestInterface $test)
    {
        $webDriver = $this->getModule(StrictWebDriver::class);
        /* @var $webDriver \Tests\App\Test\Codeception\Module\StrictWebDriver */
        $symfonyHelper = $this->getModule(SymfonyHelper::class);
        /* @var $symfonyHelper \Tests\App\Test\Codeception\Helper\SymfonyHelper */
        $domain = $symfonyHelper->grabServiceFromContainer(Domain::class);
        /* @var $domain \Shopsys\FrameworkBundle\Component\Domain\Domain */

        $domainConfig = $domain->getDomainConfigById(1);

        $webDriver->_reconfigure(['url' => $domainConfig->getUrl()]);
    }
}
