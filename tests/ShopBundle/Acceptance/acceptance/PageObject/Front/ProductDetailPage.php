<?php

declare(strict_types=1);

namespace Tests\ShopBundle\Acceptance\acceptance\PageObject\Front;

use Facebook\WebDriver\WebDriverBy;
use PHPUnit\Framework\Assert;
use Tests\ShopBundle\Acceptance\acceptance\PageObject\AbstractPage;

class ProductDetailPage extends AbstractPage
{
    public const PRODUCT_DETAIL_QUANTITY_INPUT = '.js-product-detail-main-add-to-cart-wrapper input[name="add_product_form[quantity]"]';
    public const PRODUCT_DETAIL_MAIN_WRAPPER = '.js-product-detail-main-add-to-cart-wrapper';
    public const PRODUCT_DETAIL_PRICE_WITH_VAT = '.box-detail-add__prices__item.box-detail-add__prices__item--main';
    public const PRODUCT_DETAIL_PRICE_WITHOUT_VAT = '.box-detail-add__prices__item:not(.box-detail-add__prices__item--main)';

    /**
     * @param int $quantity
     */
    public function addProductIntoCart($quantity = 1)
    {
        $this->tester->fillFieldByCss(
            self::PRODUCT_DETAIL_QUANTITY_INPUT,
            $quantity
        );
        $this->tester->clickByTranslationFrontend('Add to cart', 'messages', [], WebDriverBy::cssSelector(self::PRODUCT_DETAIL_MAIN_WRAPPER));
        $this->tester->waitForAjax();
        $this->tester->wait(1); // animation of popup window
    }

    /**
     * @param string $expectedFormattedPrice
     */
    public function assertPriceWithVat(string $expectedFormattedPrice): void
    {
        $priceElement = $this->webDriver->findElement(WebDriverBy::cssSelector(self::PRODUCT_DETAIL_PRICE_WITH_VAT));
        $actualFormattedPrice = trim($priceElement->getText());

        $message = 'Price with VAT expected to be "' . $expectedFormattedPrice . '" but was "' . $actualFormattedPrice . '".';
        Assert::assertSame($expectedFormattedPrice, $actualFormattedPrice, $message);
    }

    /**
     * @param string $expectedFormattedPrice
     */
    public function assertPriceWithoutVat(string $expectedFormattedPrice): void
    {
        $priceElement = $this->webDriver->findElement(WebDriverBy::cssSelector(self::PRODUCT_DETAIL_PRICE_WITHOUT_VAT));
        $actualFormattedPrice = trim(str_replace('excluding VAT', '', $priceElement->getText()));

        $message = 'Price with VAT expected to be "' . $expectedFormattedPrice . '" but was "' . $actualFormattedPrice . '".';
        Assert::assertSame($expectedFormattedPrice, $actualFormattedPrice, $message);
    }
}
