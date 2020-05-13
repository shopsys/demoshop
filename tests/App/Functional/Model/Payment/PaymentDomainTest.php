<?php

declare(strict_types=1);

namespace Tests\App\Functional\Model\Payment;

use Shopsys\FrameworkBundle\Model\Payment\Payment;
use Tests\App\Test\TransactionFunctionalTestCase;

class PaymentDomainTest extends TransactionFunctionalTestCase
{
    use SymfonyTestContainer;

    public const FIRST_DOMAIN_ID = 1;
    public const SECOND_DOMAIN_ID = 2;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Payment\PaymentDataFactoryInterface
     * @inject
     */
    private $paymentDataFactory;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Payment\PaymentFactoryInterface
     * @inject
     */
    private $paymentFactory;

    public function testCreatePaymentEnabledOnDomain()
    {
        $paymentData = $this->paymentDataFactory->create();

        $paymentData->enabled = [
            self::FIRST_DOMAIN_ID => true,
        ];

        $payment = $this->paymentFactory->create($paymentData);

        $refreshedPayment = $this->getRefreshedPaymentFromDatabase($payment);

        $this->assertTrue($refreshedPayment->isEnabled(self::FIRST_DOMAIN_ID));
    }

    public function testCreatePaymentDisabledOnDomain()
    {
        $paymentData = $this->paymentDataFactory->create();

        $paymentData->enabled[self::FIRST_DOMAIN_ID] = false;

        $payment = $this->paymentFactory->create($paymentData);

        $refreshedPayment = $this->getRefreshedPaymentFromDatabase($payment);

        $this->assertFalse($refreshedPayment->isEnabled(self::FIRST_DOMAIN_ID));
    }

    public function testCreatePaymentWithDifferentVisibilityOnDomains()
    {
        $paymentData = $this->paymentDataFactory->create();

        $paymentData->enabled[self::FIRST_DOMAIN_ID] = true;
        $paymentData->enabled[self::SECOND_DOMAIN_ID] = false;

        $payment = $this->paymentFactory->create($paymentData);

        $refreshedPayment = $this->getRefreshedPaymentFromDatabase($payment);

        $this->assertTrue($refreshedPayment->isEnabled(self::FIRST_DOMAIN_ID));
        $this->assertFalse($refreshedPayment->isEnabled(self::SECOND_DOMAIN_ID));
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Payment\Payment $payment
     * @return \Shopsys\FrameworkBundle\Model\Payment\Payment
     */
    private function getRefreshedPaymentFromDatabase(Payment $payment)
    {
        $this->em->persist($payment);
        $this->em->flush();

        $paymentId = $payment->getId();

        $this->em->clear();

        return $this->em->getRepository(Payment::class)->find($paymentId);
    }
}
