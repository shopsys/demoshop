<?php

namespace Tests\ShopBundle\Functional\Model\Product\Availability;

use Shopsys\FrameworkBundle\Model\Product\Availability\AvailabilityData;
use Shopsys\FrameworkBundle\Model\Product\Availability\AvailabilityFacade;
use Shopsys\FrameworkBundle\Model\Product\ProductDataFactoryInterface;
use Shopsys\FrameworkBundle\Model\Product\ProductFacade;
use Shopsys\ShopBundle\DataFixtures\Demo\AvailabilityDataFixture;
use Shopsys\ShopBundle\DataFixtures\Demo\ProductDataFixture;
use Shopsys\ShopBundle\Model\Product\ProductDataFactory;
use Tests\ShopBundle\Test\TransactionFunctionalTestCase;

class AvailabilityFacadeTest extends TransactionFunctionalTestCase
{
    public function testDeleteByIdAndReplace()
    {
        $em = $this->getEntityManager();
        $availabilityFacade = $this->getContainer()->get(AvailabilityFacade::class);
        /* @var $availabilityFacade \Shopsys\FrameworkBundle\Model\Product\Availability\AvailabilityFacade */
        $productDataFactory = $this->getContainer()->get(ProductDataFactoryInterface::class);
        /* @var $productDataFactory \Shopsys\FrameworkBundle\Model\Product\ProductDataFactoryInterface */
        $productFacade = $this->getContainer()->get(ProductFacade::class);
        /* @var $productFacade \Shopsys\FrameworkBundle\Model\Product\ProductFacade */

        $availabilityData = new AvailabilityData();
        $availabilityData->name = ['cs' => 'name'];
        $availabilityToDelete = $availabilityFacade->create($availabilityData);
        $availabilityToReplaceWith = $this->getReference(AvailabilityDataFixture::AVAILABILITY_IN_STOCK);
        /* @var $availabilityToReplaceWith \Shopsys\FrameworkBundle\Model\Product\Availability\Availability */
        $product = $this->getReference(ProductDataFixture::PRODUCT_PREFIX . '1');
        /* @var $product \Shopsys\ShopBundle\Model\Product\Product */
        $productData = $productDataFactory->createFromProduct($product);
        /* @var $productData \Shopsys\ShopBundle\Model\Product\ProductData */

        $productData->availability = $availabilityToDelete;
        $productData->outOfStockAvailability = $availabilityToDelete;

        $productFacade->edit($product->getId(), $productData);

        $availabilityFacade->deleteById($availabilityToDelete->getId(), $availabilityToReplaceWith->getId());

        $em->refresh($product);

        $this->assertEquals($availabilityToReplaceWith, $product->getAvailability());
        $this->assertEquals($availabilityToReplaceWith, $product->getOutOfStockAvailability());
    }
}
