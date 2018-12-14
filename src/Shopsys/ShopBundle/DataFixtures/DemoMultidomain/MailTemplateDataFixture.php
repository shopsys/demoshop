<?php

namespace Shopsys\ShopBundle\DataFixtures\DemoMultidomain;

use Doctrine\Common\Persistence\ObjectManager;
use Shopsys\FrameworkBundle\Component\DataFixture\AbstractReferenceFixture;
use Shopsys\FrameworkBundle\Component\Domain\Domain;
use Shopsys\FrameworkBundle\Model\Mail\MailTemplate;
use Shopsys\FrameworkBundle\Model\Mail\MailTemplateData;
use Shopsys\FrameworkBundle\Model\Mail\MailTemplateDataFactoryInterface;
use Shopsys\FrameworkBundle\Model\Mail\MailTemplateFacade;

class MailTemplateDataFixture extends AbstractReferenceFixture
{
    const DOMAIN_ID = 2;

    /**
     * @var \Shopsys\FrameworkBundle\Component\Domain\Domain
     */
    private $domain;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Mail\MailTemplateFacade
     */
    private $mailTemplateFacade;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Mail\MailTemplateDataFactoryInterface
     */
    private $mailTemplateDataFactory;

    /**
     * @param \Shopsys\FrameworkBundle\Component\Domain\Domain $domain
     * @param \Shopsys\FrameworkBundle\Model\Mail\MailTemplateFacade $mailTemplateFacade
     * @param \Shopsys\FrameworkBundle\Model\Mail\MailTemplateDataFactoryInterface $mailTemplateDataFactory
     */
    public function __construct(
        Domain $domain,
        MailTemplateFacade $mailTemplateFacade,
        MailTemplateDataFactoryInterface $mailTemplateDataFactory
    ) {
        $this->domain = $domain;
        $this->mailTemplateFacade = $mailTemplateFacade;
        $this->mailTemplateDataFactory = $mailTemplateDataFactory;
    }

    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $domainUrl = $this->domain->getDomainConfigById(self::DOMAIN_ID)->getUrl();

        $mailTemplateData = $this->mailTemplateDataFactory->create();
        $mailTemplateData->name = 'order_status_1';
        $mailTemplateData->sendMail = true;
        $mailTemplateData->subject = 'Děkujeme za objednávku č. {number} ze dne {date}';
        $mailTemplateData->body = '<table cellspacing="0" style="background:#ffffff; border:1px solid #333333; font-family:Arial; font-size:14px; width:100%">
            <tbody>
                <tr>
                    <td style="background: #333; color: #fff;  padding: 20px;"><img src="' . $domainUrl . '/assets/frontend/images/demoshop.png" style="width:150px" /></td>
                </tr>
                <tr>
                    <td style="padding: 20px;">Dobrý den,,<br />
                    Vaše objednávka byla úspěšně vytvořena. Stav objednávky můžete sledovat na tomto <a href="{order_detail_url}">odkaze</a>. O změně stavu objednávky Vás budeme informovat.<br />
                    &nbsp;
                    <table cellspacing="5" style="font-size:14px; width:100%">
                        <tbody>
                            <tr>
                                <td>Doprava:</td>
                                <td>{transport}</td>
                            </tr>
                            <tr>
                                <td>Platba:</td>
                                <td>{payment}</td>
                            </tr>
                            <tr>
                                <td>Celková cena s DPH:</td>
                                <td>{total_price}</td>
                            </tr>
                            <tr>
                                <td>Pozámka:</td>
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
                    Děkujeme za Váš nákup.<br />
                    <br />
                    S pozdravem,<br />
                    Tým Demoshop</td>
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

        $this->updateMailTemplate($mailTemplateData);

        $mailTemplateData = $this->mailTemplateDataFactory->create();
        $mailTemplateData->name = 'order_status_2';
        $mailTemplateData->sendMail = false;
        $mailTemplateData->subject = 'Stav objednávky se změnil';
        $mailTemplateData->body = '<table cellspacing="0" style="background:#ffffff; border:1px solid #333333; font-family:Arial; font-size:14px; width:100%">
            <tbody>
                <tr>
                    <td style="background: #333; color: #fff;  padding: 20px;"><img src="' . $domainUrl . '/assets/frontend/images/demoshop.png" style="width:150px" /></td>
                </tr>
                <tr>
                    <td style="padding: 20px;">Vážený zákazníku,<br />
                    Vaše objednávka {number} se zpracovává. Více informací o Vaší objednávce naleznete <a href="{order_detail_url}">zde</a>.&nbsp;<br />
                    <br />
                    <br />
                    <br />
                    S pozdravem,<br />
                    Tým Demoshop</td>
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

        $this->updateMailTemplate($mailTemplateData);

        $mailTemplateData = $this->mailTemplateDataFactory->create();
        $mailTemplateData->name = 'order_status_3';
        $mailTemplateData->sendMail = false;
        $mailTemplateData->subject = 'Stav objednávky se změnil';
        $mailTemplateData->body = '<table cellspacing="0" style="background:#ffffff; border:1px solid #333333; font-family:Arial; font-size:14px; width:100%">
            <tbody>
                <tr>
                    <td style="background: #333; color: #fff;  padding: 20px;"><img src="' . $domainUrl . '/assets/frontend/images/demoshop.png" style="width:150px" /></td>
                </tr>
                <tr>
                    <td style="padding: 20px;">Vážený zákazníku,<br />
                    zpracování objednávky&nbsp;{number} bylo dokončeno. Děkujeme za Váš nákup.<br />
                    <br />
                    Budeme se opět těšit na Vaši návštěvu na&nbsp;{url}<br />
                    <br />
                    S pozdravem,<br />
                    Tým Demoshop</td>
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

        $this->updateMailTemplate($mailTemplateData);

        $mailTemplateData = $this->mailTemplateDataFactory->create();
        $mailTemplateData->name = 'order_status_4';
        $mailTemplateData->sendMail = false;
        $mailTemplateData->subject = 'Stav objednávky se změnil';
        $mailTemplateData->body = '<table cellspacing="0" style="background:#ffffff; border:1px solid #333333; font-family:Arial; font-size:14px; width:100%">
            <tbody>
                <tr>
                    <td style="background: #333; color: #fff;  padding: 20px;"><img src="' . $domainUrl . '/assets/frontend/images/demoshop.png" style="width:150px" /></td>
                </tr>
                <tr>
                    <td style="padding: 20px;">Vážený zákazníku,<br />
                    Vaše objednávka&nbsp;{number} byla zrušena.<br />
                    <br />
                    Budeme se těšit na Vaší návštěvu na&nbsp;{url}<br />
                    <br />
                    S pozdravem,<br />
                    Tým Demoshop</td>
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

        $this->updateMailTemplate($mailTemplateData);

        $mailTemplateData = $this->mailTemplateDataFactory->create();
        $mailTemplateData->name = 'reset_password';
        $mailTemplateData->sendMail = true;
        $mailTemplateData->subject = 'Žádost o heslo';
        $mailTemplateData->body = '<table cellspacing="0" style="background:#ffffff; border:1px solid #333333; font-family:Arial; font-size:14px; width:100%">
            <tbody>
                <tr>
                    <td style="background: #333; color: #fff;  padding: 20px;"><img src="' . $domainUrl . '/assets/frontend/images/demoshop.png" style="width:150px" /></td>
                </tr>
                <tr>
                    <td style="padding: 20px;">Vážený zákazníku,<br />
                    <br />
                    tento email byl zaslán na základě Vaší žádosti o změnu hesla. Na tomto odkaze můžete nastavit nové heslo: <a href="{new_password_url}">{new_password_url}</a>.&nbsp;<br />
                    <br />
                    S pozdravem,<br />
                    Tým Demoshop</td>
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

        $this->updateMailTemplate($mailTemplateData);

        $mailTemplateData = $this->mailTemplateDataFactory->create();
        $mailTemplateData->name = 'registration_confirm';
        $mailTemplateData->sendMail = true;
        $mailTemplateData->subject = 'Registrace byla dokončena';
        $mailTemplateData->body = '<table cellspacing="0" style="background:#ffffff; border:1px solid #333333; font-family:Arial; font-size:14px; width:100%">
            <tbody>
                <tr>
                    <td style="background: #333; color: #fff;  padding: 20px;"><img src="' . $domainUrl . '/assets/frontend/images/demoshop.png" style="width:150px" /></td>
                </tr>
                <tr>
                    <td style="padding: 20px;">Vážený zákazníku,<br />
                    Vaše registrace je dokončena.
                    <table style="width:100%">
                        <tbody>
                            <tr>
                                <td>Jméno:</td>
                                <td>{first_name} {last_name}</td>
                            </tr>
                            <tr>
                                <td>E-mail:</td>
                                <td>{email}</td>
                            </tr>
                            <tr>
                                <td>Adresa e-shopu:</td>
                                <td>{url}</td>
                            </tr>
                            <tr>
                                <td>Přihlašovací stránka:</td>
                                <td>{login_page}</td>
                            </tr>
                        </tbody>
                    </table>
                    <br />
                    Budeme se těšit na Vaši návštěvu.<br />
                    <br />
                    S pozdravem,<br />
                    Tým Demoshop<br />
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

        $this->updateMailTemplate($mailTemplateData);

        $mailTemplateData = $this->mailTemplateDataFactory->create();
        $mailTemplateData->name = MailTemplate::PERSONAL_DATA_ACCESS_NAME;
        $mailTemplateData->sendMail = true;
        $mailTemplateData->subject = 'Přehled osobních údajů - {domain}';
        $mailTemplateData->body = '<table cellspacing="0" style="background:#ffffff; border:1px solid #333333; font-family:Arial; font-size:14px; width:100%">
            <tbody>
                <tr>
                    <td style="background: #333; color: #fff;  padding: 20px;"><img src="' . $domainUrl . '/assets/frontend/images/demoshop.png" style="width:150px" /></td>
                </tr>
                <tr>
                    <td style="padding: 20px;">Vážený zákazníku,<br />
                    na základě vašeho zadaného emailu {e-mail}, Vám zasíláme odkaz na zobrazení osobních údajů. Klikem na odkaz níže se dostanete na stránku s&nbsp;přehledem všech osobních údajů, které k Vašemu e-mailu evidujeme na našem e-shopu {domain}.<br />
                    <br />
                    Pro zobrazení osobních údajů klikněte <a href="{url}">zde</a>.<br />
                    Odkaz je platný 24 hodin.<br />
                    <br />
                    S pozdravem<br />
                    Tým Demoshop</td>
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

        $this->updateMailTemplate($mailTemplateData);

        $mailTemplateData = $this->mailTemplateDataFactory->create();
        $mailTemplateData->name = MailTemplate::PERSONAL_DATA_EXPORT_NAME;
        $mailTemplateData->sendMail = true;
        $mailTemplateData->subject = 'Export osobních údajů - {domain}';
        $mailTemplateData->body = '<table cellspacing="0" style="background:#ffffff; border:1px solid #333333; font-family:Arial; font-size:14px; width:100%">
            <tbody>
                <tr>
                    <td style="background: #333; color: #fff;  padding: 20px;"><img src="' . $domainUrl . '/assets/frontend/images/demoshop.png" style="width:150px" /></td>
                </tr>
                <tr>
                    <td style="padding: 20px;">Vážený zákazníku,<br />
                    na základě vašeho zadaného emailu {e-mail}, Vám zasíláme odkaz ke stažení Vašich<br />
                    údajů evidovaných na našem internetovém obchodě ve strojově čitelném formátu.<br />
                    Klikem na odkaz se dostanete na stránku s možností stažení těchto informací, které k<br />
                    Vašemu e-mailu evidujeme na našem eshopu {domain}.<br />
                    <br />
                    Pro přechod na stažení údajů, prosím, klikněte <a href="{url}">zde </a>.<br />
                    Odkaz je platný 24 hodin.<br />
                    <br />
                    S pozdravem<br />
                    Tým Demoshop</td>
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

        $this->updateMailTemplate($mailTemplateData);
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Mail\MailTemplateData $mailTemplateData
     */
    private function updateMailTemplate(MailTemplateData $mailTemplateData)
    {
        $this->mailTemplateFacade->saveMailTemplatesData([$mailTemplateData], self::DOMAIN_ID);
    }
}
