<?php

namespace SS6\ShopBundle\Tests\Acceptance\acceptance\PageObject\Front;

use Facebook\WebDriver\WebDriverBy;
use SS6\ShopBundle\Tests\Acceptance\acceptance\PageObject\AbstractPage;
use SS6\ShopBundle\Tests\Acceptance\acceptance\PageObject\Front\ProductListComponent;
use SS6\ShopBundle\Tests\Test\Codeception\AcceptanceTester;
use SS6\ShopBundle\Tests\Test\Codeception\Module\StrictWebDriver;

class ProductListPage extends AbstractPage {

	/**
	 * @var \SS6\ShopBundle\Tests\Acceptance\acceptance\PageObject\Front\ProductListComponent
	 */
	private $productListComponent;

	public function __construct(
		StrictWebDriver $strictWebDriver,
		AcceptanceTester $tester,
		ProductListComponent $productListComponent
	) {
		$this->productListComponent = $productListComponent;
		parent::__construct($strictWebDriver, $tester);
	}

	/**
	 * @param string $productName
	 * @param int $quantity
	 */
	public function addProductToCartByName($productName, $quantity = 1) {
		$context = $this->getProductListCompomentContext();

		$this->productListComponent->addProductToCartByName($productName, $quantity, $context);
	}

	/**
	 * @param int $expectedCount
	 */
	public function assertProductsTotalCount($expectedCount) {
		$totalCountElement = $this->getProductListCompomentContext()
			->findElement(WebDriverBy::cssSelector('.js-paging-total-count'));
		$actualCount = (int)trim($totalCountElement->getText());

		if ($expectedCount !== $actualCount) {
			$message = 'Product list expects ' . $expectedCount . ' products but contains ' . $actualCount . '.';
			throw new \PHPUnit_Framework_ExpectationFailedException($message);
		}
	}

	/**
	 * @return \Facebook\WebDriver\WebDriverElement
	 */
	private function getProductListCompomentContext() {
		return $this->webDriver->findElement(WebDriverBy::cssSelector('.web__main__content'));
	}

}
