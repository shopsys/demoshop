<?php

declare(strict_types=1);

namespace Tests\App\Functional\Model\Product;

use App\DataFixtures\Demo\ProductDataFixture;
use Tests\App\Test\TransactionFunctionalTestCase;
use Zalas\Injector\PHPUnit\Symfony\TestCase\SymfonyTestContainer;

class ProductSellingDeniedRecalculatorTest extends TransactionFunctionalTestCase
{
    use SymfonyTestContainer;

    /**
     * @var \App\Model\Product\ProductDataFactory
     * @inject
     */
    private $productDataFactory;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Product\ProductFacade
     * @inject
     */
    private $productFacade;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Product\ProductSellingDeniedRecalculator
     * @inject
     */
    private $productSellingDeniedRecalculator;

    public function testCalculateSellingDeniedForProductSellableVariant()
    {
        $variant1 = $this->getReference(ProductDataFixture::PRODUCT_PREFIX . '53');
        /* @var $variant1 \App\Model\Product\Product */
        $variant2 = $this->getReference(ProductDataFixture::PRODUCT_PREFIX . '54');
        /* @var $variant2 \App\Model\Product\Product */
        $variant3 = $this->getReference(ProductDataFixture::PRODUCT_PREFIX . '69');
        /* @var $variant3 \App\Model\Product\Product */
        $mainVariant = $this->getReference(ProductDataFixture::PRODUCT_PREFIX . '148');
        /* @var $mainVariant \App\Model\Product\Product */

        $variant1productData = $this->productDataFactory->createFromProduct($variant1);
        $variant1productData->sellingDenied = true;
        $this->productFacade->edit($variant1->getId(), $variant1productData);

        $this->productSellingDeniedRecalculator->calculateSellingDeniedForProduct($variant1);

        $this->em->refresh($variant1);
        $this->em->refresh($variant2);
        $this->em->refresh($variant3);
        $this->em->refresh($mainVariant);

        $this->assertTrue($variant1->getCalculatedSellingDenied());
        $this->assertFalse($variant2->getCalculatedSellingDenied());
        $this->assertFalse($variant3->getCalculatedSellingDenied());
        $this->assertFalse($mainVariant->getCalculatedSellingDenied());
    }

    public function testCalculateSellingDeniedForProductNotSellableVariants()
    {
        $variant1 = $this->getReference(ProductDataFixture::PRODUCT_PREFIX . '53');
        /* @var $variant1 \App\Model\Product\Product */
        $variant2 = $this->getReference(ProductDataFixture::PRODUCT_PREFIX . '54');
        /* @var $variant2 \App\Model\Product\Product */
        $variant3 = $this->getReference(ProductDataFixture::PRODUCT_PREFIX . '69');
        /* @var $variant2 \App\Model\Product\Product */
        $mainVariant = $this->getReference(ProductDataFixture::PRODUCT_PREFIX . '148');
        /* @var $variant2 \App\Model\Product\Product */

        $variant1productData = $this->productDataFactory->createFromProduct($variant1);
        $variant1productData->sellingDenied = true;
        $this->productFacade->edit($variant1->getId(), $variant1productData);
        $variant2productData = $this->productDataFactory->createFromProduct($variant2);
        $variant2productData->sellingDenied = true;
        $this->productFacade->edit($variant2->getId(), $variant2productData);
        $variant3productData = $this->productDataFactory->createFromProduct($variant3);
        $variant3productData->sellingDenied = true;
        $this->productFacade->edit($variant3->getId(), $variant3productData);

        $this->productSellingDeniedRecalculator->calculateSellingDeniedForProduct($mainVariant);

        $this->em->refresh($variant1);
        $this->em->refresh($variant2);
        $this->em->refresh($variant3);
        $this->em->refresh($mainVariant);

        $this->assertTrue($variant1->getCalculatedSellingDenied());
        $this->assertTrue($variant2->getCalculatedSellingDenied());
        $this->assertTrue($variant3->getCalculatedSellingDenied());
        $this->assertTrue($mainVariant->getCalculatedSellingDenied());
    }

    public function testCalculateSellingDeniedForProductNotSellableMainVariant()
    {
        $variant1 = $this->getReference(ProductDataFixture::PRODUCT_PREFIX . '53');
        /* @var $variant1 \App\Model\Product\Product */
        $variant2 = $this->getReference(ProductDataFixture::PRODUCT_PREFIX . '54');
        /* @var $variant2 \App\Model\Product\Product */
        $variant3 = $this->getReference(ProductDataFixture::PRODUCT_PREFIX . '69');
        /* @var $variant3 \App\Model\Product\Product */
        $mainVariant = $this->getReference(ProductDataFixture::PRODUCT_PREFIX . '148');
        /* @var $mainVariant \App\Model\Product\Product */

        $mainVariantproductData = $this->productDataFactory->createFromProduct($mainVariant);
        $mainVariantproductData->sellingDenied = true;
        $this->productFacade->edit($mainVariant->getId(), $mainVariantproductData);

        $this->productSellingDeniedRecalculator->calculateSellingDeniedForProduct($mainVariant);

        $this->em->refresh($variant1);
        $this->em->refresh($variant2);
        $this->em->refresh($variant3);
        $this->em->refresh($mainVariant);

        $this->assertTrue($variant1->getCalculatedSellingDenied());
        $this->assertTrue($variant2->getCalculatedSellingDenied());
        $this->assertTrue($variant3->getCalculatedSellingDenied());
        $this->assertTrue($mainVariant->getCalculatedSellingDenied());
    }
}
