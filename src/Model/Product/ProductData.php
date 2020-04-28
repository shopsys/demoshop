<?php

declare(strict_types=1);

namespace App\Model\Product;

use Shopsys\FrameworkBundle\Model\Product\ProductData as BaseProductData;

/**
 * @property \App\Model\Category\Category[][] $categoriesByDomainId
 * @property \App\Model\Product\Product[] $accessories
 * @property \App\Model\Product\Product[] $variants
 */
class ProductData extends BaseProductData
{
    /**
     * @var string
     */
    public $condition;

    public function __construct()
    {
        $this->condition = ProductConditionFacade::CONDITION_NEW;
        parent::__construct();
    }
}
