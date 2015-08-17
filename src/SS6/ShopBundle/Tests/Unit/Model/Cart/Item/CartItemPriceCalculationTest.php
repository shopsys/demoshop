<?php

namespace SS6\ShopBundle\Tests\Unit\Model\Cart\Item;

use SS6\ShopBundle\Model\Cart\Item\CartItem;
use SS6\ShopBundle\Model\Cart\Item\CartItemPriceCalculation;
use SS6\ShopBundle\Model\Customer\CustomerIdentifier;
use SS6\ShopBundle\Model\Pricing\Vat\Vat;
use SS6\ShopBundle\Model\Pricing\Vat\VatData;
use SS6\ShopBundle\Model\Product\Product;
use SS6\ShopBundle\Model\Product\ProductData;
use SS6\ShopBundle\Tests\Test\FunctionalTestCase;

class CartItemPriceCalculationTest extends FunctionalTestCase {

	public function testCalculatePrices() {
		$cartItemPriceCalculation = $this->getContainer()->get(CartItemPriceCalculation::class);
		/* @var $cartItemPriceCalculation \SS6\ShopBundle\Model\Cart\Item\CartItemPriceCalculation */

		$customerIdentifier = new CustomerIdentifier('randomString');

		$inputPrice = 11790;
		$quantity = 3;
		$vat = new Vat(new VatData('vat', 21));
		$product = new Product(new ProductData(['cs' => 'Product 1'], null, null, null, $inputPrice, $vat));

		$cartItem = new CartItem($customerIdentifier, $product, $quantity, null);

		$cartItemPrice = $cartItemPriceCalculation->calculatePrice($cartItem);

		$this->assertSame(round(11789.42, 6), round($cartItemPrice->getUnitPriceWithoutVat(), 6));
		$this->assertSame(round(14266, 6), round($cartItemPrice->getUnitPriceWithVat(), 6));
		$this->assertSame(round(2476.58, 6), round($cartItemPrice->getUnitPriceVatAmount(), 6));
		$this->assertSame(round(35368.27, 6), round($cartItemPrice->getTotalPriceWithoutVat(), 6));
		$this->assertSame(round(42798, 6), round($cartItemPrice->getTotalPriceWithVat(), 6));
		$this->assertSame(round(7429.73, 6), round($cartItemPrice->getTotalPriceVatAmount(), 6));
	}

}
