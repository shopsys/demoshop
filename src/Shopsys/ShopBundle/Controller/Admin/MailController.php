<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Shopsys\FrameworkBundle\Component\Domain\AdminDomainTabsFacade;
use Shopsys\FrameworkBundle\Controller\Admin\MailController as BaseMailController;
use Shopsys\FrameworkBundle\Model\Customer\Mail\RegistrationMail;
use Shopsys\FrameworkBundle\Model\Customer\Mail\ResetPasswordMail;
use Shopsys\FrameworkBundle\Model\Mail\MailTemplateFacade;
use Shopsys\FrameworkBundle\Model\Mail\Setting\MailSettingFacade;
use Shopsys\FrameworkBundle\Model\Order\Mail\OrderMail;
use Shopsys\FrameworkBundle\Model\Order\Status\OrderStatusFacade;
use Shopsys\FrameworkBundle\Model\PersonalData\Mail\PersonalDataAccessMail;
use Shopsys\FrameworkBundle\Model\PersonalData\Mail\PersonalDataExportMail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MailController extends BaseMailController
{
    /**
     * @var bool
     */
    protected $mailerDisableDelivery;

    /**
     * @param bool $mailerDisableDelivery
     * @param \Shopsys\FrameworkBundle\Model\Customer\Mail\ResetPasswordMail $resetPasswordMail
     * @param \Shopsys\FrameworkBundle\Model\Order\Mail\OrderMail $orderMail
     * @param \Shopsys\FrameworkBundle\Model\Customer\Mail\RegistrationMail $registrationMail
     * @param \Shopsys\FrameworkBundle\Component\Domain\AdminDomainTabsFacade $adminDomainTabsFacade
     * @param \Shopsys\FrameworkBundle\Model\Mail\MailTemplateFacade $mailTemplateFacade
     * @param \Shopsys\FrameworkBundle\Model\Mail\Setting\MailSettingFacade $mailSettingFacade
     * @param \Shopsys\FrameworkBundle\Model\Order\Status\OrderStatusFacade $orderStatusFacade
     * @param \Shopsys\FrameworkBundle\Model\PersonalData\Mail\PersonalDataAccessMail $personalDataAccessMail
     * @param \Shopsys\FrameworkBundle\Model\PersonalData\Mail\PersonalDataExportMail $personalDataExportMail
     */
    public function __construct(
        bool $mailerDisableDelivery,
        ResetPasswordMail $resetPasswordMail,
        OrderMail $orderMail,
        RegistrationMail $registrationMail,
        AdminDomainTabsFacade $adminDomainTabsFacade,
        MailTemplateFacade $mailTemplateFacade,
        MailSettingFacade $mailSettingFacade,
        OrderStatusFacade $orderStatusFacade,
        PersonalDataAccessMail $personalDataAccessMail,
        PersonalDataExportMail $personalDataExportMail
    ) {
        parent::__construct(
            $resetPasswordMail,
            $orderMail,
            $registrationMail,
            $adminDomainTabsFacade,
            $mailTemplateFacade,
            $mailSettingFacade,
            $orderStatusFacade,
            $personalDataAccessMail,
            $personalDataExportMail
        );
        $this->mailerDisableDelivery = $mailerDisableDelivery;
    }

    /**
     * @Route("/mail/template/")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function templateAction(Request $request): Response
    {
        if ($this->mailerDisableDelivery) {
            $this->getFlashMessageSender()->addInfoFlashTwig(t('Email sending has been prevented so the demoshop is not abused for the spam distribution.'));
        }

        return parent::templateAction($request);
    }
}
