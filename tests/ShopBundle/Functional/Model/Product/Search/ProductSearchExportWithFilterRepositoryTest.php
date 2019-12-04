<?php

declare(strict_types=1);

namespace Tests\ShopBundle\Functional\Model\Product\Search;

use Tests\ShopBundle\Test\TransactionFunctionalTestCase;

class ProductSearchExportWithFilterRepositoryTest extends TransactionFunctionalTestCase
{
    /**
     * @var \Shopsys\FrameworkBundle\Model\Product\Search\Export\ProductSearchExportWithFilterRepository
     * @inject
     */
    private $repository;

    public function testProductDataHaveExpectedStructure(): void
    {
        $data = $this->repository->getProductsData($this->domain->getId(), $this->domain->getLocale(), 0, 10);
        $this->assertCount(10, $data);

        $structure = array_keys(reset($data));
        sort($structure);

        $expectedStructure = [
            'id',
            'name',
            'catnum',
            'partno',
            'ean',
            'description',
            'short_description',
            'availability',
            'brand',
            'flags',
            'categories',
            'detail_url',
            'in_stock',
            'prices',
            'parameters',
            'ordering_priority',
            'calculated_selling_denied',
            'selling_denied',
            'main_variant',
            'visibility',
        ];
        sort($expectedStructure);

        $this->assertSame($expectedStructure, $structure);
    }
}
