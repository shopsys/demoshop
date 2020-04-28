<?php

declare(strict_types=1);

namespace App\Model\Product;

use Doctrine\ORM\Mapping as ORM;
use Shopsys\FrameworkBundle\Model\Product\Product as BaseProduct;
use Shopsys\FrameworkBundle\Model\Product\ProductData;

/**
 * @ORM\Table(name="products")
 * @ORM\Entity
 * @property \App\Model\Product\Product[]|\Doctrine\Common\Collections\Collection $variants
 * @property \App\Model\Product\Product|null $mainVariant
 * @method static \App\Model\Product\Product create(\App\Model\Product\ProductData $productData)
 * @method static \App\Model\Product\Product createMainVariant(\App\Model\Product\ProductData $productData, \App\Model\Product\Product[] $variants)
 * @method setAvailabilityAndStock(\App\Model\Product\ProductData $productData)
 * @method \App\Model\Category\Category[][] getCategoriesIndexedByDomainId()
 * @method \App\Model\Product\Product getMainVariant()
 * @method addVariant(\App\Model\Product\Product $variant)
 * @method addVariants(\App\Model\Product\Product[] $variants)
 * @method \App\Model\Product\Product[] getVariants()
 * @method setMainVariant(\App\Model\Product\Product $mainVariant)
 * @method setTranslations(\App\Model\Product\ProductData $productData)
 * @method setDomains(\App\Model\Product\ProductData $productData)
 * @method createDomains(\App\Model\Product\ProductData $productData)
 * @method refreshVariants(\App\Model\Product\Product[] $currentVariants)
 * @method addNewVariants(\App\Model\Product\Product[] $currentVariants)
 * @method unsetRemovedVariants(\App\Model\Product\Product[] $currentVariants)
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
     * @param \App\Model\Product\ProductData $productData
     * @param \App\Model\Product\Product[]|null $variants
     */
    protected function __construct(ProductData $productData, ?array $variants = null)
    {
        parent::__construct($productData, $variants);
        $this->condition = $productData->condition;
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Product\ProductCategoryDomain[] $productCategoryDomains
     * @param \App\Model\Product\ProductData $productData
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
