<?php

namespace Shopsys\ShopBundle\Model\Product;

use Shopsys\FrameworkBundle\Model\Product\Product as BaseProduct;
use Shopsys\FrameworkBundle\Model\Product\ProductData as BaseProductData;
use Shopsys\FrameworkBundle\Model\Product\ProductFactoryInterface;

class ProductFactory implements ProductFactoryInterface
{
    /**
     * @param \Shopsys\ShopBundle\Model\Product\ProductData $data
     * @return \Shopsys\ShopBundle\Model\Product\Product
     */
    public function create(BaseProductData $data): BaseProduct
    {
        return Product::create($data);
    }

    /**
     * @param \Shopsys\ShopBundle\Model\Product\ProductData $data
     * @param \Shopsys\ShopBundle\Model\Product\Product[] $variants
     * @return \Shopsys\ShopBundle\Model\Product\Product
     */
    public function createMainVariant(BaseProductData $data, array $variants): BaseProduct
    {
        return Product::createMainVariant($data, $variants);
    }
}
