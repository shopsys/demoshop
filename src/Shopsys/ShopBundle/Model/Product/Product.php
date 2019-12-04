<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Model\Product;

use Doctrine\ORM\Mapping as ORM;
use Shopsys\FrameworkBundle\Model\Product\Product as BaseProduct;
use Shopsys\FrameworkBundle\Model\Product\ProductData;

/**
 * @ORM\Table(name="products")
 * @ORM\Entity
 * @property \Shopsys\ShopBundle\Model\Product\Product[]|\Doctrine\Common\Collections\Collection $variants
 * @property \Shopsys\ShopBundle\Model\Product\Product|null $mainVariant
 * @method static \Shopsys\ShopBundle\Model\Product\Product create(\Shopsys\ShopBundle\Model\Product\ProductData $productData)
 * @method static \Shopsys\ShopBundle\Model\Product\Product createMainVariant(\Shopsys\ShopBundle\Model\Product\ProductData $productData, \Shopsys\ShopBundle\Model\Product\Product[] $variants)
 * @method setAvailabilityAndStock(\Shopsys\ShopBundle\Model\Product\ProductData $productData)
 * @method \Shopsys\ShopBundle\Model\Category\Category[][] getCategoriesIndexedByDomainId()
 * @method \Shopsys\ShopBundle\Model\Product\Product getMainVariant()
 * @method addVariant(\Shopsys\ShopBundle\Model\Product\Product $variant)
 * @method addVariants(\Shopsys\ShopBundle\Model\Product\Product[] $variants)
 * @method \Shopsys\ShopBundle\Model\Product\Product[] getVariants()
 * @method setMainVariant(\Shopsys\ShopBundle\Model\Product\Product $mainVariant)
 * @method setTranslations(\Shopsys\ShopBundle\Model\Product\ProductData $productData)
 * @method setDomains(\Shopsys\ShopBundle\Model\Product\ProductData $productData)
 * @method createDomains(\Shopsys\ShopBundle\Model\Product\ProductData $productData)
 * @method refreshVariants(\Shopsys\ShopBundle\Model\Product\Product[] $currentVariants)
 * @method addNewVariants(\Shopsys\ShopBundle\Model\Product\Product[] $currentVariants)
 * @method unsetRemovedVariants(\Shopsys\ShopBundle\Model\Product\Product[] $currentVariants)
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
     * @param \Shopsys\ShopBundle\Model\Product\Product[]|null $variants
     */
    protected function __construct(ProductData $productData, ?array $variants = null)
    {
        parent::__construct($productData, $variants);
        $this->condition = $productData->condition;
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Product\ProductCategoryDomain[] $productCategoryDomains
     * @param \Shopsys\ShopBundle\Model\Product\ProductData $productData
     */
    public function edit(array $productCategoryDomains, ProductData $productData)
    {
        parent::edit($productCategoryDomains, $productData);
        $this->condition = $productData->condition;
    }

    /**
     * @return string
     */
    public function getCondition(): string
    {
        return $this->condition;
    }
}
