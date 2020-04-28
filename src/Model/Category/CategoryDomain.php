<?php

declare(strict_types=1);

namespace App\Model\Category;

use Doctrine\ORM\Mapping as ORM;
use Shopsys\FrameworkBundle\Model\Category\CategoryDomain as BaseCategoryDomain;

/**
 * @ORM\Table(
 *     name="category_domains",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="category_domain", columns={"category_id", "domain_id"})
 *     }
 * )
 * @ORM\Entity
 * @property \App\Model\Category\Category $category
 * @method __construct(\App\Model\Category\Category $category, int $domainId)
 */
class CategoryDomain extends BaseCategoryDomain
{
    /**
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $descriptionSecond;

    /**
     * @return string|null
     */
    public function getDescriptionSecond()
    {
        return $this->descriptionSecond;
    }

    /**
     * @param string $descriptionSecond
     */
    public function setDescriptionSecond($descriptionSecond)
    {
        $this->descriptionSecond = $descriptionSecond;
    }
}
