<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Model\Mail;

use Shopsys\FrameworkBundle\Component\FlashMessage\FlashMessageSender;
use Shopsys\FrameworkBundle\Model\Mail\Mailer as BaseMailer;
use Shopsys\FrameworkBundle\Model\Mail\MessageData;
use Swift_Mailer;
use Swift_Transport;

class Mailer extends BaseMailer
{
    /**
     * @var bool
     */
    protected $mailerDisableDelivery;

    /**
     * @var \Shopsys\FrameworkBundle\Component\FlashMessage\FlashMessageSender
     */
    protected $flashMessageSender;

    /**
     * @param \Swift_Mailer $swiftMailer
     * @param \Swift_Transport $realSwiftTransport
     * @param bool $mailerDisableDelivery
     * @param \Shopsys\FrameworkBundle\Component\FlashMessage\FlashMessageSender $flashMessageSender
     */
    public function __construct(
        Swift_Mailer $swiftMailer,
        Swift_Transport $realSwiftTransport,
        bool $mailerDisableDelivery,
        FlashMessageSender $flashMessageSender
    ) {
        parent::__construct($swiftMailer, $realSwiftTransport);

        $this->flashMessageSender = $flashMessageSender;
        $this->mailerDisableDelivery = $mailerDisableDelivery;
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Mail\MessageData $messageData
     */
    public function send(MessageData $messageData): void
    {
        if ($this->mailerDisableDelivery) {
            $this->flashMessageSender->addInfoFlashTwig(t('In the standard project, the email would be sent to your email address, but now the sending has been prevented so the demoshop is not abused for the spam distribution.'));
        } else {
            parent::send($messageData);
        }
    }
}
