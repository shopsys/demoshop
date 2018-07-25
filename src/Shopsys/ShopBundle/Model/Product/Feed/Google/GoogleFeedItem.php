<?php

namespace Shopsys\ShopBundle\Model\Product\Feed\Google;

use Shopsys\FrameworkBundle\Model\Pricing\Currency\Currency;
use Shopsys\FrameworkBundle\Model\Pricing\Price;
use Shopsys\ProductFeed\GoogleBundle\Model\FeedItem\GoogleFeedItem as BaseGoogleFeedItem;

class GoogleFeedItem extends BaseGoogleFeedItem
{
    /**
     * @var string
     */
    private $condition;

    public function __construct(
        int $id,
        string $name,
        ?string $brandName,
        ?string $description,
        ?string $ean,
        ?string $partno,
        string $url,
        ?string $imgUrl,
        bool $sellingDenied,
        Price $price,
        Currency $currency,
        string $condition
    ) {
        parent::__construct(
            $id,
            $name,
            $brandName,
            $description,
            $ean,
            $partno,
            $url,
            $imgUrl,
            $sellingDenied,
            $price,
            $currency
        );

        $this->condition = $condition;
    }

    /**
     * @return string
     */
    public function getCondition(): string
    {
        return $this->condition;
    }

    /**
     * @param string $condition
     */
    public function setCondition(string $condition): void
    {
        $this->condition = $condition;
    }
}
