<?php

declare(strict_types=1);

namespace Tests\App\Functional\Model\Order;

use App\Model\Transport\Transport;
use Shopsys\FrameworkBundle\Component\Domain\Domain;
use Shopsys\FrameworkBundle\Model\Payment\Payment;
use Tests\App\Test\TransactionFunctionalTestCase;
use Zalas\Injector\PHPUnit\Symfony\TestCase\SymfonyTestContainer;

class OrderTransportAndPaymentTest extends TransactionFunctionalTestCase
{
    use SymfonyTestContainer;

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

    public function testVisibleTransport()
    {
        $enabledForDomains = [
            Domain::FIRST_DOMAIN_ID => true,
            Domain::SECOND_DOMAIN_ID => false,
        ];
        $transport = $this->getDefaultTransport($enabledForDomains, false);
        $payment = $this->getDefaultPayment($enabledForDomains, false);

        $payment->addTransport($transport);

        $this->em->persist($transport);
        $this->em->flush();
        $this->em->persist($payment);
        $this->em->flush();

        $visiblePayments = $this->paymentFacade->getVisibleOnCurrentDomain();
        $visibleTransports = $this->transportFacade->getVisibleOnCurrentDomain($visiblePayments);

        $this->assertContains($transport, $visibleTransports);
    }

    public function testVisibleTransportHiddenTransport()
    {
        $enabledOnDomains = [
            1 => true,
            2 => false,
        ];
        $transport = $this->getDefaultTransport($enabledOnDomains, true);
        $payment = $this->getDefaultPayment($enabledOnDomains, false);

        $payment->addTransport($transport);

        $this->em->persist($transport);
        $this->em->persist($payment);
        $this->em->flush();

        $visiblePayments = $this->paymentFacade->getVisibleOnCurrentDomain();
        $visibleTransports = $this->transportFacade->getVisibleOnCurrentDomain($visiblePayments);

        $this->assertNotContains($transport, $visibleTransports);
    }

    public function testVisibleTransportHiddenPayment()
    {
        $transportEnabledForDomains = [
            1 => true,
            2 => false,
        ];
        $paymentEnabledForDomains = [
            1 => false,
            2 => false,
        ];

        $transport = $this->getDefaultTransport($transportEnabledForDomains, false);
        $payment = $this->getDefaultPayment($paymentEnabledForDomains, true);

        $payment->addTransport($transport);

        $this->em->persist($transport);
        $this->em->persist($payment);
        $this->em->flush();

        $visiblePayments = $this->paymentFacade->getVisibleOnCurrentDomain();
        $visibleTransports = $this->transportFacade->getVisibleOnCurrentDomain($visiblePayments);

        $this->assertNotContains($transport, $visibleTransports);
    }

    public function testVisibleTransportNoPayment()
    {
        $enabledForDomains = [
            1 => true,
            2 => false,
        ];
        $transport = $this->getDefaultTransport($enabledForDomains, false);

        $this->em->persist($transport);
        $this->em->flush();

        $visiblePayments = $this->paymentFacade->getVisibleOnCurrentDomain();
        $visibleTransports = $this->transportFacade->getVisibleOnCurrentDomain($visiblePayments);

        $this->assertNotContains($transport, $visibleTransports);
    }

    public function testVisibleTransportOnDifferentDomain()
    {
        $paymentEnabledForDomains = [
            1 => true,
            2 => false,
        ];
        $transportEnabledForDomains = [
            1 => false,
            2 => true,
        ];

        $transport = $this->getDefaultTransport($transportEnabledForDomains, false);
        $payment = $this->getDefaultPayment($paymentEnabledForDomains, false);

        $payment->addTransport($transport);

        $this->em->persist($transport);
        $this->em->persist($payment);
        $this->em->flush();

        $visiblePayments = $this->paymentFacade->getVisibleOnCurrentDomain();
        $visibleTransports = $this->transportFacade->getVisibleOnCurrentDomain($visiblePayments);

        $this->assertNotContains($transport, $visibleTransports);
    }

    public function testVisibleTransportPaymentOnDifferentDomain()
    {
        $paymentEnabledForDomains = [
            1 => false,
            2 => true,
        ];
        $transportEnabledForDomains = [
            1 => true,
            2 => false,
        ];
        $transport = $this->getDefaultTransport($transportEnabledForDomains, false);
        $payment = $this->getDefaultPayment($paymentEnabledForDomains, false);
        $payment->addTransport($transport);

        $this->em->persist($transport);
        $this->em->persist($payment);
        $this->em->flush();

        $visiblePayments = $this->paymentFacade->getVisibleOnCurrentDomain();
        $visibleTransports = $this->transportFacade->getVisibleOnCurrentDomain($visiblePayments);

        $this->assertNotContains($transport, $visibleTransports);
    }

    public function testVisiblePayment()
    {
        $enabledForDomains = [
            1 => true,
            2 => false,
        ];
        $transport = $this->getDefaultTransport($enabledForDomains, false);
        $payment = $this->getDefaultPayment($enabledForDomains, false);

        $payment->addTransport($transport);

        $this->em->persist($transport);
        $this->em->persist($payment);
        $this->em->flush();

        $visiblePayments = $this->paymentFacade->getVisibleOnCurrentDomain();

        $this->assertContains($payment, $visiblePayments);
    }

    public function testVisiblePaymentHiddenTransport()
    {
        $enabledForDomains = [
            1 => true,
            2 => false,
        ];
        $transport = $this->getDefaultTransport($enabledForDomains, true);
        $payment = $this->getDefaultPayment($enabledForDomains, false);

        $payment->addTransport($transport);

        $this->em->persist($transport);
        $this->em->persist($payment);
        $this->em->flush();

        $visiblePayments = $this->paymentFacade->getVisibleOnCurrentDomain();

        $this->assertNotContains($payment, $visiblePayments);
    }

    public function testVisiblePaymentHiddenPayment()
    {
        $enabledForDomains = [
            1 => true,
            2 => false,
        ];
        $transport = $this->getDefaultTransport($enabledForDomains, false);
        $payment = $this->getDefaultPayment($enabledForDomains, true);

        $payment->addTransport($transport);

        $this->em->persist($transport);
        $this->em->persist($payment);
        $this->em->flush();

        $visiblePayments = $this->paymentFacade->getVisibleOnCurrentDomain();

        $this->assertNotContains($payment, $visiblePayments);
    }

    public function testVisiblePaymentNoTransport()
    {
        $enabledForDomains = [
            1 => true,
            2 => false,
        ];
        $payment = $this->getDefaultPayment($enabledForDomains, false);

        $this->em->persist($payment);
        $this->em->flush();

        $visiblePayments = $this->paymentFacade->getVisibleOnCurrentDomain();

        $this->assertNotContains($payment, $visiblePayments);
    }

    public function testVisiblePaymentOnDifferentDomain()
    {
        $transportEnabledForDomains = [
            1 => true,
            2 => false,
        ];
        $paymentEnabledForDomains = [
            1 => false,
            2 => true,
        ];
        $transport = $this->getDefaultTransport($transportEnabledForDomains, false);
        $payment = $this->getDefaultPayment($paymentEnabledForDomains, false);
        $payment->addTransport($transport);

        $this->em->persist($transport);
        $this->em->persist($payment);
        $this->em->flush();

        $visiblePayments = $this->paymentFacade->getVisibleOnCurrentDomain();

        $this->assertNotContains($payment, $visiblePayments);
    }

    public function testVisiblePaymentTransportOnDifferentDomain()
    {
        $transportEnabledForDomains = [
            1 => true,
            2 => false,
        ];
        $paymentEnabledForDomains = [
            1 => false,
            2 => true,
        ];
        $transport = $this->getDefaultTransport($transportEnabledForDomains, false);
        $payment = $this->getDefaultPayment($paymentEnabledForDomains, false);

        $payment->addTransport($transport);

        $this->em->persist($transport);
        $this->em->persist($payment);
        $this->em->flush();

        $visiblePayments = $this->paymentFacade->getVisibleOnCurrentDomain();

        $this->assertNotContains($payment, $visiblePayments);
    }

    /**
     * @param bool[] $enabledForDomains
     * @param bool $hidden
     * @return \Shopsys\FrameworkBundle\Model\Payment\Payment
     */
    public function getDefaultPayment($enabledForDomains, $hidden)
    {
        $paymentData = $this->paymentDataFactory->create();
        $names = [];
        foreach ($this->domain->getAllLocales() as $locale) {
            $names[$locale] = 'paymentName';
        }
        $paymentData->name = $names;
        $paymentData->hidden = $hidden;
        $paymentData->enabled = $enabledForDomains;

        return new Payment($paymentData);
    }

    /**
     * @param bool[] $enabledForDomains
     * @param bool $hidden
     * @return \App\Model\Transport\Transport
     */
    public function getDefaultTransport($enabledForDomains, $hidden)
    {
        $transportData = $this->transportDataFactory->create();
        $names = [];
        foreach ($this->domain->getAllLocales() as $locale) {
            $names[$locale] = 'transportName';
        }
        $transportData->name = $names;

        $transportData->hidden = $hidden;
        $transportData->enabled = $enabledForDomains;

        return new Transport($transportData);
    }
}
