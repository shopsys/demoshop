<?php

declare(strict_types=1);

namespace Tests\App\Functional\Form\Admin\AdvancedSearch;

use Tests\App\Test\FunctionalTestCase;
use Zalas\Injector\PHPUnit\Symfony\TestCase\SymfonyTestContainer;

class AdvancedSearchOrderFilterTranslationTest extends FunctionalTestCase
{
    use SymfonyTestContainer;

    /**
     * @var \Shopsys\FrameworkBundle\Model\AdvancedSearch\OrderAdvancedSearchConfig
     * @inject
     */
    private $orderAdvancedSearchConfig;

    /**
     * @var \Shopsys\FrameworkBundle\Form\Admin\AdvancedSearch\AdvancedSearchOrderFilterTranslation
     * @inject
     */
    private $advancedSearchOrderFilterTranslation;

    public function testTranslateFilterName()
    {
        foreach ($this->orderAdvancedSearchConfig->getAllFilters() as $filter) {
            $this->assertNotEmpty($this->advancedSearchOrderFilterTranslation->translateFilterName($filter->getName()));
        }
    }

    public function testTranslateFilterNameNotFoundException()
    {
        $this->expectException(\Shopsys\FrameworkBundle\Model\AdvancedSearch\Exception\AdvancedSearchTranslationNotFoundException::class);
        $this->advancedSearchOrderFilterTranslation->translateFilterName('nonexistingFilterName');
    }
}
