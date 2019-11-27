<?php

declare(strict_types=1);

namespace Tests\ShopBundle\Functional\Model\Unit;

use Shopsys\FrameworkBundle\Model\Product\Unit\UnitData;
use Shopsys\ShopBundle\DataFixtures\Demo\ProductDataFixture;
use Shopsys\ShopBundle\DataFixtures\Demo\UnitDataFixture;
use Tests\ShopBundle\Test\TransactionFunctionalTestCase;

class UnitFacadeTest extends TransactionFunctionalTestCase
{
    /**
     * @var \Shopsys\FrameworkBundle\Model\Product\Unit\UnitFacade
     * @inject
     */
    private $unitFacade;

    /**
     * @var \Shopsys\ShopBundle\Model\Product\ProductDataFactory
     * @inject
     */
    private $productDataFactory;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Product\ProductFacade
     * @inject
     */
    private $productFacade;

    public function testDeleteByIdAndReplace()
    {
        $em = $this->getEntityManager();

        $unitData = new UnitData();
        $unitData->name = ['cs' => 'name'];
        $unitToDelete = $this->unitFacade->create($unitData);
        $unitToReplaceWith = $this->getReference(UnitDataFixture::UNIT_PIECES);
        /* @var $newUnit \Shopsys\FrameworkBundle\Model\Product\Unit\Unit */
        $product = $this->getReference(ProductDataFixture::PRODUCT_PREFIX . '1');
        /* @var $product \Shopsys\ShopBundle\Model\Product\Product */
        $productData = $this->productDataFactory->createFromProduct($product);
        /* @var $productData \Shopsys\ShopBundle\Model\Product\ProductData */

        $productData->unit = $unitToDelete;
        $this->productFacade->edit($product->getId(), $productData);

        $this->unitFacade->deleteById($unitToDelete->getId(), $unitToReplaceWith->getId());

        $em->refresh($product);

        $this->assertEquals($unitToReplaceWith, $product->getUnit());
    }
}
