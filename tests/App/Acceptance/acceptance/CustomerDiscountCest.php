<?php

declare(strict_types=1);

namespace Tests\App\Acceptance\acceptance;

use Tests\App\Acceptance\acceptance\PageObject\Front\LayoutPage;
use Tests\App\Acceptance\acceptance\PageObject\Front\LoginPage;
use Tests\App\Acceptance\acceptance\PageObject\Front\ProductDetailPage;
use Tests\App\Test\Codeception\AcceptanceTester;

class CustomerDiscountCest
{
    /**
     * @param \Tests\App\Test\Codeception\AcceptanceTester $me
     * @param \Tests\App\Acceptance\acceptance\PageObject\Front\LayoutPage $layoutPage
     * @param \Tests\App\Acceptance\acceptance\PageObject\Front\LoginPage $loginPage
     * @param \Tests\App\Acceptance\acceptance\PageObject\Front\ProductDetailPage $productDetailPage
     */
    public function testPriceIsDiscountedWhenLoggedIn(
        AcceptanceTester $me,
        LayoutPage $layoutPage,
        LoginPage $loginPage,
        ProductDetailPage $productDetailPage
    ) {
        $me->wantTo('see customer discount on product detail');
        $me->amOnPage('/prime-flour-1-kg/');

        $productDetailPage->assertPriceWithVat('€0.38');
        $productDetailPage->assertPriceWithoutVat('€0.33');

        $layoutPage->openLoginPopup();
        /** @see \App\DataFixtures\Demo\UserDataFixture::USER_WITH_10_PERCENT_DISCOUNT */
        $loginPage->login('no-reply.3@shopsys.com', 'no-reply.3');

        $productDetailPage->assertPriceWithVat('€0.34');
        $productDetailPage->assertPriceWithoutVat('€0.30');
    }
}
