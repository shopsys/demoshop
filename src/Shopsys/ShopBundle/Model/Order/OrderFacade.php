<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Model\Order;

use Shopsys\FrameworkBundle\Model\Order\OrderFacade as BaseOrderFacade;
use Shopsys\ShopBundle\Model\Customer\BillingAddress;

/**
 * @property \Shopsys\ShopBundle\Model\Order\OrderRepository $orderRepository
 * @property \Shopsys\ShopBundle\Model\Customer\CustomerFacade $customerFacade
 * @property \Shopsys\ShopBundle\Model\Customer\CurrentCustomer $currentCustomer
 * @property \Shopsys\ShopBundle\Model\Order\OrderFactory $orderFactory
 * @method __construct(\Doctrine\ORM\EntityManagerInterface $em, \Shopsys\FrameworkBundle\Model\Order\OrderNumberSequenceRepository $orderNumberSequenceRepository, \Shopsys\ShopBundle\Model\Order\OrderRepository $orderRepository, \Shopsys\FrameworkBundle\Model\Order\OrderUrlGenerator $orderUrlGenerator, \Shopsys\FrameworkBundle\Model\Order\Status\OrderStatusRepository $orderStatusRepository, \Shopsys\FrameworkBundle\Model\Order\Mail\OrderMailFacade $orderMailFacade, \Shopsys\FrameworkBundle\Model\Order\OrderHashGeneratorRepository $orderHashGeneratorRepository, \Shopsys\FrameworkBundle\Component\Setting\Setting $setting, \Shopsys\FrameworkBundle\Model\Localization\Localization $localization, \Shopsys\FrameworkBundle\Model\Administrator\Security\AdministratorFrontSecurityFacade $administratorFrontSecurityFacade, \Shopsys\FrameworkBundle\Model\Order\PromoCode\CurrentPromoCodeFacade $currentPromoCodeFacade, \Shopsys\FrameworkBundle\Model\Cart\CartFacade $cartFacade, \Shopsys\ShopBundle\Model\Customer\CustomerFacade $customerFacade, \Shopsys\ShopBundle\Model\Customer\CurrentCustomer $currentCustomer, \Shopsys\FrameworkBundle\Model\Order\Preview\OrderPreviewFactory $orderPreviewFactory, \Shopsys\FrameworkBundle\Model\Order\Item\OrderProductFacade $orderProductFacade, \Shopsys\FrameworkBundle\Model\Heureka\HeurekaFacade $heurekaFacade, \Shopsys\FrameworkBundle\Component\Domain\Domain $domain, \Shopsys\ShopBundle\Model\Order\OrderFactory $orderFactory, \Shopsys\FrameworkBundle\Model\Order\OrderPriceCalculation $orderPriceCalculation, \Shopsys\FrameworkBundle\Model\Order\Item\OrderItemPriceCalculation $orderItemPriceCalculation, \Shopsys\FrameworkBundle\Model\Order\FrontOrderDataMapper $frontOrderDataMapper, \Shopsys\FrameworkBundle\Twig\NumberFormatterExtension $numberFormatterExtension, \Shopsys\FrameworkBundle\Model\Payment\PaymentPriceCalculation $paymentPriceCalculation, \Shopsys\FrameworkBundle\Model\Transport\TransportPriceCalculation $transportPriceCalculation, \Shopsys\FrameworkBundle\Model\Order\Item\OrderItemFactoryInterface $orderItemFactory)
 * @method \Shopsys\ShopBundle\Model\Order\Order createOrder(\Shopsys\ShopBundle\Model\Order\OrderData $orderData, \Shopsys\FrameworkBundle\Model\Order\Preview\OrderPreview $orderPreview, \Shopsys\ShopBundle\Model\Customer\User|null $user)
 * @method \Shopsys\ShopBundle\Model\Order\Order createOrderFromFront(\Shopsys\ShopBundle\Model\Order\OrderData $orderData)
 * @method sendHeurekaOrderInfo(\Shopsys\ShopBundle\Model\Order\Order $order, bool $disallowHeurekaVerifiedByCustomers)
 * @method \Shopsys\ShopBundle\Model\Order\Order edit(int $orderId, \Shopsys\ShopBundle\Model\Order\OrderData $orderData)
 * @method prefillFrontOrderData(\Shopsys\ShopBundle\Model\Order\FrontOrderData $orderData, \Shopsys\ShopBundle\Model\Customer\User $user)
 * @method \Shopsys\ShopBundle\Model\Order\Order[] getCustomerOrderList(\Shopsys\ShopBundle\Model\Customer\User $user)
 * @method \Shopsys\ShopBundle\Model\Order\Order[] getOrderListForEmailByDomainId(string $email, int $domainId)
 * @method \Shopsys\ShopBundle\Model\Order\Order getById(int $orderId)
 * @method \Shopsys\ShopBundle\Model\Order\Order getByUrlHashAndDomain(string $urlHash, int $domainId)
 * @method \Shopsys\ShopBundle\Model\Order\Order getByOrderNumberAndUser(string $orderNumber, \Shopsys\ShopBundle\Model\Customer\User $user)
 * @method setOrderDataAdministrator(\Shopsys\ShopBundle\Model\Order\OrderData $orderData)
 * @method fillOrderItems(\Shopsys\ShopBundle\Model\Order\Order $order, \Shopsys\FrameworkBundle\Model\Order\Preview\OrderPreview $orderPreview)
 * @method fillOrderProducts(\Shopsys\ShopBundle\Model\Order\Order $order, \Shopsys\FrameworkBundle\Model\Order\Preview\OrderPreview $orderPreview, string $locale)
 * @method fillOrderPayment(\Shopsys\ShopBundle\Model\Order\Order $order, \Shopsys\FrameworkBundle\Model\Order\Preview\OrderPreview $orderPreview, string $locale)
 * @method fillOrderTransport(\Shopsys\ShopBundle\Model\Order\Order $order, \Shopsys\FrameworkBundle\Model\Order\Preview\OrderPreview $orderPreview, string $locale)
 * @method fillOrderRounding(\Shopsys\ShopBundle\Model\Order\Order $order, \Shopsys\FrameworkBundle\Model\Order\Preview\OrderPreview $orderPreview, string $locale)
 * @method refreshOrderItemsWithoutTransportAndPayment(\Shopsys\ShopBundle\Model\Order\Order $order, \Shopsys\ShopBundle\Model\Order\OrderData $orderData)
 * @method calculateOrderItemDataPrices(\Shopsys\ShopBundle\Model\Order\Item\OrderItemData $orderItemData)
 */
class OrderFacade extends BaseOrderFacade
{
    /**
     * @param \Shopsys\ShopBundle\Model\Customer\User[] $users
     * @return \Shopsys\ShopBundle\Model\Order\Order[]
     */
    public function getOrderListByCustomers(array $users)
    {
        return $this->orderRepository->getOrderListByCustomers($users);
    }

    /**
     * @param string $orderNumber
     * @param \Shopsys\ShopBundle\Model\Customer\BillingAddress $billingAddress
     * @return \Shopsys\ShopBundle\Model\Order\Order
     */
    public function getByOrderNumberAndBillingAddress($orderNumber, BillingAddress $billingAddress)
    {
        return $this->orderRepository->getByOrderNumberAndBillingAddress($orderNumber, $billingAddress);
    }
}
