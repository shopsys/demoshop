<?php

declare(strict_types=1);

namespace Tests\ShopBundle\Acceptance\acceptance;

use PHPUnit\Framework\Assert;
use Tests\ShopBundle\Test\Codeception\AcceptanceTester;

class ErrorHandlingCest
{
    /**
     * @param \Tests\ShopBundle\Test\Codeception\AcceptanceTester $me
     */
    public function testDisplayNotice(AcceptanceTester $me)
    {
        $me->wantTo('display notice error page');
        $me->amOnPage('/test/error-handler/notice');
        $me->see('Oops! Error occurred');
        $me->dontSee('Notice');
    }

    /**
    +     * @param \Tests\ShopBundle\Test\Codeception\AcceptanceTester $me
    +     */
    public function test500ErrorPage(AcceptanceTester $me)
    {
        $me->wantTo('display 500 error and check error ID uniqueness');
        $me->amOnPage('/test/error-handler/exception');
        $me->see('Oops! Error occurred');
        $cssIdentifier = ['css' => '#js-error-id'];
        $errorIdFirstAccess = $me->grabTextFrom($cssIdentifier);
        $me->amOnPage('/test/error-handler/exception');
        $errorIdSecondAccess = $me->grabTextFrom($cssIdentifier);
        Assert::assertNotSame($errorIdFirstAccess, $errorIdSecondAccess);
    }
}
