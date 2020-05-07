<?php

declare(strict_types=1);

namespace App\Model\Order;

use App\Model\Customer\BillingAddress;
use Shopsys\FrameworkBundle\Model\Order\OrderFacade as BaseOrderFacade;

/**
 * @property \App\Model\Order\OrderRepository $orderRepository
 * @property \App\Model\Customer\User\CustomerUserFacade $customerUserFacade
 * @property \App\Model\Customer\User\CurrentCustomerUser $currentCustomerUser
 * @property \App\Model\Order\OrderFactory $orderFactory
 * @method __construct(\Doctrine\ORM\EntityManagerInterface $em, \Shopsys\FrameworkBundle\Model\Order\OrderNumberSequenceRepository $orderNumberSequenceRepository, \App\Model\Order\OrderRepository $orderRepository, \Shopsys\FrameworkBundle\Model\Order\OrderUrlGenerator $orderUrlGenerator, \Shopsys\FrameworkBundle\Model\Order\Status\OrderStatusRepository $orderStatusRepository, \Shopsys\FrameworkBundle\Model\Order\Mail\OrderMailFacade $orderMailFacade, \Shopsys\FrameworkBundle\Model\Order\OrderHashGeneratorRepository $orderHashGeneratorRepository, \Shopsys\FrameworkBundle\Component\Setting\Setting $setting, \Shopsys\FrameworkBundle\Model\Localization\Localization $localization, \Shopsys\FrameworkBundle\Model\Administrator\Security\AdministratorFrontSecurityFacade $administratorFrontSecurityFacade, \Shopsys\FrameworkBundle\Model\Order\PromoCode\CurrentPromoCodeFacade $currentPromoCodeFacade, \Shopsys\FrameworkBundle\Model\Cart\CartFacade $cartFacade, \App\Model\Customer\User\CustomerUserFacade $customerUserFacade, \App\Model\Customer\User\CurrentCustomerUser $currentCustomerUser, \Shopsys\FrameworkBundle\Model\Order\Preview\OrderPreviewFactory $orderPreviewFactory, \Shopsys\FrameworkBundle\Model\Order\Item\OrderProductFacade $orderProductFacade, \Shopsys\FrameworkBundle\Model\Heureka\HeurekaFacade $heurekaFacade, \Shopsys\FrameworkBundle\Component\Domain\Domain $domain, \App\Model\Order\OrderFactory $orderFactory, \Shopsys\FrameworkBundle\Model\Order\OrderPriceCalculation $orderPriceCalculation, \Shopsys\FrameworkBundle\Model\Order\Item\OrderItemPriceCalculation $orderItemPriceCalculation, \Shopsys\FrameworkBundle\Model\Order\FrontOrderDataMapper $frontOrderDataMapper, \Shopsys\FrameworkBundle\Twig\NumberFormatterExtension $numberFormatterExtension, \Shopsys\FrameworkBundle\Model\Payment\PaymentPriceCalculation $paymentPriceCalculation, \Shopsys\FrameworkBundle\Model\Transport\TransportPriceCalculation $transportPriceCalculation, \Shopsys\FrameworkBundle\Model\Order\Item\OrderItemFactoryInterface $orderItemFactory)
 * @method \App\Model\Order\Order createOrder(\App\Model\Order\OrderData $orderData, \Shopsys\FrameworkBundle\Model\Order\Preview\OrderPreview $orderPreview, \App\Model\Customer\User\CustomerUser|null $customerUser)
 * @method \App\Model\Order\Order createOrderFromFront(\App\Model\Order\OrderData $orderData, ?\Shopsys\FrameworkBundle\Model\Customer\DeliveryAddress $deliveryAddress)
 * @method sendHeurekaOrderInfo(\App\Model\Order\Order $order, bool $disallowHeurekaVerifiedByCustomers)
 * @method \App\Model\Order\Order edit(int $orderId, \App\Model\Order\OrderData $orderData)
 * @method prefillFrontOrderData(\App\Model\Order\FrontOrderData $orderData, \App\Model\Customer\User\CustomerUser $customerUser)
 * @method \App\Model\Order\Order[] getCustomerUserOrderList(\App\Model\Customer\User\CustomerUser $customerUser)
 * @method \App\Model\Order\Order[] getOrderListForEmailByDomainId(string $email, int $domainId)
 * @method \App\Model\Order\Order getById(int $orderId)
 * @method \App\Model\Order\Order getByUrlHashAndDomain(string $urlHash, int $domainId)
 * @method \App\Model\Order\Order getByOrderNumberAndUser(string $orderNumber, \App\Model\Customer\User\CustomerUser $customerUser)
 * @method setOrderDataAdministrator(\App\Model\Order\OrderData $orderData)
 * @method fillOrderItems(\App\Model\Order\Order $order, \Shopsys\FrameworkBundle\Model\Order\Preview\OrderPreview $orderPreview)
 * @method fillOrderProducts(\App\Model\Order\Order $order, \Shopsys\FrameworkBundle\Model\Order\Preview\OrderPreview $orderPreview, string $locale)
 * @method fillOrderPayment(\App\Model\Order\Order $order, \Shopsys\FrameworkBundle\Model\Order\Preview\OrderPreview $orderPreview, string $locale)
 * @method fillOrderTransport(\App\Model\Order\Order $order, \Shopsys\FrameworkBundle\Model\Order\Preview\OrderPreview $orderPreview, string $locale)
 * @method fillOrderRounding(\App\Model\Order\Order $order, \Shopsys\FrameworkBundle\Model\Order\Preview\OrderPreview $orderPreview, string $locale)
 * @method refreshOrderItemsWithoutTransportAndPayment(\App\Model\Order\Order $order, \App\Model\Order\OrderData $orderData)
 * @method calculateOrderItemDataPrices(\App\Model\Order\Item\OrderItemData $orderItemData)
 */
class OrderFacade extends BaseOrderFacade
{
    /**
     * @param \App\Model\Customer\User\CustomerUser[] $customerUsers
     * @return \App\Model\Order\Order[]
     */
    public function getOrderListByCustomers(array $customerUsers)
    {
        return $this->orderRepository->getOrderListByCustomers($customerUsers);
    }

    /**
     * @param string $orderNumber
     * @param \App\Model\Customer\BillingAddress $billingAddress
     * @return \App\Model\Order\Order
     */
    public function getByOrderNumberAndBillingAddress($orderNumber, BillingAddress $billingAddress)
    {
        return $this->orderRepository->getByOrderNumberAndBillingAddress($orderNumber, $billingAddress);
    }
}
