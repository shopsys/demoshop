<?php

namespace Shopsys\ShopBundle\Model\Product\Feed\Google;

use Shopsys\FrameworkBundle\Component\Domain\Config\DomainConfig;
use Shopsys\FrameworkBundle\Model\Pricing\Currency\CurrencyFacade;
use Shopsys\FrameworkBundle\Model\Product\Collection\ProductUrlsBatchLoader;
use Shopsys\FrameworkBundle\Model\Product\Pricing\ProductPriceCalculationForUser;
use Shopsys\FrameworkBundle\Model\Product\Product as BaseProduct;
use Shopsys\ProductFeed\GoogleBundle\Model\FeedItem\GoogleFeedItem as BaseGoogleFeedItem;
use Shopsys\ProductFeed\GoogleBundle\Model\FeedItem\GoogleFeedItemFactory as BaseGoogleFeedItemFactory;

class GoogleFeedItemFactory extends BaseGoogleFeedItemFactory
{
    /**
     * @param \Shopsys\ShopBundle\Model\Product\Product $product
     * @param \Shopsys\FrameworkBundle\Component\Domain\Config\DomainConfig $domainConfig
     * @return \Shopsys\ShopBundle\Model\Product\Feed\Google\GoogleFeedItem
     */
    public function create(BaseProduct $product, DomainConfig $domainConfig): BaseGoogleFeedItem
    {
        return new GoogleFeedItem(
            $product->getId(),
            $product->getName($domainConfig->getLocale()),
            $this->getBrandName($product),
            $product->getDescription($domainConfig->getId()),
            $product->getEan(),
            $product->getPartno(),
            $this->productUrlsBatchLoader->getProductUrl($product, $domainConfig),
            $this->productUrlsBatchLoader->getProductImageUrl($product, $domainConfig),
            $product->isSellingDenied(),
            $this->getPrice($product, $domainConfig),
            $this->getCurrency($domainConfig),
            $product->getCondition()
        );
    }
}
