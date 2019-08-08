<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Model\Product;

use Shopsys\FrameworkBundle\Model\Product\ProductData as BaseProductData;

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
