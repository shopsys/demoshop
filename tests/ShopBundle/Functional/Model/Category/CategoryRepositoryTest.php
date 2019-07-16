<?php

namespace Tests\ShopBundle\Functional\Model\Category;

use Shopsys\FrameworkBundle\Model\Category\CategoryDataFactoryInterface;
use Shopsys\FrameworkBundle\Model\Category\CategoryFacade;
use Shopsys\FrameworkBundle\Model\Category\CategoryRepository;
use Shopsys\FrameworkBundle\Model\Category\CategoryVisibilityRepository;
use Tests\ShopBundle\Test\TransactionFunctionalTestCase;

class CategoryRepositoryTest extends TransactionFunctionalTestCase
{
    const FIRST_DOMAIN_ID = 1;
    const SECOND_DOMAIN_ID = 2;
    const THIRD_DOMAIN_ID = 3;

    public function testDoNotGetCategoriesWithoutVisibleChildren()
    {
        $categoryFacade = $this->getContainer()->get(CategoryFacade::class);
        /* @var $categoryFacade \Shopsys\FrameworkBundle\Model\Category\CategoryFacade */
        $categoryRepository = $this->getContainer()->get(CategoryRepository::class);
        /* @var $categoryRepository \Shopsys\FrameworkBundle\Model\Category\CategoryRepository */
        $categoryVisibilityRepository = $this->getContainer()->get(CategoryVisibilityRepository::class);
        /* @var $categoryVisibilityRepository \Shopsys\FrameworkBundle\Model\Category\CategoryVisibilityRepository */
        $categoryDataFactory = $this->getContainer()->get(CategoryDataFactoryInterface::class);
        /* @var $categoryDataFactory \Shopsys\FrameworkBundle\Model\Category\CategoryDataFactoryInterface */

        $categoryData = $categoryDataFactory->create();
        $categoryData->name = ['en' => 'name'];
        $categoryData->parent = $categoryFacade->getRootCategory();

        $parentCategory = $categoryFacade->create($categoryData);

        $categoryData->enabled = [
            self::FIRST_DOMAIN_ID => false,
            self::SECOND_DOMAIN_ID => false,
            self::THIRD_DOMAIN_ID => false,
        ];
        $categoryData->parent = $parentCategory;
        $categoryFacade->create($categoryData);

        $categoryVisibilityRepository->refreshCategoriesVisibility();

        $categoriesWithVisibleChildren = $categoryRepository->getCategoriesWithVisibleChildren([$parentCategory], self::FIRST_DOMAIN_ID);
        $this->assertCount(0, $categoriesWithVisibleChildren);
    }

    public function testGetCategoriesWithAtLeastOneVisibleChild()
    {
        $categoryFacade = $this->getContainer()->get(CategoryFacade::class);
        /* @var $categoryFacade \Shopsys\FrameworkBundle\Model\Category\CategoryFacade */
        $categoryRepository = $this->getContainer()->get(CategoryRepository::class);
        /* @var $categoryRepository \Shopsys\FrameworkBundle\Model\Category\CategoryRepository */
        $categoryVisibilityRepository = $this->getContainer()->get(CategoryVisibilityRepository::class);
        /* @var $categoryVisibilityRepository \Shopsys\FrameworkBundle\Model\Category\CategoryVisibilityRepository */
        $categoryDataFactory = $this->getContainer()->get(CategoryDataFactoryInterface::class);
        /* @var $categoryDataFactory \Shopsys\FrameworkBundle\Model\Category\CategoryDataFactoryInterface */

        $categoryData = $categoryDataFactory->create();
        $categoryData->name = ['en' => 'name'];
        $categoryData->parent = $categoryFacade->getRootCategory();

        $parentCategory = $categoryFacade->create($categoryData);

        $categoryData->parent = $parentCategory;
        $categoryFacade->create($categoryData);

        $categoryVisibilityRepository->refreshCategoriesVisibility();

        $categoriesWithVisibleChildren = $categoryRepository->getCategoriesWithVisibleChildren([$parentCategory], self::FIRST_DOMAIN_ID);
        $this->assertCount(1, $categoriesWithVisibleChildren);
    }
}
