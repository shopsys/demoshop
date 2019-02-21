<?php

namespace Tests\ShopBundle\Functional\Model\Cart\Watcher;

use Shopsys\FrameworkBundle\Component\Domain\Domain;
use Shopsys\FrameworkBundle\DataFixtures\Demo\PricingGroupDataFixture;
use Shopsys\FrameworkBundle\DataFixtures\Demo\ProductDataFixture;
use Shopsys\FrameworkBundle\Model\Cart\CartFacade;
use Shopsys\FrameworkBundle\Model\Cart\CartFactory;
use Shopsys\FrameworkBundle\Model\Cart\CartRepository;
use Shopsys\FrameworkBundle\Model\Cart\Item\CartItem;
use Shopsys\FrameworkBundle\Model\Cart\Item\CartItemFactory;
use Shopsys\FrameworkBundle\Model\Cart\Watcher\CartWatcher;
use Shopsys\FrameworkBundle\Model\Cart\Watcher\CartWatcherFacade;
use Shopsys\FrameworkBundle\Model\Customer\CurrentCustomer;
use Shopsys\FrameworkBundle\Model\Customer\CustomerIdentifier;
use Shopsys\FrameworkBundle\Model\Customer\CustomerIdentifierFactory;
use Shopsys\FrameworkBundle\Model\Order\PromoCode\CurrentPromoCodeFacade;
use Shopsys\FrameworkBundle\Model\Pricing\Vat\Vat;
use Shopsys\FrameworkBundle\Model\Pricing\Vat\VatData;
use Shopsys\FrameworkBundle\Model\Product\Pricing\ProductManualInputPriceFacade;
use Shopsys\FrameworkBundle\Model\Product\Pricing\ProductPriceCalculationForUser;
use Shopsys\FrameworkBundle\Model\Product\ProductCategoryDomainFactory;
use Shopsys\FrameworkBundle\Model\Product\ProductRepository;
use Shopsys\FrameworkBundle\Model\Product\ProductVisibility;
use Shopsys\FrameworkBundle\Model\Product\ProductVisibilityRepository;
use Shopsys\ShopBundle\Model\Product\Product;
use Shopsys\ShopBundle\Model\Product\ProductDataFactory;
use Tests\ShopBundle\Test\TransactionFunctionalTestCase;

class CartWatcherTest extends TransactionFunctionalTestCase
{
    public function testGetModifiedPriceItemsAndUpdatePrices()
    {
        $customerIdentifier = new CustomerIdentifier('randomString');

        $cart = $this->getCartByCustomerIdentifier($customerIdentifier);

        /** @var \Shopsys\ShopBundle\Model\Product\Product $product */
        $product = $this->getReference(ProductDataFixture::PRODUCT_PREFIX . '1');

        /* @var \Shopsys\FrameworkBundle\Model\Product\Pricing\ProductPriceCalculationForUser $productPriceCalculationForUser */
        $productPriceCalculationForUser = $this->getContainer()->get(ProductPriceCalculationForUser::class);

        $productPrice = $productPriceCalculationForUser->calculatePriceForCurrentUser($product);
        $cartItem = new CartItem($cart, $product, 1, $productPrice->getPriceWithVat());
        $cart->addItem($cartItem);

        /** @var \Shopsys\FrameworkBundle\Model\Cart\Watcher\CartWatcher $cartWatcher */
        $cartWatcher = $this->getContainer()->get(CartWatcher::class);

        $modifiedItems1 = $cartWatcher->getModifiedPriceItemsAndUpdatePrices($cart);
        $this->assertEmpty($modifiedItems1);

        $pricingGroup = $this->getReference(PricingGroupDataFixture::PRICING_GROUP_ORDINARY_DOMAIN_1);

        /** @var \Shopsys\FrameworkBundle\Model\Product\Pricing\ProductManualInputPriceFacade $manualInputPriceFacade */
        $manualInputPriceFacade = $this->getContainer()->get(ProductManualInputPriceFacade::class);
        $manualInputPriceFacade->refresh($product, $pricingGroup, '10');

        $modifiedItems2 = $cartWatcher->getModifiedPriceItemsAndUpdatePrices($cart);
        $this->assertNotEmpty($modifiedItems2);

        $modifiedItems3 = $cartWatcher->getModifiedPriceItemsAndUpdatePrices($cart);
        $this->assertEmpty($modifiedItems3);
    }

    public function testGetNotListableItemsWithItemWithoutProduct()
    {
        $customerIdentifier = new CustomerIdentifier('randomString');

        $cart = $this->getCartByCustomerIdentifier($customerIdentifier);

        $cartItemMock = $this->getMockBuilder(CartItem::class)
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();

        $expectedPricingGroup = $this->getReference(PricingGroupDataFixture::PRICING_GROUP_ORDINARY_DOMAIN_1);
        $currentCustomerMock = $this->getMockBuilder(CurrentCustomer::class)
            ->disableOriginalConstructor()
            ->setMethods(['getPricingGroup'])
            ->getMock();
        $currentCustomerMock
            ->expects($this->any())
            ->method('getPricingGroup')
            ->willReturn($expectedPricingGroup);

        $cart->addItem($cartItemMock);

        $cartWatcher = $this->getContainer()->get(CartWatcher::class);
        /* @var $cartWatcher \Shopsys\FrameworkBundle\Model\Cart\Watcher\CartWatcher */

        $notListableItems = $cartWatcher->getNotListableItems($cart, $currentCustomerMock);
        $this->assertCount(1, $notListableItems);
    }

    public function testGetNotListableItemsWithVisibleButNotSellableProduct()
    {
        $customerIdentifier = new CustomerIdentifier('randomString');
        $cart = $this->getCartByCustomerIdentifier($customerIdentifier);
        $productDataFactory = $this->getContainer()->get(ProductDataFactory::class);

        $productData = $productDataFactory->create();
        $productData->name = [];
        $productData->price = 100;
        $vatData = new VatData();
        $vatData->name = 'vat';
        $vatData->percent = 21;
        $productData->vat = new Vat($vatData);
        $product = Product::create($productData, new ProductCategoryDomainFactory());

        $cartItemMock = $this->getMockBuilder(CartItem::class)
            ->disableOriginalConstructor()
            ->setMethods(['getProduct'])
            ->getMock();
        $cartItemMock
            ->expects($this->any())
            ->method('getProduct')
            ->willReturn($product);

        $expectedPricingGroup = $this->getReference(PricingGroupDataFixture::PRICING_GROUP_ORDINARY_DOMAIN_1);
        $currentCustomerMock = $this->getMockBuilder(CurrentCustomer::class)
            ->disableOriginalConstructor()
            ->setMethods(['getPricingGroup'])
            ->getMock();
        $currentCustomerMock
            ->expects($this->any())
            ->method('getPricingGroup')
            ->willReturn($expectedPricingGroup);

        $productVisibilityMock = $this->getMockBuilder(ProductVisibility::class)
            ->disableOriginalConstructor()
            ->setMethods(['isVisible'])
            ->getMock();
        $productVisibilityMock
            ->expects($this->any())
            ->method('isVisible')
            ->willReturn(true);

        $productVisibilityRepositoryMock = $this->getMockBuilder(ProductVisibilityRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['getProductVisibility'])
            ->getMock();
        $productVisibilityRepositoryMock
            ->expects($this->any())
            ->method('getProductVisibility')
            ->willReturn($productVisibilityMock);

        $productPriceCalculationForUser = $this->getContainer()->get(ProductPriceCalculationForUser::class);
        $domain = $this->getContainer()->get(Domain::class);

        $cartWatcher = new CartWatcher($productPriceCalculationForUser, $productVisibilityRepositoryMock, $domain);

        $cart->addItem($cartItemMock);

        $notListableItems = $cartWatcher->getNotListableItems($cart, $currentCustomerMock);
        $this->assertCount(1, $notListableItems);
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Customer\CustomerIdentifier $customerIdentifier
     * @return \Shopsys\FrameworkBundle\Model\Cart\Cart
     */
    private function getCartByCustomerIdentifier(CustomerIdentifier $customerIdentifier)
    {
        $cartFacade = $this->createCartFacade($customerIdentifier);

        return $cartFacade->getCartByCustomerIdentifierCreateIfNotExists($customerIdentifier);
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Customer\CustomerIdentifier $customerIdentifier
     * @return \Shopsys\FrameworkBundle\Model\Cart\CartFacade
     */
    private function createCartFacade(CustomerIdentifier $customerIdentifier)
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
            $this->getContainer()->get(CartItemFactory::class),
            $this->getContainer()->get(CartRepository::class),
            $this->getContainer()->get(CartWatcherFacade::class)
        );
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Customer\CustomerIdentifier $customerIdentifier
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    private function getCustomerIdentifierFactoryMock(CustomerIdentifier $customerIdentifier)
    {
        $customerIdentifierFactoryMock = $this->getMockBuilder(CustomerIdentifierFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $customerIdentifierFactoryMock->method('get')->willReturn($customerIdentifier);

        return $customerIdentifierFactoryMock;
    }
}
