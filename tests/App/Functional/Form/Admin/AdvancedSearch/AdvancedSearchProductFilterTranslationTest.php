<?php

declare(strict_types=1);

namespace Tests\App\Functional\Form\Admin\AdvancedSearch;

use Tests\App\Test\FunctionalTestCase;
use Zalas\Injector\PHPUnit\Symfony\TestCase\SymfonyTestContainer;

class AdvancedSearchProductFilterTranslationTest extends FunctionalTestCase
{
    use SymfonyTestContainer;

    /**
     * @var \Shopsys\FrameworkBundle\Model\AdvancedSearch\ProductAdvancedSearchConfig
     * @inject
     */
    private $productAdvancedSearchConfig;

    /**
     * @var \Shopsys\FrameworkBundle\Form\Admin\AdvancedSearch\AdvancedSearchProductFilterTranslation
     * @inject
     */
    private $advancedSearchProductFilterTranslation;

    public function testTranslateFilterName()
    {
        foreach ($this->productAdvancedSearchConfig->getAllFilters() as $filter) {
            $this->assertNotEmpty($this->advancedSearchProductFilterTranslation->translateFilterName($filter->getName()));
        }
    }

    public function testTranslateFilterNameNotFoundException()
    {
        $this->expectException(\Shopsys\FrameworkBundle\Model\AdvancedSearch\Exception\AdvancedSearchTranslationNotFoundException::class);
        $this->advancedSearchProductFilterTranslation->translateFilterName('nonexistingFilterName');
    }
}
