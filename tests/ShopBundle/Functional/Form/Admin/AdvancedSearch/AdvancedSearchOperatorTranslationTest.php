<?php

declare(strict_types=1);

namespace Tests\ShopBundle\Functional\Form\Admin\AdvancedSearch;

use Tests\ShopBundle\Test\FunctionalTestCase;

class AdvancedSearchOperatorTranslationTest extends FunctionalTestCase
{
    /**
     * @var \Shopsys\FrameworkBundle\Model\AdvancedSearch\ProductAdvancedSearchConfig
     * @inject
     */
    private $productAdvancedSearchConfig;

    /**
     * @var \Shopsys\FrameworkBundle\Model\AdvancedSearch\OrderAdvancedSearchConfig
     * @inject
     */
    private $orderAdvancedSearchConfig;

    /**
     * @var \Shopsys\FrameworkBundle\Form\Admin\AdvancedSearch\AdvancedSearchOperatorTranslation
     * @inject
     */
    private $advancedSearchOperatorTranslation;

    public function testTranslateOperator()
    {
        $operators = [];
        foreach ($this->productAdvancedSearchConfig->getAllFilters() as $filter) {
            $operators = array_merge($operators, $filter->getAllowedOperators());
        }
        foreach ($this->orderAdvancedSearchConfig->getAllFilters() as $filter) {
            $operators = array_merge($operators, $filter->getAllowedOperators());
        }

        foreach ($operators as $operator) {
            $this->assertNotEmpty($this->advancedSearchOperatorTranslation->translateOperator($operator));
        }
    }

    public function testTranslateOperatorNotFoundException()
    {
        $this->expectException(\Shopsys\FrameworkBundle\Model\AdvancedSearch\Exception\AdvancedSearchTranslationNotFoundException::class);
        $this->advancedSearchOperatorTranslation->translateOperator('nonexistingOperator');
    }
}
