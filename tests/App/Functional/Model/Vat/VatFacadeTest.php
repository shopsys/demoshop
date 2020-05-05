<?php

declare(strict_types=1);

namespace Tests\App\Functional\Model\Vat;

use Shopsys\FrameworkBundle\Component\Domain\Domain;
use Shopsys\FrameworkBundle\Model\Pricing\Vat\VatData;
use App\DataFixtures\Demo\PaymentDataFixture;
use App\DataFixtures\Demo\TransportDataFixture;
use App\DataFixtures\Demo\VatDataFixture;
use Tests\App\Test\TransactionFunctionalTestCase;

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

    public function testDeleteByIdAndReplaceForFirstDomain()
    {
        $em = $this->getEntityManager();

        $vatData = new VatData();
        $vatData->name = 'name';
        $vatData->percent = 10;
        $vatToDelete = $this->vatFacade->create($vatData, Domain::FIRST_DOMAIN_ID);
        $vatToReplaceWith = $this->getReferenceForDomain(VatDataFixture::VAT_HIGH, Domain::FIRST_DOMAIN_ID);
        /* @var $vatToReplaceWith \Shopsys\FrameworkBundle\Model\Pricing\Vat\Vat */
        $transport = $this->getReference(TransportDataFixture::TRANSPORT_PERSONAL);
        /* @var $transport \Shopsys\FrameworkBundle\Model\Transport\Transport */
        $transportData = $this->transportDataFactory->createFromTransport($transport);

        $payment = $this->getReference(PaymentDataFixture::PAYMENT_CASH);
        /* @var $payment \Shopsys\FrameworkBundle\Model\Payment\Payment */
        $paymentData = $this->paymentDataFactory->createFromPayment($payment);

        $transportData->vatsIndexedByDomainId[Domain::FIRST_DOMAIN_ID] = $vatToDelete;
        $this->transportFacade->edit($transport, $transportData);

        $paymentData->vatsIndexedByDomainId[Domain::FIRST_DOMAIN_ID] = $vatToDelete;
        $this->paymentFacade->edit($payment, $paymentData);

        $this->vatFacade->deleteById($vatToDelete, $vatToReplaceWith);

        $em->refresh($transport->getTransportDomain(Domain::FIRST_DOMAIN_ID));
        $em->refresh($payment->getPaymentDomain(Domain::FIRST_DOMAIN_ID));

        $this->assertEquals($vatToReplaceWith, $payment->getPaymentDomain(Domain::FIRST_DOMAIN_ID)->getVat());
        $this->assertEquals($vatToReplaceWith, $transport->getTransportDomain(Domain::FIRST_DOMAIN_ID)->getVat());
    }
}
