<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Component\Router\DomainContextSwitcher;
use App\Model\Customer\BillingAddress;
use App\Model\Customer\BillingAddressDataFactory;
use App\Model\Customer\Exception\BillingAddressNotFoundException;
use App\Model\Customer\Exception\DuplicateEmailsException;
use App\Model\Customer\User\CustomerUserFacade;
use App\Model\Customer\User\CustomerUserUpdateData;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Shopsys\FrameworkBundle\Component\Domain\AdminDomainTabsFacade;
use Shopsys\FrameworkBundle\Component\Domain\Domain;
use Shopsys\FrameworkBundle\Component\Grid\GridFactory;
use Shopsys\FrameworkBundle\Component\Grid\QueryBuilderDataSource;
use Shopsys\FrameworkBundle\Component\Router\DomainRouterFactory;
use Shopsys\FrameworkBundle\Component\Router\Security\Annotation\CsrfProtection;
use Shopsys\FrameworkBundle\Controller\Admin\CustomerController as BaseCustomerController;
use Shopsys\FrameworkBundle\Controller\Admin\LoginController;
use Shopsys\FrameworkBundle\Form\Admin\Customer\User\CustomerUserUpdateFormType;
use Shopsys\FrameworkBundle\Form\Admin\QuickSearch\QuickSearchFormData;
use Shopsys\FrameworkBundle\Form\Admin\QuickSearch\QuickSearchFormType;
use Shopsys\FrameworkBundle\Model\Administrator\AdministratorGridFacade;
use Shopsys\FrameworkBundle\Model\AdminNavigation\BreadcrumbOverrider;
use Shopsys\FrameworkBundle\Model\Customer\BillingAddressFacade;
use Shopsys\FrameworkBundle\Model\Customer\DeliveryAddressDataFactory;
use Shopsys\FrameworkBundle\Model\Customer\DeliveryAddressFactory;
use Shopsys\FrameworkBundle\Model\Customer\User\CustomerUser;
use Shopsys\FrameworkBundle\Model\Customer\User\CustomerUserDataFactoryInterface;
use Shopsys\FrameworkBundle\Model\Customer\User\CustomerUserFactory;
use Shopsys\FrameworkBundle\Model\Customer\User\CustomerUserListAdminFacade;
use Shopsys\FrameworkBundle\Model\Customer\User\CustomerUserUpdateDataFactoryInterface;
use Shopsys\FrameworkBundle\Model\Order\OrderFacade;
use Shopsys\FrameworkBundle\Model\Security\LoginAsUserFacade;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CustomerController extends BaseCustomerController
{
    /**
     * @var \Shopsys\FrameworkBundle\Model\Customer\User\CustomerUserListAdminFacade
     */
    protected $customerUserListAdminFacade;

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
     * @var \App\Model\Customer\User\CustomerUserFacade
     */
    protected $customerUserFacade;

    /**
     * @var \App\Model\Customer\User\CustomerUserUpdateDataFactory
     */
    protected $customerUserUpdateDataFactory;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Customer\BillingAddressFacade
     */
    protected $billingAddressFacade;

    /**
     * @var \App\Model\Customer\BillingAddressDataFactory
     */
    protected $billingAddressDataFactory;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Customer\User\CustomerUserFactory
     */
    protected $customerUserFactory;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Customer\User\CustomerUserDataFactory
     */
    protected $customerUserDataFactory;

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
     * @var \App\Model\Order\OrderFacade
     */
    protected $orderFacade;

    /**
     * @var \Shopsys\FrameworkBundle\Component\Router\DomainRouterFactory
     */
    protected $domainRouterFactory;

    /**
     * @var \App\Component\Router\DomainContextSwitcher
     */
    private $domainContextSwitcher;

    /**
     * @param \App\Model\Customer\User\CustomerUserDataFactory $customerUserDataFactory
     * @param \Shopsys\FrameworkBundle\Model\Customer\User\CustomerUserListAdminFacade $customerUserListAdminFacade
     * @param \App\Model\Customer\User\CustomerUserFacade $customerUserFacade
     * @param \Shopsys\FrameworkBundle\Model\AdminNavigation\BreadcrumbOverrider $breadcrumbOverrider
     * @param \Shopsys\FrameworkBundle\Model\Administrator\AdministratorGridFacade $administratorGridFacade
     * @param \Shopsys\FrameworkBundle\Component\Grid\GridFactory $gridFactory
     * @param \Shopsys\FrameworkBundle\Component\Domain\AdminDomainTabsFacade $adminDomainTabsFacade
     * @param \App\Model\Order\OrderFacade $orderFacade
     * @param \Shopsys\FrameworkBundle\Model\Security\LoginAsUserFacade $loginAsUserFacade
     * @param \Shopsys\FrameworkBundle\Component\Router\DomainRouterFactory $domainRouterFactory
     * @param \App\Model\Customer\User\CustomerUserUpdateDataFactory $customerUserUpdateDataFactory
     * @param \Shopsys\FrameworkBundle\Model\Customer\BillingAddressFacade $billingAddressFacade
     * @param \App\Model\Customer\BillingAddressDataFactory $billingAddressDataFactory
     * @param \Shopsys\FrameworkBundle\Model\Customer\User\CustomerUserFactory $customerUserFactory
     * @param \Shopsys\FrameworkBundle\Model\Customer\DeliveryAddressDataFactory $deliveryAddressDataFactory
     * @param \Shopsys\FrameworkBundle\Model\Customer\DeliveryAddressFactory $deliveryAddressFactory
     * @param \App\Component\Router\DomainContextSwitcher $domainContextSwitcher
     */
    public function __construct(
        CustomerUserDataFactoryInterface $customerUserDataFactory,
        CustomerUserListAdminFacade $customerUserListAdminFacade,
        CustomerUserFacade $customerUserFacade,
        BreadcrumbOverrider $breadcrumbOverrider,
        AdministratorGridFacade $administratorGridFacade,
        GridFactory $gridFactory,
        AdminDomainTabsFacade $adminDomainTabsFacade,
        OrderFacade $orderFacade,
        LoginAsUserFacade $loginAsUserFacade,
        DomainRouterFactory $domainRouterFactory,
        CustomerUserUpdateDataFactoryInterface $customerUserUpdateDataFactory,
        BillingAddressFacade $billingAddressFacade,
        BillingAddressDataFactory $billingAddressDataFactory,
        CustomerUserFactory $customerUserFactory,
        DeliveryAddressDataFactory $deliveryAddressDataFactory,
        DeliveryAddressFactory $deliveryAddressFactory,
        DomainContextSwitcher $domainContextSwitcher
    ) {
        parent::__construct($customerUserDataFactory, $customerUserListAdminFacade, $customerUserFacade, $breadcrumbOverrider, $administratorGridFacade, $gridFactory, $adminDomainTabsFacade, $orderFacade, $loginAsUserFacade, $domainRouterFactory, $customerUserUpdateDataFactory);

        $this->customerUserListAdminFacade = $customerUserListAdminFacade;
        $this->adminDomainTabsFacade = $adminDomainTabsFacade;
        $this->gridFactory = $gridFactory;
        $this->administratorGridFacade = $administratorGridFacade;
        $this->customerUserFacade = $customerUserFacade;
        $this->customerUserUpdateDataFactory = $customerUserUpdateDataFactory;
        $this->billingAddressFacade = $billingAddressFacade;
        $this->billingAddressDataFactory = $billingAddressDataFactory;
        $this->deliveryAddressDataFactory = $deliveryAddressDataFactory;
        $this->deliveryAddressFactory = $deliveryAddressFactory;
        $this->customerUserDataFactory = $customerUserDataFactory;
        $this->customerUserFactory = $customerUserFactory;
        $this->breadcrumbOverrider = $breadcrumbOverrider;
        $this->orderFacade = $orderFacade;
        $this->domainRouterFactory = $domainRouterFactory;
        $this->domainContextSwitcher = $domainContextSwitcher;
    }

    /**
     * @Route("/customer/new/")
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function newAction(Request $request)
    {
        $customerUserUpdateData = $this->customerUserUpdateDataFactory->create();
        $selectedDomainId = $this->adminDomainTabsFacade->getSelectedDomainId();
        $customerUserData = $this->customerUserDataFactory->createForDomainId($selectedDomainId);
        $customerUserUpdateData->customerUserData = $customerUserData;

        $form = $this->createForm(CustomerUserUpdateFormType::class, $customerUserUpdateData, [
            'customerUser' => null,
            'domain_id' => $selectedDomainId,
            'billingAddress' => null,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $customerUserUpdateData = $form->getData();
            $customerUser = $this->customerUserFacade->create($customerUserUpdateData);

            $this->addSuccessFlashTwig(
                t('Customer <strong><a href="{{ url }}">{{ name }}</a></strong> created'),
                [
                    'name' => $customerUser->getFullName(),
                    'url' => $this->generateUrl('admin_customer_edit', ['billingAddressId' => $customerUser->getBillingAddress()->getId()]),
                ]
            );

            return $this->redirectToRoute('admin_customer_list');
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addErrorFlashTwig(t('Please check the correctness of all data filled.'));
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
            $this->addErrorFlash(t('Customer not found'));

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
        /* @var $administrator \App\Model\Administrator\Administrator */

        $quickSearchForm = $this->createForm(QuickSearchFormType::class, new QuickSearchFormData());
        $quickSearchForm->handleRequest($request);

        $queryBuilder = $this->customerUserListAdminFacade->getCustomerListQueryBuilderByQuickSearchData(
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

            $customerUser = $this->customerUserFacade->getUserByBillingAddress($billingAddress);

            $this->customerUserFacade->removeBillingAddress($billingAddress);

            $this->addSuccessFlashTwig(
                t('Customer <strong>{{ name }}</strong> deleted'),
                [
                    'name' => $customerUser->getFullName(),
                ]
            );
        } catch (\Shopsys\FrameworkBundle\Model\Customer\Exception\CustomerUserNotFoundException $ex) {
            $this->addErrorFlash(t('Selected customer doesn\'t exist.'));
        }

        return $this->redirectToRoute('admin_customer_list');
    }

    /**
     * @param \App\Model\Customer\BillingAddress $billingAddress
     * @param \App\Model\Customer\User\CustomerUserUpdateData $customerUserUpdateData
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function editCompanyWithMultipleUsers(BillingAddress $billingAddress, CustomerUserUpdateData $customerUserUpdateData)
    {
        $customerUser = $this->customerUserFacade->getUserByBillingAddress($billingAddress);

        if (!$customerUserUpdateData->billingAddressData->isCompanyWithMultipleUsers) {
            $this->customerUserFacade->removeCompanyUsersExceptTheFirstOne($billingAddress, $customerUserUpdateData);
        } else {
            $this->customerUserFacade->editCompanyWithMultipleUsers($billingAddress, $customerUserUpdateData);
        }

        $this->billingAddressFacade->edit($billingAddress->getId(), $customerUserUpdateData->billingAddressData);

        $this->addSuccessFlashTwig(
            t('Customer <strong><a href="{{ url }}">{{ name }}</a></strong> modified'),
            [
                'name' => $customerUser->getFullName(),
                'url' => $this->generateUrl('admin_customer_edit', [
                    'billingAddressId' => $billingAddress->getId(),
                ]),
            ]
        );

        return $this->redirectToRoute('admin_customer_list');
    }

    /**
     * @param \App\Model\Customer\BillingAddress $billingAddress
     * @param \App\Model\Customer\User\CustomerUserUpdateData $customerUserUpdateData
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function editStandardCustomer(BillingAddress $billingAddress, CustomerUserUpdateData $customerUserUpdateData)
    {
        $customerUser = $this->customerUserFacade->getUserByBillingAddress($billingAddress);

        $this->customerUserFacade->editByAdmin($customerUser->getId(), $customerUserUpdateData);

        $this->addSuccessFlashTwig(
            t('Customer <strong><a href="{{ url }}">{{ name }}</a></strong> modified'),
            [
                'name' => $customerUser->getFullName(),
                'url' => $this->generateUrl('admin_customer_edit', [
                    'billingAddressId' => $billingAddress->getId(),
                ]),
            ]
        );

        return $this->redirectToRoute('admin_customer_list');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Model\Customer\BillingAddress $billingAddress
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    protected function processCompanyWithMultipleUsers(Request $request, BillingAddress $billingAddress)
    {
        $customerUser = $this->customerUserFacade->getUserByBillingAddress($billingAddress);
        $customerUserUpdateData = $this->customerUserUpdateDataFactory->createFromUser($customerUser);

        $usersByBillingAddress = $this->customerUserFacade->getAllByBillingAddress($billingAddress);
        $customerUserUpdateData->companyUsersData = $this->customerUserDataFactory->createMultipleUserDataFromUsers($usersByBillingAddress);

        $form = $this->createForm(CustomerUserUpdateFormType::class, $customerUserUpdateData, [
            'customerUser' => $customerUser,
            'domain_id' => $this->adminDomainTabsFacade->getSelectedDomainId(),
            'billingAddress' => $billingAddress,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                return $this->editCompanyWithMultipleUsers($billingAddress, $customerUserUpdateData);
            } catch (DuplicateEmailsException $exc) {
                $this->addErrorFlashTwig(t('One or more emails are duplicated or already used, e.g.:') . ' ' . $exc->getEmail());
                $this->addErrorFlashTwig(t('Please check the correctness of all data filled.'));
            }
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addErrorFlashTwig(t('Please check the correctness of all data filled.'));
        }

        return $this->render('@ShopsysFramework/Admin/Content/Customer/edit.html.twig', [
            'form' => $form->createView(),
            'customerUser' => $customerUser,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Model\Customer\BillingAddress $billingAddress
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    protected function processStandardCustomer(Request $request, BillingAddress $billingAddress)
    {
        $customerUser = $this->customerUserFacade->getUserByBillingAddress($billingAddress);
        $customerUserUpdateData = $this->customerUserUpdateDataFactory->createFromUser($customerUser);

        $form = $this->createForm(CustomerUserUpdateFormType::class, $customerUserUpdateData, [
            'customerUser' => $customerUser,
            'domain_id' => $this->adminDomainTabsFacade->getSelectedDomainId(),
            'billingAddress' => null,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->editStandardCustomer($billingAddress, $customerUserUpdateData);
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addErrorFlashTwig(t('Please check the correctness of all data filled.'));
        }

        $this->breadcrumbOverrider->overrideLastItem(t('Editing customer - %name%', ['%name%' => $customerUser->getFullName()]));

        $orders = $this->orderFacade->getCustomerUserOrderList($customerUser);

        return $this->render('@ShopsysFramework/Admin/Content/Customer/edit.html.twig', [
            'form' => $form->createView(),
            'customerUser' => $customerUser,
            'orders' => $orders,
            'ssoLoginAsUserUrl' => $this->getSsoLoginAsUserUrl($customerUser),
        ]);
    }

    /**
     * @param \App\Model\Customer\User\CustomerUser $customerUser
     * @return string
     */
    protected function getSsoLoginAsUserUrl(CustomerUser $customerUser)
    {
        $this->domainContextSwitcher->changeRouterContext($customerUser->getDomainId());
        $loginAsUserUrl = $this->generateUrl(
            'admin_customer_loginasuser',
            [
                'userId' => $customerUser->getId(),
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $this->domainContextSwitcher->changeRouterContext(Domain::MAIN_ADMIN_DOMAIN_ID);
        $ssoLoginAsUserUrl = $this->generateUrl(
            'admin_login_sso',
            [
                LoginController::ORIGINAL_DOMAIN_ID_PARAMETER_NAME => $customerUser->getDomainId(),
                LoginController::ORIGINAL_REFERER_PARAMETER_NAME => $loginAsUserUrl,
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        return $ssoLoginAsUserUrl;
    }
}
