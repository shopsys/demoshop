<?php

namespace Shopsys\ShopBundle\Model\Product;

use Doctrine\ORM\Mapping as ORM;
use Shopsys\FrameworkBundle\Model\Product\Product as BaseProduct;
use Shopsys\FrameworkBundle\Model\Product\ProductData;

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
