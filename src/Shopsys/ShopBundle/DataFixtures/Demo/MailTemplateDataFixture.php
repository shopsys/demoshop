<?php

namespace Shopsys\ShopBundle\DataFixtures\Demo;

use Doctrine\Common\Persistence\ObjectManager;
use Shopsys\FrameworkBundle\Component\DataFixture\AbstractReferenceFixture;
use Shopsys\FrameworkBundle\Component\Domain\Domain;
use Shopsys\FrameworkBundle\Model\Mail\MailTemplate;
use Shopsys\FrameworkBundle\Model\Mail\MailTemplateData;
use Shopsys\FrameworkBundle\Model\Mail\MailTemplateDataFactoryInterface;
use Shopsys\FrameworkBundle\Model\Mail\MailTemplateFactoryInterface;

class MailTemplateDataFixture extends AbstractReferenceFixture
{
    /**
     * @var \Shopsys\FrameworkBundle\Component\Domain\Domain
     */
    protected $domain;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Mail\MailTemplateFactoryInterface
     */
    protected $mailTemplateFactory;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Mail\MailTemplateDataFactoryInterface
     */
    protected $mailTemplateDataFactory;

    /**
     * @param \Shopsys\FrameworkBundle\Component\Domain\Domain $domain
     * @param \Shopsys\FrameworkBundle\Model\Mail\MailTemplateFactoryInterface $mailTemplateFactory
     * @param \Shopsys\FrameworkBundle\Model\Mail\MailTemplateDataFactoryInterface $mailTemplateDataFactory
     */
    public function __construct(
        Domain $domain,
        MailTemplateFactoryInterface $mailTemplateFactory,
        MailTemplateDataFactoryInterface $mailTemplateDataFactory
    ) {
        $this->domain = $domain;
        $this->mailTemplateFactory = $mailTemplateFactory;
        $this->mailTemplateDataFactory = $mailTemplateDataFactory;
    }

    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $domainUrl = $this->domain->getDomainConfigById(Domain::FIRST_DOMAIN_ID)->getUrl();

        $mailTemplateData = $this->mailTemplateDataFactory->create();
        $mailTemplateData->sendMail = true;

        $mailTemplateData->subject = 'Thank you for your order no. {number} placed at {date}';
        $mailTemplateData->body = '<table cellspacing="0" style="background:#ffffff; border:1px solid #333333; font-family:Arial; font-size:14px; width:100%">
            <tbody>
                <tr>
                    <td style="background: #333; color: #fff;  padding: 20px;"><img src="' . $domainUrl . '/assets/frontend/images/demoshop.png" style="width:150px" /></td>
                </tr>
                <tr>
                    <td style="padding: 20px;">Dear customer,<br />
                    Thank you for your order. Your order number {number} has been placed successfully. You can view the order status <a href="{order_detail_url}">here</a>. You will be contacted when the order state changes.<br />
                    &nbsp;
                    <table cellspacing="5" style="font-size:14px; width:100%">
                        <tbody>
                            <tr>
                                <td>Shipping:</td>
                                <td>{transport}</td>
                            </tr>
                            <tr>
                                <td>Payment:</td>
                                <td>{payment}</td>
                            </tr>
                            <tr>
                                <td>Total price including VAT:</td>
                                <td>{total_price}</td>
                            </tr>
                            <tr>
                                <td>Note:</td>
                                <td>{note}</td>
                            </tr>
                            <tr>
                                <td colspan="2">{products}</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="text-align: left;">{transport_instructions}</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="text-align: left;">{payment_instructions}</td>
                            </tr>
                        </tbody>
                    </table>
                    <br />
                    Thank your for your purchase.<br />
                    <br />
                    Regards,<br />
                    Team Demoshop</td>
                </tr>
                <tr>
                    <td style="background: #333; color: #fff; padding: 20px;">
                    <table style="width:100%">
                        <tbody>
                            <tr>
                                <td style="color: #fff; width: 50%;">Made with passion by <a href="http://www.shopsys-framework.com/" style="color: #01AEFF;">Shopsys Framework</a></td>
                                <td style="color: #fff; width: 50%; text-align: right;"><a href="tel:+420123456789" style="color: #fff;">+420123456789</a></td>
                            </tr>
                        </tbody>
                    </table>
                    </td>
                </tr>
            </tbody>
        </table>';

        $this->createMailTemplate($manager, 'order_status_1', $mailTemplateData);

        $mailTemplateData->sendMail = false;
        $mailTemplateData->subject = 'Order status has changed';
        $mailTemplateData->body = '<table cellspacing="0" style="background:#ffffff; border:1px solid #333333; font-family:Arial; font-size:14px; width:100%">
            <tbody>
                <tr>
                    <td style="background: #333; color: #fff;  padding: 20px;"><img src="' . $domainUrl . '/assets/frontend/images/demoshop.png" style="width:150px" /></td>
                </tr>
                <tr>
                    <td style="padding: 20px;">Dear customer,<br />
                    Your order {number} is being processed. For more details about your order you can click <a href="{order_detail_url}">here</a>.<br />
                    <br />
                    Regards,<br />
                    Team Demoshop</td>
                </tr>
                <tr>
                    <td style="background: #333; color: #fff; padding: 20px;">
                    <table style="width:100%">
                        <tbody>
                            <tr>
                                <td style="color: #fff; width: 50%;">Made with passion by <a href="http://www.shopsys-framework.com/" style="color: #01AEFF;">Shopsys Framework</a></td>
                                <td style="color: #fff; width: 50%; text-align: right;"><a href="tel:+420123456789" style="color: #fff;">+420123456789</a></td>
                            </tr>
                        </tbody>
                    </table>
                    </td>
                </tr>
            </tbody>
        </table>';

        $this->createMailTemplate($manager, 'order_status_2', $mailTemplateData);

        $mailTemplateData->subject = 'Order status has changed';
        $mailTemplateData->body = '<table cellspacing="0" style="background:#ffffff; border:1px solid #333333; font-family:Arial; font-size:14px; width:100%">
            <tbody>
                <tr>
                    <td style="background: #333; color: #fff;  padding: 20px;"><img src="' . $domainUrl . '/assets/frontend/images/demoshop.png" style="width:150px" /></td>
                </tr>
                <tr>
                    <td style="padding: 20px;">Dear customer,<br />
                    Processing your order&nbsp;{number} has been finished. Thank you for your purchase.&nbsp;<br />
                    <br />
                    We will again look forward to your visit to&nbsp;{url}<br />
                    <br />
                    <br />
                    Regards,<br />
                    Team Demoshop</td>
                </tr>
                <tr>
                    <td style="background: #333; color: #fff; padding: 20px;">
                    <table style="width:100%">
                        <tbody>
                            <tr>
                                <td style="color: #fff; width: 50%;">Made with passion by <a href="http://www.shopsys-framework.com/" style="color: #01AEFF;">Shopsys Framework</a></td>
                                <td style="color: #fff; width: 50%; text-align: right;"><a href="tel:+420123456789" style="color: #fff;">+420123456789</a></td>
                            </tr>
                        </tbody>
                    </table>
                    </td>
                </tr>
            </tbody>
        </table>';

        $this->createMailTemplate($manager, 'order_status_3', $mailTemplateData);

        $mailTemplateData->subject = 'Order status has changed';
        $mailTemplateData->body = '<table cellspacing="0" style="background:#ffffff; border:1px solid #333333; font-family:Arial; font-size:14px; width:100%">
            <tbody>
                <tr>
                    <td style="background: #333; color: #fff;  padding: 20px;"><img src="' . $domainUrl . '/assets/frontend/images/demoshop.png" style="width:150px" /></td>
                </tr>
                <tr>
                    <td style="padding: 20px;">Dear customer,<br />
                    Your order {number} has been cancelled.<br />
                    <br />
                    We will again look forward to your visit to&nbsp;{url}<br />
                    <br />
                    Regards,<br />
                    Team Demoshop</td>
                </tr>
                <tr>
                    <td style="background: #333; color: #fff; padding: 20px;">
                    <table style="width:100%">
                        <tbody>
                            <tr>
                                <td style="color: #fff; width: 50%;">Made with passion by <a href="http://www.shopsys-framework.com/" style="color: #01AEFF;">Shopsys Framework</a></td>
                                <td style="color: #fff; width: 50%; text-align: right;"><a href="tel:+420123456789" style="color: #fff;">+420123456789</a></td>
                            </tr>
                        </tbody>
                    </table>
                    </td>
                </tr>
            </tbody>
        </table>';

        $this->createMailTemplate($manager, 'order_status_4', $mailTemplateData);

        $mailTemplateData->sendMail = true;
        $mailTemplateData->subject = 'Reset password request';
        $mailTemplateData->body = '<table cellspacing="0" style="background:#ffffff; border:1px solid #333333; font-family:Arial; font-size:14px; width:100%">
            <tbody>
                <tr>
                    <td style="background: #333; color: #fff;  padding: 20px;"><img src="' . $domainUrl . '/assets/frontend/images/demoshop.png" style="width:150px" /></td>
                </tr>
                <tr>
                    <td style="padding: 20px;">Dear customer,<br />
                    <br />
                    You can set a new password following this link: <a href="{new_password_url}">{new_password_url}</a><br />
                    <br />
                    Regards,<br />
                    Team Demoshop</td>
                </tr>
                <tr>
                    <td style="background: #333; color: #fff; padding: 20px;">
                    <table style="width:100%">
                        <tbody>
                            <tr>
                                <td style="color: #fff; width: 50%;">Made with passion by <a href="http://www.shopsys-framework.com/" style="color: #01AEFF;">Shopsys Framework</a></td>
                                <td style="color: #fff; width: 50%; text-align: right;"><a href="tel:+420123456789" style="color: #fff;">+420123456789</a></td>
                            </tr>
                        </tbody>
                    </table>
                    </td>
                </tr>
            </tbody>
        </table>';

        $this->createMailTemplate($manager, MailTemplate::RESET_PASSWORD_NAME, $mailTemplateData);

        $mailTemplateData->subject = 'Registration completed';
        $mailTemplateData->body = '<table cellspacing="0" style="background:#ffffff; border:1px solid #333333; font-family:Arial; font-size:14px; width:100%">
            <tbody>
                <tr>
                    <td style="background: #333; color: #fff;  padding: 20px;"><img src="' . $domainUrl . '/assets/frontend/images/demoshop.png" style="width:150px" /></td>
                </tr>
                <tr>
                    <td style="padding: 20px;">Dear customer,<br />
                    your registration is completed.
                    <table style="width:100%">
                        <tbody>
                            <tr>
                                <td>Name:</td>
                                <td>{first_name} {last_name}</td>
                            </tr>
                            <tr>
                                <td>Email:</td>
                                <td>{email}</td>
                            </tr>
                            <tr>
                                <td>E-shop link:</td>
                                <td>{url}</td>
                            </tr>
                            <tr>
                                <td>Log in page:</td>
                                <td>{login_page}</td>
                            </tr>
                        </tbody>
                    </table>
                    <br />
                    We will again look forward to your visit.<br />
                    <br />
                    Regards,<br />
                    Team Demoshop<br />
                    &nbsp;</td>
                </tr>
                <tr>
                    <td style="background: #333; color: #fff; padding: 20px;">
                    <table style="width:100%">
                        <tbody>
                            <tr>
                                <td style="color: #fff; width: 50%;">Made with passion by <a href="http://www.shopsys-framework.com/" style="color: #01AEFF;">Shopsys Framework</a></td>
                                <td style="color: #fff; width: 50%; text-align: right;"><a href="tel:+420123456789" style="color: #fff;">+420123456789</a></td>
                            </tr>
                        </tbody>
                    </table>
                    </td>
                </tr>
            </tbody>
        </table>';

        $this->createMailTemplate($manager, MailTemplate::REGISTRATION_CONFIRM_NAME, $mailTemplateData);

        $mailTemplateData->subject = 'Personal overview - {domain}';
        $mailTemplateData->body = '<table cellspacing="0" style="background:#ffffff; border:1px solid #333333; font-family:Arial; font-size:14px; width:100%">
            <tbody>
                <tr>
                    <td style="background: #333; color: #fff;  padding: 20px;"><img src="' . $domainUrl . '/assets/frontend/images/demoshop.png" style="width:150px" /></td>
                </tr>
                <tr>
                    <td style="padding: 20px;">Dear customer,<br />
                    For your email, we record personal overview that you can view <a href="{url}">here</a>.<br />
                    The link is valid for 24 hours.<br />
                    <br />
                    <br />
                    Regards,<br />
                    Team Demoshop</td>
                </tr>
                <tr>
                    <td style="background: #333; color: #fff; padding: 20px;">
                    <table style="width:100%">
                        <tbody>
                            <tr>
                                <td style="color: #fff; width: 50%;">Made with passion by <a href="http://www.shopsys-framework.com/" style="color: #01AEFF;">Shopsys Framework</a></td>
                                <td style="color: #fff; width: 50%; text-align: right;"><a href="tel:+420123456789" style="color: #fff;">+420123456789</a></td>
                            </tr>
                        </tbody>
                    </table>
                    </td>
                </tr>
            </tbody>
        </table>';

        $this->createMailTemplate($manager, MailTemplate::PERSONAL_DATA_ACCESS_NAME, $mailTemplateData);

        $mailTemplateData->subject = 'Personal information export - {domain}';
        $mailTemplateData->body = '<table cellspacing="0" style="background:#ffffff; border:1px solid #333333; font-family:Arial; font-size:14px; width:100%">
            <tbody>
                <tr>
                    <td style="background: #333; color: #fff;  padding: 20px;"><img src="' . $domainUrl . '/assets/frontend/images/demoshop.png" style="width:150px" /></td>
                </tr>
                <tr>
                    <td style="padding: 20px;">Dear customer,<br />
                    You can find your personal information export <a href="{url}">here</a>.<br />
                    The link is valid for 24 hours.<br />
                    <br />
                    Regards,<br />
                    Tem Demoshop</td>
                </tr>
                <tr>
                    <td style="background: #333; color: #fff; padding: 20px;">
                    <table style="width:100%">
                        <tbody>
                            <tr>
                                <td style="color: #fff; width: 50%;">Made with passion by <a href="http://www.shopsys-framework.com/" style="color: #01AEFF;">Shopsys Framework</a></td>
                                <td style="color: #fff; width: 50%; text-align: right;"><a href="tel:+420123456789" style="color: #fff;">+420123456789</a></td>
                            </tr>
                        </tbody>
                    </table>
                    </td>
                </tr>
            </tbody>
        </table>';

        $this->createMailTemplate($manager, MailTemplate::PERSONAL_DATA_EXPORT_NAME, $mailTemplateData);
    }

    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     * @param mixed $name
     * @param \Shopsys\FrameworkBundle\Model\Mail\MailTemplateData $mailTemplateData
     */
    private function createMailTemplate(
        ObjectManager $manager,
        $name,
        MailTemplateData $mailTemplateData
    ) {
        $repository = $manager->getRepository(MailTemplate::class);

        $mailTemplate = $repository->findOneBy([
            'name' => $name,
            'domainId' => Domain::FIRST_DOMAIN_ID,
        ]);

        if ($mailTemplate === null) {
            $mailTemplate = $this->mailTemplateFactory->create($name, Domain::FIRST_DOMAIN_ID, $mailTemplateData);
        } else {
            $mailTemplate->edit($mailTemplateData);
        }

        $manager->persist($mailTemplate);
        $manager->flush($mailTemplate);
    }
}
