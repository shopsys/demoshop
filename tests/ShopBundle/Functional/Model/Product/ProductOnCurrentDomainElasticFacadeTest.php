<?php

declare(strict_types=1);

namespace Tests\ShopBundle\Functional\Model\Product;

use Shopsys\FrameworkBundle\Model\Product\ProductOnCurrentDomainFacadeInterface;

class ProductOnCurrentDomainElasticFacadeTest extends ProductOnCurrentDomainFacadeTest
{
    /**
     * @var \Shopsys\FrameworkBundle\Model\Product\ProductOnCurrentDomainElasticFacade
     * @inject
     */
    private $productOnCurrentDomainElasticFacade;

    /**
     * @return \Shopsys\FrameworkBundle\Model\Product\ProductOnCurrentDomainFacadeInterface
     */
    public function getProductOnCurrentDomainFacade(): ProductOnCurrentDomainFacadeInterface
    {
        return $this->productOnCurrentDomainElasticFacade;
    }
}
