<?php

declare(strict_types=1);

namespace Tests\App\Functional\PersonalData;

use Shopsys\FrameworkBundle\Component\Domain\Domain;
use Shopsys\FrameworkBundle\Component\Money\Money;
use Shopsys\FrameworkBundle\Component\Xml\XmlNormalizer;
use Shopsys\FrameworkBundle\Model\Country\Country;
use Shopsys\FrameworkBundle\Model\Country\CountryData;
use Shopsys\FrameworkBundle\Model\Customer\BillingAddress;
use Shopsys\FrameworkBundle\Model\Customer\BillingAddressData;
use Shopsys\FrameworkBundle\Model\Customer\DeliveryAddress;
use Shopsys\FrameworkBundle\Model\Customer\DeliveryAddressData;
use Shopsys\FrameworkBundle\Model\Order\Item\OrderItem;
use Shopsys\FrameworkBundle\Model\Order\Status\OrderStatus;
use Shopsys\FrameworkBundle\Model\Pricing\Currency\Currency;
use Shopsys\FrameworkBundle\Model\Pricing\Currency\CurrencyData;
use Shopsys\FrameworkBundle\Model\Pricing\Price;
use Shopsys\FrameworkBundle\Model\Product\Product;
use App\Model\Customer\User;
use App\Model\Customer\UserData;
use App\Model\Order\Order;
use App\Model\Order\OrderData;
use Tests\App\Test\TransactionFunctionalTestCase;

class PersonalDataExportXmlTest extends TransactionFunctionalTestCase
{
    public const EMAIL = 'no-reply@shopsys.com';
    public const EXPECTED_XML_FILE_NAME = 'test.xml';
    public const DOMAIN_ID_FIRST = Domain::FIRST_DOMAIN_ID;

    public function testExportXml()
    {
        $country = $this->createCountry();
        $billingAddress = $this->createBillingAddress($country);
        $deliveryAddress = $this->createDeliveryAddress($country);
        $user = $this->createUser($billingAddress, $deliveryAddress);
        $status = $this->createMock(OrderStatus::class);
        $currencyData = new CurrencyData();
        $currencyData->name = 'CZK';
        $currencyData->code = 'CZK';
        $currency = new Currency($currencyData);
        $order = $this->createOrder($currency, $status, $country);
        $product = $this->createMock(Product::class);
        $price = new Price(Money::create(1), Money::create(1));
        $orderItem = new OrderItem($order, 'test', $price, 1, 1, OrderItem::TYPE_PRODUCT, 'ks', 'cat');
        $orderItem->setProduct($product);
        $order->addItem($orderItem);
        $order->setStatus($status);

        $twig = $this->getContainer()->get('twig');

        $generatedXml = $twig->render('@ShopsysShop/Front/Content/PersonalData/export.xml.twig', [
            'user' => $user,
            'orders' => [0 => $order],
            'newsletterSubscriber' => null,
        ]);

        $generatedXml = XmlNormalizer::normalizeXml($generatedXml);

        $expectedXml = file_get_contents(__DIR__ . '/Resources/' . self::EXPECTED_XML_FILE_NAME);
        $this->assertEquals($expectedXml, $generatedXml);
    }

    /**
     * @return \Shopsys\FrameworkBundle\Model\Country\Country
     */
    private function createCountry()
    {
        $countryData = new CountryData();
        $countryData->names = ['cz' => 'Czech Republic'];
        $countryData->code = 'CZ';
        $country = new Country($countryData);

        return $country;
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Country\Country $country
     * @return \Shopsys\FrameworkBundle\Model\Customer\BillingAddress
     */
    private function createBillingAddress(Country $country)
    {
        $billingAddressData = new BillingAddressData();
        $billingAddressData->country = $country;
        $billingAddressData->city = 'Ostrava';
        $billingAddressData->street = 'Hlubinská';
        $billingAddressData->companyCustomer = true;
        $billingAddressData->companyName = 'Shopsys';
        $billingAddressData->companyNumber = 123456;
        $billingAddressData->companyTaxNumber = 123456;
        $billingAddressData->postcode = 70200;

        $billingAddress = new BillingAddress($billingAddressData);

        return $billingAddress;
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Country\Country $country
     * @return \Shopsys\FrameworkBundle\Model\Customer\DeliveryAddress
     */
    private function createDeliveryAddress(Country $country)
    {
        $deliveryAddressData = new DeliveryAddressData();
        $deliveryAddressData->country = $country;
        $deliveryAddressData->telephone = '+420987654321';
        $deliveryAddressData->postcode = 70200;
        $deliveryAddressData->companyName = 'Shopsys';
        $deliveryAddressData->street = 'Hlubinská';
        $deliveryAddressData->city = 'Ostrava';
        $deliveryAddressData->lastName = 'Fero';
        $deliveryAddressData->firstName = 'Mrkva';
        $deliveryAddress = new DeliveryAddress($deliveryAddressData);

        return $deliveryAddress;
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Customer\BillingAddress $billingAddress
     * @param \Shopsys\FrameworkBundle\Model\Customer\DeliveryAddress $deliveryAddress
     * @return \App\Model\Customer\User
     */
    private function createUser(BillingAddress $billingAddress, DeliveryAddress $deliveryAddress)
    {
        $userData = new UserData();
        $userData->firstName = 'Jaromír';
        $userData->lastName = 'Jágr';
        $userData->domainId = self::DOMAIN_ID_FIRST;
        $userData->createdAt = new \DateTime('2018-04-13');
        $userData->email = 'no-reply@shopsys.com';
        $userData->telephone = '+420987654321';

        $user = new User($userData, $billingAddress, $deliveryAddress);

        return $user;
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Pricing\Currency\Currency $currency
     * @param \Shopsys\FrameworkBundle\Model\Order\Status\OrderStatus $status
     * @param \Shopsys\FrameworkBundle\Model\Country\Country $country
     * @return \App\Model\Order\Order
     */
    private function createOrder(Currency $currency, OrderStatus $status, Country $country)
    {
        $orderData = new OrderData();
        $orderData->currency = $currency;
        $orderData->status = $status;
        $orderData->email = 'no-reply@shopsys.com';
        $orderData->createdAt = new \DateTime('2018-04-13');
        $orderData->domainId = self::DOMAIN_ID_FIRST;
        $orderData->lastName = 'Bořič';
        $orderData->firstName = 'Adam';
        $orderData->city = 'Liberec';
        $orderData->street = 'Cihelní 5';
        $orderData->companyName = 'Shopsys';
        $orderData->postcode = 65421;
        $orderData->telephone = '+420987654321';
        $orderData->companyTaxNumber = 123456;
        $orderData->companyNumber = 123456;
        $orderData->deliveryAddressSameAsBillingAddress = true;
        $orderData->country = $country;

        $order = new Order($orderData, '1523596513', 'hash');

        return $order;
    }
}
