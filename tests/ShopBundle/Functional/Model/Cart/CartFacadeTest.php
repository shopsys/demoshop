<?php

declare(strict_types=1);

namespace Tests\ShopBundle\Functional\Model\Cart;

use PHPUnit\Framework\MockObject\MockObject;
use Shopsys\FrameworkBundle\Component\Domain\Domain;
use Shopsys\FrameworkBundle\Component\Money\Money;
use Shopsys\FrameworkBundle\Model\Cart\Cart;
use Shopsys\FrameworkBundle\Model\Cart\CartFacade;
use Shopsys\FrameworkBundle\Model\Cart\CartFactory;
use Shopsys\FrameworkBundle\Model\Cart\CartRepository;
use Shopsys\FrameworkBundle\Model\Cart\Item\CartItemFactoryInterface;
use Shopsys\FrameworkBundle\Model\Cart\Watcher\CartWatcherFacade;
use Shopsys\FrameworkBundle\Model\Customer\CurrentCustomer;
use Shopsys\FrameworkBundle\Model\Customer\CustomerIdentifier;
use Shopsys\FrameworkBundle\Model\Customer\CustomerIdentifierFactory;
use Shopsys\FrameworkBundle\Model\Order\PromoCode\CurrentPromoCodeFacade;
use Shopsys\FrameworkBundle\Model\Product\Pricing\ProductPriceCalculationForUser;
use Shopsys\FrameworkBundle\Model\Product\ProductRepository;
use Shopsys\ShopBundle\DataFixtures\Demo\ProductDataFixture;
use Shopsys\ShopBundle\Model\Product\Product;
use Tests\ShopBundle\Test\TransactionFunctionalTestCase;

class CartFacadeTest extends TransactionFunctionalTestCase
{
    /**
     * @var \Shopsys\FrameworkBundle\Model\Cart\CartFacade
     * @inject
     */
    private $cartFacade;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Cart\Item\CartItemFactoryInterface
     * @inject
     */
    private $cartItemFactory;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Localization\TranslatableListener
     * @inject
     */
    private $translatableListener;

    public function testAddProductToCartAddsItemsOnlyToCurrentCart(): void
    {
        $customerIdentifier = new CustomerIdentifier('secretSessionHash');
        $anotherCustomerIdentifier = new CustomerIdentifier('anotherSecretSessionHash');

        /** @var \Shopsys\ShopBundle\Model\Product\Product $product */
        $product = $this->getReference(ProductDataFixture::PRODUCT_PREFIX . '1');
        $productId = $product->getId();
        $quantity = 10;

        $cartFacade = $this->createCartFacade($customerIdentifier);

        $cartFacade->addProductToCart($productId, $quantity);

        $cart = $this->getCartByCustomerIdentifier($customerIdentifier);
        $cartItems = $cart->getItems();
        $product = array_pop($cartItems)->getProduct();
        $this->assertSame($productId, $product->getId(), 'Add correct product');

        $anotherCart = $this->getCartByCustomerIdentifier($anotherCustomerIdentifier);
        $this->assertSame(0, $anotherCart->getItemsCount(), 'Add only in their own cart');
    }

    public function testCannotAddUnsellableProductToCart(): void
    {
        /** @var \Shopsys\ShopBundle\Model\Product\Product $product */
        $product = $this->getReference(ProductDataFixture::PRODUCT_PREFIX . '6');
        $productId = $product->getId();
        $quantity = 1;

        $customerIdentifier = new CustomerIdentifier('secretSessionHash');
        $cartFacade = $this->createCartFacade($customerIdentifier);

        $this->expectException('\Shopsys\FrameworkBundle\Model\Product\Exception\ProductNotFoundException');
        $cartFacade->addProductToCart($productId, $quantity);

        $cart = $this->getCartByCustomerIdentifier($customerIdentifier);
        $cartItems = $cart->getItems();

        $this->assertEmpty($cartItems, 'Product add not suppressed');
    }

    public function testCanChangeCartItemsQuantities(): void
    {
        /** @var \Shopsys\ShopBundle\Model\Product\Product $product1 */
        $product1 = $this->getReference(ProductDataFixture::PRODUCT_PREFIX . '1');
        /** @var \Shopsys\ShopBundle\Model\Product\Product $product2 */
        $product2 = $this->getReference(ProductDataFixture::PRODUCT_PREFIX . '3');

        $customerIdentifier = new CustomerIdentifier('secretSessionHash');
        $cartFacade = $this->createCartFacade($customerIdentifier);

        $cartItem1 = $cartFacade->addProductToCart($product1->getId(), 1)->getCartItem();
        $cartItem2 = $cartFacade->addProductToCart($product2->getId(), 2)->getCartItem();

        $cartFacade->changeQuantities([
            $cartItem1->getId() => 5,
            $cartItem2->getId() => 9,
        ]);

        $cart = $this->getCartByCustomerIdentifier($customerIdentifier);
        foreach ($cart->getItems() as $cartItem) {
            if ($cartItem->getId() === $cartItem1->getId()) {
                $this->assertSame(5, $cartItem->getQuantity(), 'Correct change quantity product');
            } elseif ($cartItem->getId() === $cartItem2->getId()) {
                $this->assertSame(9, $cartItem->getQuantity(), 'Correct change quantity product');
            } else {
                $this->fail('Unexpected product in cart');
            }
        }
    }

    public function testCannotDeleteNonexistentCartItem(): void
    {
        $customerIdentifier = new CustomerIdentifier('secretSessionHash');

        /** @var \Shopsys\ShopBundle\Model\Product\Product $product */
        $product = $this->getReference(ProductDataFixture::PRODUCT_PREFIX . '1');
        $quantity = 1;

        $cartFacade = $this->createCartFacade($customerIdentifier);
        $cartFacade->addProductToCart($product->getId(), $quantity);

        $cart = $this->getCartByCustomerIdentifier($customerIdentifier);
        $cartItems = $cart->getItems();
        $cartItem = array_pop($cartItems);

        $this->expectException('\Shopsys\FrameworkBundle\Model\Cart\Exception\InvalidCartItemException');
        $cartFacade->deleteCartItem($cartItem->getId() + 1);
    }

    public function testCanDeleteCartItem(): void
    {
        // Set currentLocale in TranslatableListener as it done in real request
        // because CartWatcherFacade works with entity translations.
        $this->translatableListener->setCurrentLocale('cs');

        $customerIdentifier = new CustomerIdentifier('secretSessionHash');

        /** @var \Shopsys\ShopBundle\Model\Product\Product $product1 */
        $product1 = $this->getReference(ProductDataFixture::PRODUCT_PREFIX . '1');
        /** @var \Shopsys\ShopBundle\Model\Product\Product $product2 */
        $product2 = $this->getReference(ProductDataFixture::PRODUCT_PREFIX . '2');
        $quantity = 1;

        $cartFacade = $this->createCartFacade($customerIdentifier);
        $cartItem1 = $cartFacade->addProductToCart($product1->getId(), $quantity)->getCartItem();
        $cartItem2 = $cartFacade->addProductToCart($product2->getId(), $quantity)->getCartItem();

        $cartFacade->deleteCartItem($cartItem1->getId());

        $cart = $this->getCartByCustomerIdentifier($customerIdentifier);
        $cartItems = $cart->getItems();

        $this->assertArrayHasSameElements([$cartItem2], $cartItems);
    }

    /**
     * @dataProvider productCartDataProvider
     * @param int $productId
     * @param bool $cartShouldBeNull
     */
    public function testCartNotExistIfNoListableProductIsInCart(int $productId, bool $cartShouldBeNull): void
    {
        $product = $this->getReference(ProductDataFixture::PRODUCT_PREFIX . $productId);

        $cart = $this->cartFacade->getCartOfCurrentCustomerCreateIfNotExists();
        $cartItem = $this->cartItemFactory->create($cart, $product, 1, Money::create(10));
        $cart->addItem($cartItem);

        $this->getEntityManager()->persist($cartItem);
        $this->getEntityManager()->flush();

        $this->assertFalse($cart->isEmpty(), 'Cart should not be empty');

        $cart = $this->cartFacade->findCartOfCurrentCustomer();

        if ($cartShouldBeNull) {
            $this->assertNull($cart);
        } else {
            $this->assertEquals(1, $cart->getItemsCount());
        }
    }

    public function productCartDataProvider()
    {
        return [
            ['productId' => 1, 'cartShouldBeNull' => false],
            ['productId' => 34, 'cartShouldBeNull' => true], // not listable product

        ];
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Customer\CustomerIdentifier $customerIdentifier
     * @return \Shopsys\FrameworkBundle\Model\Cart\CartFacade
     */
    private function createCartFacade(CustomerIdentifier $customerIdentifier): CartFacade
    {
        return new CartFacade(
            $this->getEntityManager(),
            $this->getContainer()->get(CartFactory::class),
            $this->getContainer()->get(ProductRepository::class),
            $this->getCustomerIdentifierFactoryMock($customerIdentifier),
            $this->getContainer()->get(Domain::class),
            $this->getContainer()->get(CurrentCustomer::class),
            $this->getContainer()->get(CurrentPromoCodeFacade::class),
            $this->getContainer()->get(ProductPriceCalculationForUser::class),
            $this->getContainer()->get(CartItemFactoryInterface::class),
            $this->getContainer()->get(CartRepository::class),
            $this->getContainer()->get(CartWatcherFacade::class)
        );
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Customer\CustomerIdentifier $customerIdentifier
     * @return \Shopsys\FrameworkBundle\Model\Cart\Cart
     */
    private function getCartByCustomerIdentifier(CustomerIdentifier $customerIdentifier): Cart
    {
        $cartFacade = $this->createCartFacade($customerIdentifier);

        return $cartFacade->getCartByCustomerIdentifierCreateIfNotExists($customerIdentifier);
    }

    /**
     * @param array $expected
     * @param array $actual
     */
    private function assertArrayHasSameElements(array $expected, array $actual): void
    {
        foreach ($expected as $expectedElement) {
            $key = array_search($expectedElement, $actual, true);

            if ($key === false) {
                $this->fail('Actual array does not contain expected element: ' . var_export($expectedElement, true));
            }

            unset($actual[$key]);
        }

        if (!empty($actual)) {
            $this->fail('Actual array contains extra elements: ' . var_export($actual, true));
        }
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Customer\CustomerIdentifier $customerIdentifier
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    private function getCustomerIdentifierFactoryMock(CustomerIdentifier $customerIdentifier): MockObject
    {
        $customerIdentifierFactoryMock = $this->getMockBuilder(CustomerIdentifierFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $customerIdentifierFactoryMock->method('get')->willReturn($customerIdentifier);

        return $customerIdentifierFactoryMock;
    }

    /**
     * @return \Shopsys\ShopBundle\Model\Product\Product
     */
    private function createProduct(): Product
    {
        return $this->getReference(ProductDataFixture::PRODUCT_PREFIX . 1);
    }

    public function testCannotAddProductFloatQuantityToCart(): void
    {
        $product = $this->createProduct();

        $this->expectException('Shopsys\FrameworkBundle\Model\Cart\Exception\InvalidQuantityException');
        $this->cartFacade->addProductToCart($product->getId(), 1.1);
    }

    public function testCannotAddProductZeroQuantityToCart(): void
    {
        $product = $this->createProduct();

        $this->expectException('Shopsys\FrameworkBundle\Model\Cart\Exception\InvalidQuantityException');
        $this->cartFacade->addProductToCart($product->getId(), 0);
    }

    public function testCannotAddProductNegativeQuantityToCart(): void
    {
        $product = $this->createProduct();

        $this->expectException('Shopsys\FrameworkBundle\Model\Cart\Exception\InvalidQuantityException');
        $this->cartFacade->addProductToCart($product->getId(), -10);
    }

    public function testAddProductToCartMarksAddedProductAsNew(): void
    {
        $product = $this->createProduct();

        $result = $this->cartFacade->addProductToCart($product->getId(), 2);
        $this->assertTrue($result->getIsNew());
    }

    public function testAddProductToCartMarksRepeatedlyAddedProductAsNotNew(): void
    {
        $product = $this->createProduct();

        $this->cartFacade->addProductToCart($product->getId(), 1);
        $result = $this->cartFacade->addProductToCart($product->getId(), 2);
        $this->assertFalse($result->getIsNew());
    }

    public function testAddProductResultContainsAddedProductQuantity(): void
    {
        $product = $this->createProduct();

        $quantity = 2;
        $result = $this->cartFacade->addProductToCart($product->getId(), $quantity);
        $this->assertSame($quantity, $result->getAddedQuantity());
    }

    public function testAddProductResultDoesNotContainPreviouslyAddedProductQuantity(): void
    {
        $product = $this->createProduct();

        $cartFacade = $this->cartFacade;
        $cartFacade->addProductToCart($product->getId(), 1);
        $quantity = 2;

        $result = $cartFacade->addProductToCart($product->getId(), $quantity);
        $this->assertSame($quantity, $result->getAddedQuantity());
    }
}
