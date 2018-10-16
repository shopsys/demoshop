<?php

namespace Shopsys\ShopBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Shopsys\FrameworkBundle\Component\Domain\AdminDomainTabsFacade;
use Shopsys\FrameworkBundle\Component\Grid\GridFactory;
use Shopsys\FrameworkBundle\Component\Grid\QueryBuilderDataSource;
use Shopsys\FrameworkBundle\Component\Router\DomainRouterFactory;
use Shopsys\FrameworkBundle\Component\Router\Security\Annotation\CsrfProtection;
use Shopsys\FrameworkBundle\Controller\Admin\CustomerController as BaseCustomerController;
use Shopsys\FrameworkBundle\Controller\Admin\LoginController;
use Shopsys\FrameworkBundle\Form\Admin\Customer\CustomerFormType;
use Shopsys\FrameworkBundle\Form\Admin\QuickSearch\QuickSearchFormData;
use Shopsys\FrameworkBundle\Form\Admin\QuickSearch\QuickSearchFormType;
use Shopsys\FrameworkBundle\Model\Administrator\AdministratorGridFacade;
use Shopsys\FrameworkBundle\Model\AdminNavigation\BreadcrumbOverrider;
use Shopsys\FrameworkBundle\Model\Customer\CustomerDataFactoryInterface;
use Shopsys\FrameworkBundle\Model\Customer\CustomerListAdminFacade;
use Shopsys\FrameworkBundle\Model\Customer\DeliveryAddressDataFactory;
use Shopsys\FrameworkBundle\Model\Customer\DeliveryAddressFactory;
use Shopsys\FrameworkBundle\Model\Customer\User;
use Shopsys\FrameworkBundle\Model\Customer\UserDataFactoryInterface;
use Shopsys\FrameworkBundle\Model\Customer\UserFactory;
use Shopsys\FrameworkBundle\Model\Order\OrderFacade;
use Shopsys\FrameworkBundle\Model\Security\LoginAsUserFacade;
use Shopsys\ShopBundle\Model\Customer\BillingAddress;
use Shopsys\ShopBundle\Model\Customer\BillingAddressDataFactory;
use Shopsys\ShopBundle\Model\Customer\BillingAddressFacade;
use Shopsys\ShopBundle\Model\Customer\CustomerData;
use Shopsys\ShopBundle\Model\Customer\CustomerFacade;
use Shopsys\ShopBundle\Model\Customer\Exception\BillingAddressNotFoundException;
use Shopsys\ShopBundle\Model\Customer\Exception\DuplicateEmailsException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CustomerController extends BaseCustomerController
{
    /**
     * @var \Shopsys\FrameworkBundle\Model\Customer\CustomerListAdminFacade
     */
    protected $customerListAdminFacade;

    /**
     * @var \Shopsys\FrameworkBundle\Component\Domain\AdminDomainTabsFacade
     */
    protected $adminDomainTabsFacade;

    /**
     * @var \Shopsys\FrameworkBundle\Component\Grid\GridFactory
     */
    protected $gridFactory;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Administrator\AdministratorGridFacade
     */
    protected $administratorGridFacade;

    /**
     * @var \Shopsys\ShopBundle\Model\Customer\CustomerFacade
     */
    protected $customerFacade;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Customer\CustomerDataFactoryInterface
     */
    protected $customerDataFactory;

    /**
     * @var \Shopsys\ShopBundle\Model\Customer\BillingAddressFacade
     */
    protected $billingAddressFacade;

    /**
     * @var \Shopsys\ShopBundle\Model\Customer\BillingAddressDataFactory
     */
    protected $billingAddressDataFactory;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Customer\UserFactory
     */
    protected $userFactory;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Customer\UserDataFactory
     */
    protected $userDataFactory;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Customer\DeliveryAddressDataFactory
     */
    protected $deliveryAddressDataFactory;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Customer\DeliveryAddressFactory
     */
    protected $deliveryAddressFactory;

    /**
     * @var \Shopsys\FrameworkBundle\Model\AdminNavigation\BreadcrumbOverrider
     */
    protected $breadcrumbOverrider;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Order\OrderFacade
     */
    protected $orderFacade;

    /**
     * @var \Shopsys\FrameworkBundle\Component\Router\DomainRouterFactory
     */
    protected $domainRouterFactory;

    public function __construct(
        UserDataFactoryInterface $userDataFactory,
        CustomerListAdminFacade $customerListAdminFacade,
        CustomerFacade $customerFacade,
        BreadcrumbOverrider $breadcrumbOverrider,
        AdministratorGridFacade $administratorGridFacade,
        GridFactory $gridFactory,
        AdminDomainTabsFacade $adminDomainTabsFacade,
        OrderFacade $orderFacade,
        LoginAsUserFacade $loginAsUserFacade,
        DomainRouterFactory $domainRouterFactory,
        CustomerDataFactoryInterface $customerDataFactory,
        BillingAddressFacade $billingAddressFacade,
        BillingAddressDataFactory $billingAddressDataFactory,
        UserFactory $userFactory,
        DeliveryAddressDataFactory $deliveryAddressDataFactory,
        DeliveryAddressFactory $deliveryAddressFactory
    ) {
        $this->customerListAdminFacade = $customerListAdminFacade;
        $this->adminDomainTabsFacade = $adminDomainTabsFacade;
        $this->gridFactory = $gridFactory;
        $this->administratorGridFacade = $administratorGridFacade;
        $this->customerFacade = $customerFacade;
        $this->customerDataFactory = $customerDataFactory;
        $this->billingAddressFacade = $billingAddressFacade;
        $this->billingAddressDataFactory = $billingAddressDataFactory;
        $this->deliveryAddressDataFactory = $deliveryAddressDataFactory;
        $this->deliveryAddressFactory = $deliveryAddressFactory;
        $this->userDataFactory = $userDataFactory;
        $this->userFactory = $userFactory;
        $this->breadcrumbOverrider = $breadcrumbOverrider;
        $this->orderFacade = $orderFacade;
        $this->domainRouterFactory = $domainRouterFactory;

        parent::__construct($userDataFactory, $customerListAdminFacade, $customerFacade, $breadcrumbOverrider, $administratorGridFacade, $gridFactory, $adminDomainTabsFacade, $orderFacade, $loginAsUserFacade, $domainRouterFactory, $customerDataFactory);
    }

    /**
     * @Route("/customer/new/")
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function newAction(Request $request)
    {
        $customerData = $this->customerDataFactory->create();
        $selectedDomainId = $this->adminDomainTabsFacade->getSelectedDomainId();
        $userData = $this->userDataFactory->createForDomainId($selectedDomainId);
        $customerData->userData = $userData;

        $form = $this->createForm(CustomerFormType::class, $customerData, [
            'user' => null,
            'domain_id' => $selectedDomainId,
            'billingAddress' => null,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $customerData = $form->getData();
            $user = $this->customerFacade->create($customerData);

            $this->getFlashMessageSender()->addSuccessFlashTwig(
                t('Customer <strong><a href="{{ url }}">{{ name }}</a></strong> created'),
                [
                    'name' => $user->getFullName(),
                    'url' => $this->generateUrl('admin_customer_edit', ['billingAddressId' => $user->getBillingAddress()->getId()]),
                ]
            );

            return $this->redirectToRoute('admin_customer_list');
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->getFlashMessageSender()->addErrorFlashTwig(t('Please check the correctness of all data filled.'));
        }

        return $this->render('@ShopsysFramework/Admin/Content/Customer/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/customer/edit/{billingAddressId}", requirements={"billingAddressId" = "\d+"})
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int $billingAddressId
     */
    public function editAction(Request $request, $billingAddressId)
    {
        try {
            $billingAddress = $this->billingAddressFacade->getById($billingAddressId);
        } catch (BillingAddressNotFoundException $ex) {
            $this->getFlashMessageSender()->addErrorFlash(t('Customer not found'));

            return $this->redirectToRoute('admin_customer_list');
        }

        if ($billingAddress->isCompanyWithMultipleUsers()) {
            return $this->processCompanyWithMultipleUsers($request, $billingAddress);
        }

        return $this->processStandardCustomer($request, $billingAddress);
    }

    /**
     * @Route("/customer/list/")
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function listAction(Request $request)
    {
        $administrator = $this->getUser();
        /* @var $administrator \Shopsys\FrameworkBundle\Model\Administrator\Administrator */

        $quickSearchForm = $this->createForm(QuickSearchFormType::class, new QuickSearchFormData());
        $quickSearchForm->handleRequest($request);

        $queryBuilder = $this->customerListAdminFacade->getCustomerListQueryBuilderByQuickSearchData(
            $this->adminDomainTabsFacade->getSelectedDomainId(),
            $quickSearchForm->getData()
        );

        $dataSource = new QueryBuilderDataSource($queryBuilder, 'ba.id');

        $grid = $this->gridFactory->create('customerList', $dataSource);
        $grid->enablePaging();
        $grid->setDefaultOrder('name');

        $grid->addColumn('name', 'name', t('Full name'), true);
        $grid->addColumn('street', 'street', t('Street'), true);
        $grid->addColumn('city', 'city', t('City'), true);

        $grid->setActionColumnClassAttribute('table-col table-col-10');
        $grid->addEditActionColumn('admin_customer_edit', ['billingAddressId' => 'id']);
        $grid->addDeleteActionColumn('admin_customer_delete', ['billingAddressId' => 'id'])
            ->setConfirmMessage(t('Do you really want to remove this customer?'));

        $grid->setTheme('@ShopsysFramework/Admin/Content/Customer/listGrid.html.twig');

        $this->administratorGridFacade->restoreAndRememberGridLimit($administrator, $grid);

        return $this->render('@ShopsysFramework/Admin/Content/Customer/list.html.twig', [
            'gridView' => $grid->createView(),
            'quickSearchForm' => $quickSearchForm->createView(),
        ]);
    }

    /**
     * @Route("/customer/delete/{billingAddressId}", requirements={"billingAddressId" = "\d+"})
     * @CsrfProtection
     * @param int $billingAddressId
     */
    public function deleteAction($billingAddressId)
    {
        try {
            $billingAddress = $this->billingAddressFacade->getById($billingAddressId);

            $user = $this->customerFacade->getUserByBillingAddress($billingAddress);

            $this->customerFacade->removeBillingAddress($billingAddress);

            $this->getFlashMessageSender()->addSuccessFlashTwig(
                t('Customer <strong>{{ name }}</strong> deleted'),
                [
                    'name' => $user->getFullName(),
                ]
            );
        } catch (\Shopsys\FrameworkBundle\Model\Customer\Exception\UserNotFoundException $ex) {
            $this->getFlashMessageSender()->addErrorFlash(t('Selected customer doesn\'t exist.'));
        }

        return $this->redirectToRoute('admin_customer_list');
    }

    /**
     * @param \Shopsys\ShopBundle\Model\Customer\BillingAddress $billingAddress
     * @param \Shopsys\ShopBundle\Model\Customer\CustomerData $customerData
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function editCompanyWithMultipleUsers(BillingAddress $billingAddress, CustomerData $customerData)
    {
        $user = $this->customerFacade->getUserByBillingAddress($billingAddress);

        if (!$customerData->billingAddressData->isCompanyWithMultipleUsers) {
            $this->customerFacade->removeCompanyUsersExceptTheFirstOne($billingAddress, $customerData);
        } else {
            $this->customerFacade->editCompanyWithMultipleUsers($billingAddress, $customerData);
        }

        $this->billingAddressFacade->edit($billingAddress->getId(), $customerData->billingAddressData);

        $this->getFlashMessageSender()->addSuccessFlashTwig(
            t('Customer <strong><a href="{{ url }}">{{ name }}</a></strong> modified'),
            [
                'name' => $user->getFullName(),
                'url' => $this->generateUrl('admin_customer_edit', [
                    'billingAddressId' => $billingAddress->getId(),
                ]),
            ]
        );

        return $this->redirectToRoute('admin_customer_list');
    }

    /**
     * @param \Shopsys\ShopBundle\Model\Customer\BillingAddress $billingAddress
     * @param \Shopsys\ShopBundle\Model\Customer\CustomerData $customerData
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function editStandardCustomer(BillingAddress $billingAddress, CustomerData $customerData)
    {
        $user = $this->customerFacade->getUserByBillingAddress($billingAddress);

        $this->customerFacade->editByAdmin($user->getId(), $customerData);

        $this->getFlashMessageSender()->addSuccessFlashTwig(
            t('Customer <strong><a href="{{ url }}">{{ name }}</a></strong> modified'),
            [
                'name' => $user->getFullName(),
                'url' => $this->generateUrl('admin_customer_edit', [
                    'billingAddressId' => $billingAddress->getId(),
                ]),
            ]
        );

        return $this->redirectToRoute('admin_customer_list');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Shopsys\ShopBundle\Model\Customer\BillingAddress $billingAddress
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    protected function processCompanyWithMultipleUsers(Request $request, BillingAddress $billingAddress)
    {
        $user = $this->customerFacade->getUserByBillingAddress($billingAddress);
        $customerData = $this->customerDataFactory->createFromUser($user);

        $usersByBillingAddress = $this->customerFacade->getAllByBillingAddress($billingAddress);
        $customerData->companyUsersData = $this->userDataFactory->createMultipleUserDataFromUsers($usersByBillingAddress);

        $form = $this->createForm(CustomerFormType::class, $customerData, [
            'user' => $user,
            'domain_id' => $this->adminDomainTabsFacade->getSelectedDomainId(),
            'billingAddress' => $billingAddress,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                return $this->editCompanyWithMultipleUsers($billingAddress, $customerData);
            } catch (DuplicateEmailsException $exc) {
                $this->getFlashMessageSender()->addErrorFlashTwig(t('One or more emails are duplicated or already used, e.g.: ' . $exc->getEmail()));
                $this->getFlashMessageSender()->addErrorFlashTwig(t('Please check the correctness of all data filled.'));
            }
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->getFlashMessageSender()->addErrorFlashTwig(t('Please check the correctness of all data filled.'));
        }

        return $this->render('@ShopsysFramework/Admin/Content/Customer/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Shopsys\ShopBundle\Model\Customer\BillingAddress $billingAddress
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    protected function processStandardCustomer(Request $request, BillingAddress $billingAddress)
    {
        $user = $this->customerFacade->getUserByBillingAddress($billingAddress);
        $customerData = $this->customerDataFactory->createFromUser($user);

        $form = $this->createForm(CustomerFormType::class, $customerData, [
            'user' => $user,
            'domain_id' => $this->adminDomainTabsFacade->getSelectedDomainId(),
            'billingAddress' => null,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->editStandardCustomer($billingAddress, $customerData);
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->getFlashMessageSender()->addErrorFlashTwig(t('Please check the correctness of all data filled.'));
        }

        $this->breadcrumbOverrider->overrideLastItem(t('Editing customer - %name%', ['%name%' => $user->getFullName()]));

        $orders = $this->orderFacade->getCustomerOrderList($user);

        return $this->render('@ShopsysFramework/Admin/Content/Customer/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'orders' => $orders,
            'ssoLoginAsUserUrl' => $this->getSsoLoginAsUserUrl($user),
        ]);
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Customer\User $user
     * @return string
     */
    protected function getSsoLoginAsUserUrl(User $user)
    {
        $loginAsUserUrl = $this->generateUrl(
            'admin_customer_loginasuser',
            [
                'userId' => $user->getId(),
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $ssoLoginAsUserUrl = $this->generateUrl(
            'admin_login_sso',
            [
                LoginController::ORIGINAL_DOMAIN_ID_PARAMETER_NAME => $user->getDomainId(),
                LoginController::ORIGINAL_REFERER_PARAMETER_NAME => $loginAsUserUrl,
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        return $ssoLoginAsUserUrl;
    }
}
