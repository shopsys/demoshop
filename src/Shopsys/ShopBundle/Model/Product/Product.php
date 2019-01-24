<?php

namespace Shopsys\ShopBundle\Model\Product;

use Doctrine\ORM\Mapping as ORM;
use Shopsys\FrameworkBundle\Model\Product\Pricing\ProductPriceRecalculationScheduler;
use Shopsys\FrameworkBundle\Model\Product\Product as BaseProduct;
use Shopsys\FrameworkBundle\Model\Product\ProductCategoryDomainFactoryInterface;
use Shopsys\FrameworkBundle\Model\Product\ProductData as BaseProductData;

/**
 * @ORM\Table(name="products")
 * @ORM\Entity
 */
class Product extends BaseProduct
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", options={"default": "new"})
     */
    protected $condition;

    /**
     * @param \Shopsys\ShopBundle\Model\Product\ProductData $productData
     * @param \Shopsys\FrameworkBundle\Model\Product\ProductCategoryDomainFactoryInterface $productCategoryDomainFactory
     * @param \Shopsys\ShopBundle\Model\Product\Product[]|null $variants
     */
    protected function __construct(ProductData $productData, ProductCategoryDomainFactoryInterface $productCategoryDomainFactory, array $variants = null)
    {
        $this->condition = $productData->condition;
        parent::__construct($productData, $productCategoryDomainFactory, $variants);
    }

    /**
     * @param \Shopsys\ShopBundle\Model\Product\ProductData $productData
     * @param \Shopsys\FrameworkBundle\Model\Product\ProductCategoryDomainFactoryInterface $productCategoryDomainFactory
     * @return \Shopsys\ShopBundle\Model\Product\Product
     */
    public static function create(BaseProductData $productData, ProductCategoryDomainFactoryInterface $productCategoryDomainFactory)
    {
        return new self($productData, $productCategoryDomainFactory, null);
    }

    /**
     * @param \Shopsys\ShopBundle\Model\Product\ProductData $productData
     * @param \Shopsys\FrameworkBundle\Model\Product\ProductCategoryDomainFactoryInterface $productCategoryDomainFactory
     * @param \Shopsys\ShopBundle\Model\Product\Product[] $variants
     * @return \Shopsys\ShopBundle\Model\Product\Product
     */
    public static function createMainVariant(BaseProductData $productData, ProductCategoryDomainFactoryInterface $productCategoryDomainFactory, array $variants)
    {
        return new self($productData, $productCategoryDomainFactory, $variants);
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Product\ProductCategoryDomainFactoryInterface $productCategoryDomainFactory
     * @param \Shopsys\ShopBundle\Model\Product\ProductData $productData
     * @param \Shopsys\FrameworkBundle\Model\Product\Pricing\ProductPriceRecalculationScheduler $productPriceRecalculationScheduler
     * @param \Shopsys\ShopBundle\Model\Product\ProductData
     */
    public function edit(
        ProductCategoryDomainFactoryInterface $productCategoryDomainFactory,
        BaseProductData $productData,
        ProductPriceRecalculationScheduler $productPriceRecalculationScheduler
    ) {
        $this->condition = $productData->condition;
        parent::edit($productCategoryDomainFactory, $productData, $productPriceRecalculationScheduler);
    }

    /**
     * @return string
     */
    public function getCondition(): string
    {
        return $this->condition;
    }
}
