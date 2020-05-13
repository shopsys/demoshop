<?php

declare(strict_types=1);

namespace App\Model\Mail;

use Shopsys\FrameworkBundle\Component\FlashMessage\FlashMessageTrait;
use Shopsys\FrameworkBundle\Model\Mail\Mailer as BaseMailer;
use Shopsys\FrameworkBundle\Model\Mail\MailTemplateFacade;
use Shopsys\FrameworkBundle\Model\Mail\MessageData;
use Swift_Mailer;
use Swift_Transport;

class Mailer extends BaseMailer
{
    use FlashMessageTrait;

    /**
     * @var bool
     */
    protected $mailerDisableDelivery;

    /**
     * @param \Swift_Mailer $swiftMailer
     * @param \Swift_Transport $realSwiftTransport
     * @param \Shopsys\FrameworkBundle\Model\Mail\MailTemplateFacade $mailTemplateFacade
     * @param bool $mailerDisableDelivery
     */
    public function __construct(
        Swift_Mailer $swiftMailer,
        Swift_Transport $realSwiftTransport,
        MailTemplateFacade $mailTemplateFacade,
        bool $mailerDisableDelivery
    ) {
        parent::__construct($swiftMailer, $realSwiftTransport, $mailTemplateFacade);

        $this->mailerDisableDelivery = $mailerDisableDelivery;
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Mail\MessageData $messageData
     */
    public function send(MessageData $messageData): void
    {
        if ($this->mailerDisableDelivery) {
            $this->addInfoFlashTwig(t('In the standard project, the email would be sent to your email address, but now the sending has been prevented so the demoshop is not abused for the spam distribution.'));
        } else {
            parent::send($messageData);
        }
    }
}
