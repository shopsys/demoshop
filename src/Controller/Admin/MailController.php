<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Shopsys\FrameworkBundle\Component\Domain\AdminDomainTabsFacade;
use Shopsys\FrameworkBundle\Controller\Admin\MailController as BaseMailController;
use Shopsys\FrameworkBundle\Model\AdminNavigation\BreadcrumbOverrider;
use Shopsys\FrameworkBundle\Model\Mail\Grid\MailTemplateGridFactory;
use Shopsys\FrameworkBundle\Model\Mail\MailTemplateConfiguration;
use Shopsys\FrameworkBundle\Model\Mail\MailTemplateDataFactory;
use Shopsys\FrameworkBundle\Model\Mail\MailTemplateFacade;
use Shopsys\FrameworkBundle\Model\Mail\Setting\MailSettingFacade;
use Symfony\Component\HttpFoundation\Response;

class MailController extends BaseMailController
{
    /**
     * @var bool
     */
    protected $mailerDisableDelivery;

    /**
     * @param bool $mailerDisableDelivery
     * @param \Shopsys\FrameworkBundle\Component\Domain\AdminDomainTabsFacade $adminDomainTabsFacade
     * @param \Shopsys\FrameworkBundle\Model\Mail\MailTemplateFacade $mailTemplateFacade
     * @param \Shopsys\FrameworkBundle\Model\Mail\Setting\MailSettingFacade $mailSettingFacade
     * @param \Shopsys\FrameworkBundle\Model\AdminNavigation\BreadcrumbOverrider $breadcrumbOverrider
     * @param \Shopsys\FrameworkBundle\Model\Mail\Grid\MailTemplateGridFactory $mailTemplateGridFactory
     * @param \Shopsys\FrameworkBundle\Model\Mail\MailTemplateConfiguration $mailTemplateConfiguration
     * @param \Shopsys\FrameworkBundle\Model\Mail\MailTemplateDataFactory $mailTemplateDataFactory
     */
    public function __construct(
        bool $mailerDisableDelivery,
        AdminDomainTabsFacade $adminDomainTabsFacade,
        MailTemplateFacade $mailTemplateFacade,
        MailSettingFacade $mailSettingFacade,
        BreadcrumbOverrider $breadcrumbOverrider,
        MailTemplateGridFactory $mailTemplateGridFactory,
        MailTemplateConfiguration $mailTemplateConfiguration,
        MailTemplateDataFactory $mailTemplateDataFactory
    ) {
        parent::__construct($adminDomainTabsFacade, $mailTemplateFacade, $mailSettingFacade, $breadcrumbOverrider, $mailTemplateGridFactory, $mailTemplateConfiguration, $mailTemplateDataFactory);

        $this->mailerDisableDelivery = $mailerDisableDelivery;
    }

    /**
     * @Route("/mail/template/")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function templateAction(): Response
    {
        if ($this->mailerDisableDelivery) {
            $this->addInfoFlashTwig(t('Email sending has been prevented so the demoshop is not abused for the spam distribution.'));
        }

        return parent::templateAction();
    }
}
