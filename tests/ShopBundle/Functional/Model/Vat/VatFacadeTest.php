<?php

declare(strict_types=1);

namespace Tests\ShopBundle\Functional\Model\Vat;

use Shopsys\FrameworkBundle\Model\Pricing\Vat\VatData;
use App\DataFixtures\Demo\PaymentDataFixture;
use App\DataFixtures\Demo\TransportDataFixture;
use App\DataFixtures\Demo\VatDataFixture;
use Tests\ShopBundle\Test\TransactionFunctionalTestCase;

class VatFacadeTest extends TransactionFunctionalTestCase
{
    /**
     * @var \Shopsys\FrameworkBundle\Model\Pricing\Vat\VatFacade
     * @inject
     */
    private $vatFacade;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Transport\TransportFacade
     * @inject
     */
    private $transportFacade;

    /**
     * @var \App\Model\Transport\TransportDataFactory
     * @inject
     */
    private $transportDataFactory;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Payment\PaymentFacade
     * @inject
     */
    private $paymentFacade;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Payment\PaymentDataFactoryInterface
     * @inject
     */
    private $paymentDataFactory;

    public function testDeleteByIdAndReplace()
    {
        $em = $this->getEntityManager();

        $vatData = new VatData();
        $vatData->name = 'name';
        $vatData->percent = 10;
        $vatToDelete = $this->vatFacade->create($vatData);
        $vatToReplaceWith = $this->getReference(VatDataFixture::VAT_HIGH);
        /* @var $vatToReplaceWith \Shopsys\FrameworkBundle\Model\Pricing\Vat\Vat */
        $transport = $this->getReference(TransportDataFixture::TRANSPORT_PERSONAL);
        /* @var $transport \Shopsys\FrameworkBundle\Model\Transport\Transport */
        $transportData = $this->transportDataFactory->createFromTransport($transport);
        $payment = $this->getReference(PaymentDataFixture::PAYMENT_CASH);
        /* @var $payment \Shopsys\FrameworkBundle\Model\Payment\Payment */
        $paymentData = $this->paymentDataFactory->createFromPayment($payment);

        $transportData->vat = $vatToDelete;
        $this->transportFacade->edit($transport, $transportData);

        $paymentData->vat = $vatToDelete;
        $this->paymentFacade->edit($payment, $paymentData);

        $this->vatFacade->deleteById($vatToDelete, $vatToReplaceWith);

        $em->refresh($transport);
        $em->refresh($payment);

        $this->assertEquals($vatToReplaceWith, $transport->getVat());
        $this->assertEquals($vatToReplaceWith, $payment->getVat());
    }
}
