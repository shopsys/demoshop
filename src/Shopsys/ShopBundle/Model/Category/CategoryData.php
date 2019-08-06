<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Model\Category;

use Shopsys\FrameworkBundle\Model\Category\CategoryData as BaseCategoryData;

class CategoryData extends BaseCategoryData
{
    /**
     * @var string[]
     */
    public $descriptionsSecond;

    public function __construct()
    {
        $this->descriptionsSecond = [];
        parent::__construct();
    }
}
