<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Model\Category;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Shopsys\FrameworkBundle\Model\Category\Category as BaseCategory;
use Shopsys\FrameworkBundle\Model\Category\CategoryData as BaseCategoryData;

/**
 * @Gedmo\Tree(type="nested")
 * @ORM\Table(name="categories")
 * @ORM\Entity
 * @property \Shopsys\ShopBundle\Model\Category\Category|null $parent
 * @property \Shopsys\ShopBundle\Model\Category\Category[]|\Doctrine\Common\Collections\Collection $children
 * @property \Shopsys\ShopBundle\Model\Category\CategoryDomain[]|\Doctrine\Common\Collections\Collection $domains
 * @method __construct(\Shopsys\ShopBundle\Model\Category\CategoryData $categoryData)
 * @method edit(\Shopsys\ShopBundle\Model\Category\CategoryData $categoryData)
 * @method setParent(\Shopsys\ShopBundle\Model\Category\Category|null $parent)
 * @method \Shopsys\ShopBundle\Model\Category\Category|null getParent()
 * @method \Shopsys\ShopBundle\Model\Category\Category[] getChildren()
 * @method \Shopsys\ShopBundle\Model\Category\CategoryDomain getCategoryDomain(int $domainId)
 * @method setTranslations(\Shopsys\ShopBundle\Model\Category\CategoryData $categoryData)
 */
class Category extends BaseCategory
{
    /**
     * @param int $domainId
     * @return string|null
     */
    public function getDescriptionSecond(int $domainId)
    {
        return $this->getCategoryDomain($domainId)->getDescriptionSecond();
    }

    /**
     * @param \Shopsys\ShopBundle\Model\Category\CategoryData $categoryData
     */
    protected function setDomains(BaseCategoryData $categoryData)
    {
        foreach ($this->domains as $categoryDomain) {
            $domainId = $categoryDomain->getDomainId();

            $categoryDomain->setDescriptionSecond($categoryData->descriptionsSecond[$domainId] ?? null);
        }
        parent::setDomains($categoryData);
    }

    /**
     * @param \Shopsys\ShopBundle\Model\Category\CategoryData $categoryData
     */
    protected function createDomains(BaseCategoryData $categoryData)
    {
        $domainIds = array_keys($categoryData->seoTitles);

        foreach ($domainIds as $domainId) {
            $categoryDomain = new CategoryDomain($this, $domainId);
            $this->domains[] = $categoryDomain;
        }

        $this->setDomains($categoryData);
    }
}
