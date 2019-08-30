<?php

declare(strict_types=1);

namespace Tests\ShopBundle\Acceptance\acceptance;

use Tests\ShopBundle\Acceptance\acceptance\PageObject\Front\LayoutPage;
use Tests\ShopBundle\Acceptance\acceptance\PageObject\Front\LoginPage;
use Tests\ShopBundle\Acceptance\acceptance\PageObject\Front\ProductDetailPage;
use Tests\ShopBundle\Test\Codeception\AcceptanceTester;

class CustomerDiscountCest
{
    /**
     * @param \Tests\ShopBundle\Test\Codeception\AcceptanceTester $me
     * @param \Tests\ShopBundle\Acceptance\acceptance\PageObject\Front\LayoutPage $layoutPage
     * @param \Tests\ShopBundle\Acceptance\acceptance\PageObject\Front\LoginPage $loginPage
     * @param \Tests\ShopBundle\Acceptance\acceptance\PageObject\Front\ProductDetailPage $productDetailPage
     */
    public function testPriceIsDiscountedWhenLoggedIn(
        AcceptanceTester $me,
        LayoutPage $layoutPage,
        LoginPage $loginPage,
        ProductDetailPage $productDetailPage
    ) {
        $me->wantTo('see customer discount on product detail');
        $me->amOnPage('/prime-flour-1-kg/');

        $productDetailPage->assertPriceWithVat('CZK10.00');
        $productDetailPage->assertPriceWithoutVat('CZK8.70');

        $layoutPage->openLoginPopup();
        /** @see \Shopsys\ShopBundle\DataFixtures\Demo\UserDataFixture::USER_WITH_10_PERCENT_DISCOUNT */
        $loginPage->login('no-reply.3@shopsys.com', 'no-reply.3');

        $productDetailPage->assertPriceWithVat('CZK9.00');
        $productDetailPage->assertPriceWithoutVat('CZK7.83');
    }
}
