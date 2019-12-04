<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Model\Category;

use Shopsys\FrameworkBundle\Model\Category\CategoryData as BaseCategoryData;

/**
 * @property \Shopsys\ShopBundle\Model\Category\Category|null $parent
 */
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
