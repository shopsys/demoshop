<?php

namespace Tests\ShopBundle\Acceptance\acceptance;

use Codeception\Scenario;
use Tests\ShopBundle\Acceptance\acceptance\PageObject\Admin\LoginPage;
use Tests\ShopBundle\Test\Codeception\AcceptanceTester;

class LoginAsCustomerCest
{
    public function testLoginAsCustomer(AcceptanceTester $me, LoginPage $loginPage, Scenario $scenario)
    {
        $scenario->skip('The fixed bar with warning message is hidden temporarily for design debugging purposes.');
        $me->wantTo('login as a customer from admin');
        $loginPage->loginAsAdmin();
        $me->amOnPage('/admin/customer/edit/2');
        $me->clickByText('Log in as user');
        $me->switchToLastOpenedWindow();
        $me->seeCurrentPageEquals('/');
        $me->see('Attention! You are administrator logged in as the customer.');
        $me->see('Igor Anpilogov');
    }
}
