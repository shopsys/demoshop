<?php

declare(strict_types=1);

namespace Tests\ShopBundle\Functional\Model\Customer;

use Shopsys\FrameworkBundle\Component\Domain\Domain;
use App\DataFixtures\Demo\PricingGroupDataFixture;
use Tests\ShopBundle\Test\TransactionFunctionalTestCase;

class CustomerFacadeTest extends TransactionFunctionalTestCase
{
    protected const EXISTING_EMAIL_ON_DOMAIN_1 = 'no-reply.3@shopsys.com';
    protected const EXISTING_EMAIL_ON_DOMAIN_2 = 'no-reply.4@shopsys.com';

    /**
     * @var \Shopsys\FrameworkBundle\Model\Customer\CustomerFacade
     * @inject
     */
    protected $customerFacade;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Customer\CustomerDataFactoryInterface
     * @inject
     */
    protected $customerDataFactory;

    public function testChangeEmailToExistingEmailButDifferentDomainDoNotThrowException()
    {
        $user = $this->customerFacade->findUserByEmailAndDomain(self::EXISTING_EMAIL_ON_DOMAIN_1, Domain::FIRST_DOMAIN_ID);
        $customerData = $this->customerDataFactory->createFromUser($user);
        $customerData->userData->email = self::EXISTING_EMAIL_ON_DOMAIN_2;

        $this->customerFacade->editByAdmin($user->getId(), $customerData);

        $this->expectNotToPerformAssertions();
    }

    public function testCreateNotDuplicateEmail()
    {
        $customerData = $this->customerDataFactory->create();
        $customerData->userData->pricingGroup = $this->getReferenceForDomain(PricingGroupDataFixture::PRICING_GROUP_ORDINARY, Domain::FIRST_DOMAIN_ID);
        $customerData->userData->domainId = 1;
        $customerData->userData->email = 'unique-email@shopsys.com';
        $customerData->userData->firstName = 'John';
        $customerData->userData->lastName = 'Doe';
        $customerData->userData->password = 'password';

        $this->customerFacade->create($customerData);

        $this->expectNotToPerformAssertions();
    }

    public function testCreateDuplicateEmail()
    {
        $user = $this->customerFacade->findUserByEmailAndDomain(self::EXISTING_EMAIL_ON_DOMAIN_1, 1);
        $customerData = $this->customerDataFactory->createFromUser($user);
        $customerData->userData->password = 'password';
        $this->expectException(\Shopsys\FrameworkBundle\Model\Customer\Exception\DuplicateEmailException::class);

        $this->customerFacade->create($customerData);
    }

    public function testCreateDuplicateEmailCaseInsentitive()
    {
        $user = $this->customerFacade->findUserByEmailAndDomain(self::EXISTING_EMAIL_ON_DOMAIN_1, 1);
        $customerData = $this->customerDataFactory->createFromUser($user);
        $customerData->userData->password = 'password';
        $customerData->userData->email = mb_strtoupper(self::EXISTING_EMAIL_ON_DOMAIN_1);
        $this->expectException(\Shopsys\FrameworkBundle\Model\Customer\Exception\DuplicateEmailException::class);

        $this->customerFacade->create($customerData);
    }
}
