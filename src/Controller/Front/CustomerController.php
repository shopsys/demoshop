<?php

declare(strict_types=1);

namespace App\Controller\Front;

use App\Form\Front\Customer\CustomerUserFormType;
use Shopsys\FrameworkBundle\Component\Domain\Domain;
use Shopsys\FrameworkBundle\Model\Customer\DeliveryAddressFacade;
use Shopsys\FrameworkBundle\Model\Customer\User\CustomerUserFacade;
use Shopsys\FrameworkBundle\Model\Customer\User\CustomerUserUpdateDataFactoryInterface;
use Shopsys\FrameworkBundle\Model\Order\Item\OrderItemPriceCalculation;
use Shopsys\FrameworkBundle\Model\Order\OrderFacade;
use Shopsys\FrameworkBundle\Model\Security\LoginAsUserFacade;
use Shopsys\FrameworkBundle\Model\Security\Roles;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerController extends FrontBaseController
{
    /**
     * @var \App\Model\Customer\User\CustomerUserFacade
     */
    private $customerUserFacade;

    /**
     * @var \Shopsys\FrameworkBundle\Component\Domain\Domain
     */
    private $domain;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Order\Item\OrderItemPriceCalculation
     */
    private $orderItemPriceCalculation;

    /**
     * @var \App\Model\Order\OrderFacade
     */
    private $orderFacade;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Security\LoginAsUserFacade
     */
    private $loginAsUserFacade;

    /**
     * @var \App\Model\Customer\User\CustomerUserUpdateDataFactory
     */
    private $customerUserUpdateDataFactory;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Customer\DeliveryAddressFacade
     */
    private $deliveryAddressFacade;

    /**
     * @param \Shopsys\FrameworkBundle\Model\Customer\User\CustomerUserFacade $customerUserFacade
     * @param \App\Model\Order\OrderFacade $orderFacade
     * @param \Shopsys\FrameworkBundle\Component\Domain\Domain $domain
     * @param \Shopsys\FrameworkBundle\Model\Order\Item\OrderItemPriceCalculation $orderItemPriceCalculation
     * @param \Shopsys\FrameworkBundle\Model\Security\LoginAsUserFacade $loginAsUserFacade
     * @param \App\Model\Customer\User\CustomerUserUpdateDataFactory $customerUserUpdateDataFactory
     * @param \Shopsys\FrameworkBundle\Model\Customer\DeliveryAddressFacade $deliveryAddressFacade
     */
    public function __construct(
        CustomerUserFacade $customerUserFacade,
        OrderFacade $orderFacade,
        Domain $domain,
        OrderItemPriceCalculation $orderItemPriceCalculation,
        LoginAsUserFacade $loginAsUserFacade,
        CustomerUserUpdateDataFactoryInterface $customerUserUpdateDataFactory,
        DeliveryAddressFacade $deliveryAddressFacade
    ) {
        $this->customerUserFacade = $customerUserFacade;
        $this->orderFacade = $orderFacade;
        $this->domain = $domain;
        $this->orderItemPriceCalculation = $orderItemPriceCalculation;
        $this->loginAsUserFacade = $loginAsUserFacade;
        $this->customerUserUpdateDataFactory = $customerUserUpdateDataFactory;
        $this->deliveryAddressFacade = $deliveryAddressFacade;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function editAction(Request $request)
    {
        if (!$this->isGranted(Roles::ROLE_LOGGED_CUSTOMER)) {
            $this->addErrorFlash(t('You have to be logged in to enter this page'));
            return $this->redirectToRoute('front_login');
        }

        $customerUser = $this->getUser();
        $customerUserUpdateData = $this->customerUserUpdateDataFactory->createFromUser($customerUser);
        $customerUserUpdateData->deliveryAddressData = null;

        $form = $this->createForm(CustomerUserFormType::class, $customerUserUpdateData, [
            'domain_id' => $this->domain->getId(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $customerUserUpdateData = $form->getData();

            $this->customerUserFacade->editByCustomerUser($customerUser->getId(), $customerUserUpdateData);

            $this->addSuccessFlash(t('Your data had been successfully updated'));
            return $this->redirectToRoute('front_customer_edit');
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addErrorFlash(t('Please check the correctness of all data filled.'));
        }

        return $this->render('Front/Content/Customer/edit.html.twig', [
            'form' => $form->createView(),
            'customerUser' => $customerUser,
        ]);
    }

    public function ordersAction()
    {
        if (!$this->isGranted(Roles::ROLE_LOGGED_CUSTOMER)) {
            $this->addErrorFlash(t('You have to be logged in to enter this page'));
            return $this->redirectToRoute('front_login');
        }

        $customerUser = $this->getUser();
        /* @var $customerUser \App\Model\Customer\User\CustomerUser */

        if ($customerUser->getBillingAddress()->isCompanyWithMultipleUsers()) {
            $customerUsers = $this->customerUserFacade->getUsersByBillingAddressAndDomain($customerUser->getBillingAddress(), $customerUser->getDomainId());
        } else {
            $customerUsers = [$customerUser];
        }

        $orders = $this->orderFacade->getOrderListByCustomers($customerUsers);
        return $this->render('Front/Content/Customer/orders.html.twig', [
            'orders' => $orders,
        ]);
    }

    /**
     * @param string $orderNumber
     */
    public function orderDetailRegisteredAction($orderNumber)
    {
        return $this->orderDetailAction(null, $orderNumber);
    }

    /**
     * @param string $urlHash
     */
    public function orderDetailUnregisteredAction($urlHash)
    {
        return $this->orderDetailAction($urlHash, null);
    }

    /**
     * @param string $urlHash
     * @param string $orderNumber
     */
    private function orderDetailAction($urlHash = null, $orderNumber = null)
    {
        if ($orderNumber !== null) {
            if (!$this->isGranted(Roles::ROLE_LOGGED_CUSTOMER)) {
                $this->addErrorFlash(t('You have to be logged in to enter this page'));
                return $this->redirectToRoute('front_login');
            }

            $customerUser = $this->getUser();
            try {
                if ($customerUser->getBillingAddress()->isCompanyWithMultipleUsers()) {
                    $order = $this->orderFacade->getByOrderNumberAndBillingAddress($orderNumber, $customerUser->getBillingAddress());
                /* @var $order \App\Model\Order\Order */
                } else {
                    $order = $this->orderFacade->getByOrderNumberAndUser($orderNumber, $customerUser);
                    /* @var $order \App\Model\Order\Order */
                }
            } catch (\Shopsys\FrameworkBundle\Model\Order\Exception\OrderNotFoundException $ex) {
                $this->addErrorFlash(t('Order not found'));
                return $this->redirectToRoute('front_customer_orders');
            }
        } else {
            $order = $this->orderFacade->getByUrlHashAndDomain($urlHash, $this->domain->getId());
            /* @var $order \App\Model\Order\Order */
        }

        $orderItemTotalPricesById = $this->orderItemPriceCalculation->calculateTotalPricesIndexedById($order->getItems());

        return $this->render('Front/Content/Customer/orderDetail.html.twig', [
            'order' => $order,
            'orderItemTotalPricesById' => $orderItemTotalPricesById,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAsRememberedUserAction(Request $request)
    {
        try {
            $this->loginAsUserFacade->loginAsRememberedUser($request);
        } catch (\Shopsys\FrameworkBundle\Model\Customer\Exception\CustomerUserNotFoundException $e) {
            $this->addErrorFlash(t('User not found.'));

            return $this->redirectToRoute('admin_customer_list');
        } catch (\Shopsys\FrameworkBundle\Model\Security\Exception\LoginAsRememberedUserException $e) {
            throw $this->createAccessDeniedException('', $e);
        }

        return $this->redirectToRoute('front_homepage');
    }

    /**
     * @param int $deliveryAddressId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteDeliveryAddressAction(int $deliveryAddressId)
    {
        if (!$this->isGranted(Roles::ROLE_LOGGED_CUSTOMER)) {
            throw $this->createAccessDeniedException('');
        }

        /** @var \App\Model\Customer\User\CustomerUser $customerUser */
        $customerUser = $this->getUser();

        $deliveryAddress = $this->deliveryAddressFacade->getById($deliveryAddressId);

        if (in_array($deliveryAddress, $customerUser->getCustomer()->getDeliveryAddresses(), true)) {
            $this->deliveryAddressFacade->delete($deliveryAddressId);

            return Response::create('OK');
        } else {
            throw $this->createAccessDeniedException('');
        }
    }
}
