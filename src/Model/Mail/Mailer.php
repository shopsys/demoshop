<?php

declare(strict_types=1);

namespace App\Model\Mail;

use Shopsys\FrameworkBundle\Component\FlashMessage\FlashMessage;
use Shopsys\FrameworkBundle\Component\FlashMessage\FlashMessageTrait;
use Shopsys\FrameworkBundle\Model\Mail\Mailer as BaseMailer;
use Shopsys\FrameworkBundle\Model\Mail\MailTemplateFacade;
use Shopsys\FrameworkBundle\Model\Mail\MessageData;
use Swift_Mailer;
use Swift_Transport;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class Mailer extends BaseMailer
{
    use FlashMessageTrait;

    /**
     * @var bool
     */
    protected $mailerDisableDelivery;

    /**
     * @var \Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface
     */
    private $flashBag;

    /**
     * @param \Swift_Mailer $swiftMailer
     * @param \Swift_Transport $realSwiftTransport
     * @param bool $mailerDisableDelivery
     * @param \Shopsys\FrameworkBundle\Model\Mail\MailTemplateFacade $mailTemplateFacade
     * @param \Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface $flashBag
     */
    public function __construct(
        Swift_Mailer $swiftMailer,
        Swift_Transport $realSwiftTransport,
        bool $mailerDisableDelivery,
        MailTemplateFacade $mailTemplateFacade,
        FlashBagInterface $flashBag
    ) {
        parent::__construct($swiftMailer, $realSwiftTransport, $mailTemplateFacade);

        $this->mailerDisableDelivery = $mailerDisableDelivery;
        $this->flashBag = $flashBag;
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Mail\MessageData $messageData
     */
    public function send(MessageData $messageData): void
    {
        if ($this->mailerDisableDelivery) {
            $this->flashBag->add(FlashMessage::KEY_INFO, t('In the standard project, the email would be sent to your email address, but now the sending has been prevented so the demoshop is not abused for the spam distribution.'));
        } else {
            parent::send($messageData);
        }
    }
}
