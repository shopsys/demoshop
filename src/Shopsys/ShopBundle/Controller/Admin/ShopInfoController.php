<?php

namespace Shopsys\ShopBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Shopsys\ShopBundle\Component\Controller\AdminBaseController;
use Shopsys\ShopBundle\Component\Domain\SelectedDomain;
use Shopsys\ShopBundle\Form\Admin\ShopInfo\ShopInfoSettingFormType;
use Shopsys\ShopBundle\Form\Admin\ShopInfo\ShopInfoSettingFormTypeFactory;
use Shopsys\ShopBundle\Model\ShopInfo\ShopInfoSettingFacade;
use Symfony\Component\HttpFoundation\Request;

class ShopInfoController extends AdminBaseController
{
    /**
     * @var \Shopsys\ShopBundle\Form\Admin\ShopInfo\ShopInfoSettingFormTypeFactory
     */
    private $shopInfoSettingFormTypeFactory;

    /**
     * @var \Shopsys\ShopBundle\Component\Domain\SelectedDomain
     */
    private $selectedDomain;

    /**
     * @var \Shopsys\ShopBundle\Model\ShopInfo\ShopInfoSettingFacade
     */
    private $shopInfoSettingFacade;

    public function __construct(
        ShopInfoSettingFormTypeFactory $shopInfoSettingFormTypeFactory,
        ShopInfoSettingFacade $shopInfoSettingFacade,
        SelectedDomain $selectedDomain
    ) {
        $this->shopInfoSettingFormTypeFactory = $shopInfoSettingFormTypeFactory;
        $this->shopInfoSettingFacade = $shopInfoSettingFacade;
        $this->selectedDomain = $selectedDomain;
    }

    /**
     * @Route("/shop-info/setting/")
     */
    public function settingAction(Request $request)
    {
        $selectedDomainId = $this->selectedDomain->getId();

        $form = $this->createForm(new ShopInfoSettingFormType());

        $shopInfoSettingData = [];
        $shopInfoSettingData['phoneNumber'] = $this->shopInfoSettingFacade->getPhoneNumber($selectedDomainId);
        $shopInfoSettingData['email'] = $this->shopInfoSettingFacade->getEmail($selectedDomainId);
        $shopInfoSettingData['phoneHours'] = $this->shopInfoSettingFacade->getPhoneHours($selectedDomainId);

        $form->setData($shopInfoSettingData);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $shopInfoSettingData = $form->getData();
            $this->shopInfoSettingFacade->setPhoneNumber($shopInfoSettingData['phoneNumber'], $selectedDomainId);
            $this->shopInfoSettingFacade->setEmail($shopInfoSettingData['email'], $selectedDomainId);
            $this->shopInfoSettingFacade->setPhoneHours($shopInfoSettingData['phoneHours'], $selectedDomainId);

            $this->getFlashMessageSender()->addSuccessFlash(t('E-shop attributes settings modified'));

            return $this->redirectToRoute('admin_shopinfo_setting');
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->getFlashMessageSender()->addErrorFlashTwig(t('Please check the correctness of all data filled.'));
        }

        return $this->render('@ShopsysShop/Admin/Content/ShopInfo/shopInfo.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}