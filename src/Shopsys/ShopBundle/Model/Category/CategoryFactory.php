<?php

namespace Shopsys\ShopBundle\Model\Category;

use Shopsys\FrameworkBundle\Model\Category\Category as BaseCategory;
use Shopsys\FrameworkBundle\Model\Category\CategoryData as BaseCategoryData;
use Shopsys\FrameworkBundle\Model\Category\CategoryFactoryInterface;

class CategoryFactory implements CategoryFactoryInterface
{
    /**
     * @param \Shopsys\ShopBundle\Model\Category\CategoryData $data
     * @return \Shopsys\ShopBundle\Model\Category\Category
     */
    public function create(BaseCategoryData $data): BaseCategory
    {
        return new Category($data);
    }
}
