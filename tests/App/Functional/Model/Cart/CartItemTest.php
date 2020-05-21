<?php

declare(strict_types=1);

namespace Tests\App\Functional\Model\Cart;

use App\DataFixtures\Demo\UnitDataFixture;
use App\Model\Product\Product;
use Shopsys\FrameworkBundle\Component\Money\Money;
use Shopsys\FrameworkBundle\Model\Cart\Cart;
use Shopsys\FrameworkBundle\Model\Cart\Item\CartItem;
use Shopsys\FrameworkBundle\Model\Customer\CustomerIdentifier;
use Shopsys\FrameworkBundle\Model\Product\Availability\Availability;
use Shopsys\FrameworkBundle\Model\Product\Availability\AvailabilityData;
use Tests\App\Test\TransactionFunctionalTestCase;

class CartItemTest extends TransactionFunctionalTestCase
{
    /**
     * @var \Shopsys\FrameworkBundle\Model\Product\ProductDataFactoryInterface
     * @inject
     */
    private $productDataFactory;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Pricing\Vat\VatFacade
     * @inject
     */
    private $vatFacade;

    public function testIsSimilarItemAs()
    {
        $em = $this->getEntityManager();

        $customerIdentifier = new CustomerIdentifier('randomString');

        $availabilityData = new AvailabilityData();
        $availabilityData->dispatchTime = 0;
        $availability = new Availability($availabilityData);
        /** @var \App\Model\Product\ProductData $productData */
        $productData = $this->productDataFactory->create();
        $productData->name = [];
        $productData->price = 100;
        $productData->availability = $availability;
        $productData->unit = $this->getReference(UnitDataFixture::UNIT_PIECES);

        $productVatsIndexedByDomainId = [];
        foreach ($this->domain->getAllIds() as $domainId) {
            $productVatsIndexedByDomainId[$domainId] = $this->vatFacade->getDefaultVatForDomain($domainId);
        }
        $productData->vatsIndexedByDomainId = $productVatsIndexedByDomainId;

        $product1 = Product::create($productData);
        $product2 = Product::create($productData);
        $em->persist($availability);
        $em->persist($product1);
        $em->persist($product2);
        $em->flush();

        $cart = new Cart($customerIdentifier->getCartIdentifier());

        $cartItem1 = new CartItem($cart, $product1, 1, Money::zero());
        $cartItem2 = new CartItem($cart, $product1, 3, Money::zero());
        $cartItem3 = new CartItem($cart, $product2, 1, Money::zero());

        $this->assertTrue($cartItem1->isSimilarItemAs($cartItem2));
        $this->assertFalse($cartItem1->isSimilarItemAs($cartItem3));
    }
}
