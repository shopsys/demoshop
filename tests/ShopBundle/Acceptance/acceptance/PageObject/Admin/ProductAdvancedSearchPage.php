<?php

declare(strict_types=1);

namespace Tests\ShopBundle\Acceptance\acceptance\PageObject\Admin;

use Facebook\WebDriver\WebDriverBy;
use PHPUnit\Framework\Assert;
use Tests\ShopBundle\Acceptance\acceptance\PageObject\AbstractPage;

class ProductAdvancedSearchPage extends AbstractPage
{
    public const SEARCH_SUBJECT_CATNUM = 'productCatnum';

    /**
     * @param string $searchSubject
     * @param string $value
     */
    public function search($searchSubject, $value)
    {
        $this->tester->amOnPage('/admin/product/list/');

        $this->tester->clickByTranslationAdmin('Advanced search');
        $this->tester->selectOptionByCssAndValue('.js-advanced-search-rule-subject', $searchSubject);
        $this->tester->waitForAjax();
        $this->tester->fillFieldByCss('.js-advanced-search-rule-value input', $value);

        $this->tester->clickByTranslationAdmin('Search [verb]', 'messages', [], WebDriverBy::cssSelector('#js-advanced-search-rules-box'));
    }

    /**
     * @param string $productName
     */
    public function assertFoundProductByName($productName)
    {
        $translatedProductName = t($productName, [], 'dataFixtures', $this->tester->getAdminLocale());
        $this->tester->seeTranslationAdminInCss($translatedProductName, '.js-grid-column-name');
    }

    /**
     * @param int $expectedCount
     */
    public function assertFoundProductCount($expectedCount)
    {
        $foundProductCount = $this->tester->countVisibleByCss('tbody .table-grid__row');

        $message = 'Product advanced search expected to found ' . $expectedCount . ' products but found ' . $foundProductCount . '.';
        Assert::assertSame($expectedCount, $foundProductCount, $message);
    }
}
