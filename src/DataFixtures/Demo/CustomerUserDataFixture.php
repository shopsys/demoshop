<?php

declare(strict_types=1);

namespace App\DataFixtures\Demo;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Generator;
use Shopsys\FrameworkBundle\Component\DataFixture\AbstractReferenceFixture;
use Shopsys\FrameworkBundle\Component\Domain\Domain;
use Shopsys\FrameworkBundle\Component\String\HashGenerator;
use Shopsys\FrameworkBundle\Model\Customer\BillingAddressDataFactoryInterface;
use Shopsys\FrameworkBundle\Model\Customer\CustomerFactoryInterface;
use Shopsys\FrameworkBundle\Model\Customer\DeliveryAddressDataFactoryInterface;
use Shopsys\FrameworkBundle\Model\Customer\User\CustomerUser;
use Shopsys\FrameworkBundle\Model\Customer\User\CustomerUserDataFactoryInterface;
use Shopsys\FrameworkBundle\Model\Customer\User\CustomerUserFacade;
use Shopsys\FrameworkBundle\Model\Customer\User\CustomerUserPasswordFacade;
use Shopsys\FrameworkBundle\Model\Customer\User\CustomerUserUpdateData;
use Shopsys\FrameworkBundle\Model\Customer\User\CustomerUserUpdateDataFactoryInterface;

class CustomerUserDataFixture extends AbstractReferenceFixture implements DependentFixtureInterface
{
    public const USER_WITH_RESET_PASSWORD_HASH = 'user_with_reset_password_hash';

    protected const KEY_CUSTOMER_USER_DATA = 'customerUserData';
    protected const KEY_BILLING_ADDRESS = 'billingAddress';
    protected const KEY_DELIVERY_ADDRESS = 'deliveryAddress';

    protected const KEY_CUSTOMER_USER_DATA_FIRST_NAME = 'firstName';
    protected const KEY_CUSTOMER_USER_DATA_LAST_NAME = 'lastName';
    protected const KEY_CUSTOMER_USER_DATA_EMAIL = 'email';
    protected const KEY_CUSTOMER_USER_DATA_PASSWORD = 'password';
    protected const KEY_CUSTOMER_USER_DATA_TELEPHONE = 'telephone';

    protected const KEY_ADDRESS_COMPANY_CUSTOMER = 'companyCustomer';
    protected const KEY_ADDRESS_COMPANY_NAME = 'companyName';
    protected const KEY_ADDRESS_COMPANY_NUMBER = 'companyNumber';
    protected const KEY_ADDRESS_STREET = 'street';
    protected const KEY_ADDRESS_CITY = 'city';
    protected const KEY_ADDRESS_POSTCODE = 'postcode';
    protected const KEY_ADDRESS_COUNTRY = 'country';
    protected const KEY_ADDRESS_ADDRESS_FILLED = 'addressFilled';
    protected const KEY_ADDRESS_TELEPHONE = 'telephone';
    protected const KEY_ADDRESS_FIRST_NAME = 'firstName';
    protected const KEY_ADDRESS_LAST_NAME = 'lastName';

    /**
     * @var \Shopsys\FrameworkBundle\Model\Customer\User\CustomerUserFacade
     */
    protected $customerUserFacade;

    /**
     * @var \Faker\Generator
     */
    protected $faker;

    /**
     * @var \Shopsys\FrameworkBundle\Component\EntityExtension\EntityManagerDecorator
     */
    protected $em;

    /**
     * @var \Shopsys\FrameworkBundle\Component\String\HashGenerator
     */
    protected $hashGenerator;

    /**
     * @var \Shopsys\FrameworkBundle\Component\Domain\Domain
     */
    protected $domain;

    /**
     * @var \App\Model\Customer\User\CustomerUserUpdateDataFactory
     */
    protected $customerUserUpdateDataFactory;

    /**
     * @var \App\Model\Customer\User\CustomerUserDataFactory
     */
    protected $customerUserDataFactory;

    /**
     * @var \App\Model\Customer\BillingAddressDataFactory
     */
    protected $billingAddressDataFactory;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Customer\DeliveryAddressDataFactoryInterface
     */
    protected $deliveryAddressDataFactory;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Customer\CustomerFactoryInterface
     */
    protected $customerFactory;

    /**
     * @param \Shopsys\FrameworkBundle\Model\Customer\User\CustomerUserFacade $customerUserFacade
     * @param \Faker\Generator $faker
     * @param \Shopsys\FrameworkBundle\Component\EntityExtension\EntityManagerDecorator $em
     * @param \Shopsys\FrameworkBundle\Component\String\HashGenerator $hashGenerator
     * @param \Shopsys\FrameworkBundle\Component\Domain\Domain $domain
     * @param \App\Model\Customer\User\CustomerUserUpdateDataFactory $customerUserUpdateDataFactory
     * @param \App\Model\Customer\User\CustomerUserDataFactory $customerUserDataFactory
     * @param \App\Model\Customer\BillingAddressDataFactory $billingAddressDataFactory
     * @param \Shopsys\FrameworkBundle\Model\Customer\DeliveryAddressDataFactoryInterface $deliveryAddressDataFactory
     * @param \Shopsys\FrameworkBundle\Model\Customer\CustomerFactoryInterface $customerFactory
     */
    public function __construct(
        CustomerUserFacade $customerUserFacade,
        Generator $faker,
        EntityManagerInterface $em,
        HashGenerator $hashGenerator,
        Domain $domain,
        CustomerUserUpdateDataFactoryInterface $customerUserUpdateDataFactory,
        CustomerUserDataFactoryInterface $customerUserDataFactory,
        BillingAddressDataFactoryInterface $billingAddressDataFactory,
        DeliveryAddressDataFactoryInterface $deliveryAddressDataFactory,
        CustomerFactoryInterface $customerFactory
    ) {
        $this->customerUserFacade = $customerUserFacade;
        $this->faker = $faker;
        $this->em = $em;
        $this->hashGenerator = $hashGenerator;
        $this->domain = $domain;
        $this->customerUserUpdateDataFactory = $customerUserUpdateDataFactory;
        $this->customerUserDataFactory = $customerUserDataFactory;
        $this->billingAddressDataFactory = $billingAddressDataFactory;
        $this->deliveryAddressDataFactory = $deliveryAddressDataFactory;
        $this->customerFactory = $customerFactory;
    }

    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->domain->getAll() as $domainConfig) {
            $domainId = $domainConfig->getId();
            if ($domainId === Domain::SECOND_DOMAIN_ID) {
                $customersDataProvider = $this->getDistinctCustomerUsersDataProvider();
            } else {
                $customersDataProvider = $this->getDefaultCustomerUsersDataProvider();
            }

            foreach ($customersDataProvider as $customerDataProvider) {
                $customerUserUpdateData = $this->getCustomerUserUpdateData($domainId, $customerDataProvider);
                $customerUserUpdateData->customerUserData->createdAt = $this->faker->dateTimeBetween('-1 week', 'now');

                $customer = $this->customerUserFacade->create($customerUserUpdateData);
                if ($customer->getId() === 1) {
                    $this->resetPassword($customer);
                    $this->addReference(self::USER_WITH_RESET_PASSWORD_HASH, $customer);
                }
            }
        }
    }

    /**
     * @param int $domainId
     * @param array $data
     *
     * @return \App\Model\Customer\User\CustomerUserUpdateData
     */
    protected function getCustomerUserUpdateData(int $domainId, array $data): CustomerUserUpdateData
    {
        $customerUserUpdateData = $this->customerUserUpdateDataFactory->create();
        $customerUserData = $this->customerUserDataFactory->createForDomainId($domainId);
        $customerUserData->firstName = $data[self::KEY_CUSTOMER_USER_DATA][self::KEY_CUSTOMER_USER_DATA_FIRST_NAME] ?? null;
        $customerUserData->lastName = $data[self::KEY_CUSTOMER_USER_DATA][self::KEY_CUSTOMER_USER_DATA_LAST_NAME] ?? null;
        $customerUserData->email = $data[self::KEY_CUSTOMER_USER_DATA][self::KEY_CUSTOMER_USER_DATA_EMAIL] ?? null;
        $customerUserData->password = $data[self::KEY_CUSTOMER_USER_DATA][self::KEY_CUSTOMER_USER_DATA_PASSWORD] ?? null;
        $customerUserData->telephone = $data[self::KEY_CUSTOMER_USER_DATA][self::KEY_CUSTOMER_USER_DATA_TELEPHONE] ?? null;
        $customerUserData->customer = $customerUserUpdateData->customerUserData->customer;

        $billingAddressData = $customerUserUpdateData->billingAddressData;
        $billingAddressData->companyCustomer = $data[self::KEY_BILLING_ADDRESS][self::KEY_ADDRESS_COMPANY_CUSTOMER];
        $billingAddressData->companyName = $data[self::KEY_BILLING_ADDRESS][self::KEY_ADDRESS_COMPANY_NAME] ?? null;
        $billingAddressData->companyNumber = $data[self::KEY_BILLING_ADDRESS][self::KEY_ADDRESS_COMPANY_NUMBER] ?? null;
        $billingAddressData->city = $data[self::KEY_BILLING_ADDRESS][self::KEY_ADDRESS_CITY] ?? null;
        $billingAddressData->street = $data[self::KEY_BILLING_ADDRESS][self::KEY_ADDRESS_STREET] ?? null;
        $billingAddressData->postcode = $data[self::KEY_BILLING_ADDRESS][self::KEY_ADDRESS_POSTCODE] ?? null;
        $billingAddressData->country = $data[self::KEY_BILLING_ADDRESS][self::KEY_ADDRESS_COUNTRY];

        if (isset($data[self::KEY_DELIVERY_ADDRESS])) {
            $deliveryAddressData = $customerUserUpdateData->deliveryAddressData;
            $deliveryAddressData->addressFilled = $data[self::KEY_DELIVERY_ADDRESS][self::KEY_ADDRESS_ADDRESS_FILLED] ?? null;
            $deliveryAddressData->companyName = $data[self::KEY_DELIVERY_ADDRESS][self::KEY_ADDRESS_COMPANY_NAME] ?? null;
            $deliveryAddressData->firstName = $data[self::KEY_DELIVERY_ADDRESS][self::KEY_ADDRESS_FIRST_NAME] ?? null;
            $deliveryAddressData->lastName = $data[self::KEY_DELIVERY_ADDRESS][self::KEY_ADDRESS_LAST_NAME] ?? null;
            $deliveryAddressData->city = $data[self::KEY_DELIVERY_ADDRESS][self::KEY_ADDRESS_CITY] ?? null;
            $deliveryAddressData->postcode = $data[self::KEY_DELIVERY_ADDRESS][self::KEY_ADDRESS_POSTCODE] ?? null;
            $deliveryAddressData->street = $data[self::KEY_DELIVERY_ADDRESS][self::KEY_ADDRESS_STREET] ?? null;
            $deliveryAddressData->telephone = $data[self::KEY_DELIVERY_ADDRESS][self::KEY_ADDRESS_TELEPHONE] ?? null;
            $deliveryAddressData->country = $data[self::KEY_DELIVERY_ADDRESS][self::KEY_ADDRESS_COUNTRY];
        }

        $customerUserUpdateData->customerUserData = $customerUserData;
        $customerUserUpdateData->billingAddressData = $billingAddressData;

        return $customerUserUpdateData;
    }

    /**
     * @return array
     */
    protected function getDefaultCustomerUsersDataProvider(): array
    {
        return [
            [
                self::KEY_CUSTOMER_USER_DATA => [
                    self::KEY_CUSTOMER_USER_DATA_FIRST_NAME => 'Jaromír',
                    self::KEY_CUSTOMER_USER_DATA_LAST_NAME => 'Jágr',
                    self::KEY_CUSTOMER_USER_DATA_EMAIL => 'no-reply@shopsys.com',
                    self::KEY_CUSTOMER_USER_DATA_PASSWORD => 'user123',
                    self::KEY_CUSTOMER_USER_DATA_TELEPHONE => '605000123',
                ],
                self::KEY_BILLING_ADDRESS => [
                    self::KEY_ADDRESS_COMPANY_CUSTOMER => true,
                    self::KEY_ADDRESS_COMPANY_NAME => 'Shopsys',
                    self::KEY_ADDRESS_COMPANY_NUMBER => '123456',
                    self::KEY_ADDRESS_STREET => 'Hlubinská',
                    self::KEY_ADDRESS_CITY => 'Ostrava',
                    self::KEY_ADDRESS_POSTCODE => '70200',
                    self::KEY_ADDRESS_COUNTRY => $this->getReference(CountryDataFixture::COUNTRY_CZECH_REPUBLIC),
                ],
            ],
            [
                self::KEY_CUSTOMER_USER_DATA => [
                    self::KEY_CUSTOMER_USER_DATA_FIRST_NAME => 'Igor',
                    self::KEY_CUSTOMER_USER_DATA_LAST_NAME => 'Anpilogov',
                    self::KEY_CUSTOMER_USER_DATA_EMAIL => 'no-reply.3@shopsys.com',
                    self::KEY_CUSTOMER_USER_DATA_PASSWORD => 'no-reply.3',
                ],
                self::KEY_BILLING_ADDRESS => [
                    self::KEY_ADDRESS_COMPANY_CUSTOMER => false,
                    self::KEY_ADDRESS_CITY => 'Budišov nad Budišovkou',
                    self::KEY_ADDRESS_COUNTRY => $this->getReference(CountryDataFixture::COUNTRY_CZECH_REPUBLIC),
                ],
            ],
            [
                self::KEY_CUSTOMER_USER_DATA => [
                    self::KEY_CUSTOMER_USER_DATA_FIRST_NAME => 'Hana',
                    self::KEY_CUSTOMER_USER_DATA_LAST_NAME => 'Anrejsová',
                    self::KEY_CUSTOMER_USER_DATA_EMAIL => 'no-reply.5@shopsys.com',
                    self::KEY_CUSTOMER_USER_DATA_PASSWORD => 'no-reply.5',
                ],
                self::KEY_BILLING_ADDRESS => [
                    self::KEY_ADDRESS_COMPANY_CUSTOMER => false,
                    self::KEY_ADDRESS_CITY => 'Brno',
                    self::KEY_ADDRESS_COUNTRY => $this->getReference(CountryDataFixture::COUNTRY_CZECH_REPUBLIC),
                ],
            ],
            [
                self::KEY_CUSTOMER_USER_DATA => [
                    self::KEY_CUSTOMER_USER_DATA_FIRST_NAME => 'Alexandr',
                    self::KEY_CUSTOMER_USER_DATA_LAST_NAME => 'Ton',
                    self::KEY_CUSTOMER_USER_DATA_EMAIL => 'no-reply.9@shopsys.com',
                    self::KEY_CUSTOMER_USER_DATA_PASSWORD => 'no-reply.9',
                    self::KEY_CUSTOMER_USER_DATA_TELEPHONE => '606060606',
                ],
                self::KEY_BILLING_ADDRESS => [
                    self::KEY_ADDRESS_COMPANY_CUSTOMER => false,
                    self::KEY_ADDRESS_CITY => 'Bohumín',
                    self::KEY_ADDRESS_STREET => 'Na Strzi 3',
                    self::KEY_ADDRESS_POSTCODE => '69084',
                    self::KEY_ADDRESS_COUNTRY => $this->getReference(CountryDataFixture::COUNTRY_CZECH_REPUBLIC),
                ],
            ],
            [
                self::KEY_CUSTOMER_USER_DATA => [
                    self::KEY_CUSTOMER_USER_DATA_FIRST_NAME => 'Pavel',
                    self::KEY_CUSTOMER_USER_DATA_LAST_NAME => 'Nedvěd',
                    self::KEY_CUSTOMER_USER_DATA_EMAIL => 'no-reply.10@shopsys.com',
                    self::KEY_CUSTOMER_USER_DATA_PASSWORD => 'no-reply.10',
                    self::KEY_CUSTOMER_USER_DATA_TELEPHONE => '606060606',
                ],
                self::KEY_BILLING_ADDRESS => [
                    self::KEY_ADDRESS_COMPANY_CUSTOMER => false,
                    self::KEY_ADDRESS_CITY => 'Turín',
                    self::KEY_ADDRESS_STREET => 'Turínská 5',
                    self::KEY_ADDRESS_POSTCODE => '12345',
                    self::KEY_ADDRESS_COUNTRY => $this->getReference(CountryDataFixture::COUNTRY_CZECH_REPUBLIC),
                ],
                self::KEY_DELIVERY_ADDRESS => [
                    self::KEY_ADDRESS_CITY => 'Bahamy',
                    self::KEY_ADDRESS_POSTCODE => '99999',
                    self::KEY_ADDRESS_STREET => 'Bahamská 99',
                    self::KEY_ADDRESS_COUNTRY => $this->getReference(CountryDataFixture::COUNTRY_CZECH_REPUBLIC),
                ],
            ],
            [
                self::KEY_CUSTOMER_USER_DATA => [
                    self::KEY_CUSTOMER_USER_DATA_FIRST_NAME => 'Rostislav',
                    self::KEY_CUSTOMER_USER_DATA_LAST_NAME => 'Vítek',
                    self::KEY_CUSTOMER_USER_DATA_EMAIL => 'vitek@shopsys.com',
                    self::KEY_CUSTOMER_USER_DATA_PASSWORD => 'user123',
                    self::KEY_CUSTOMER_USER_DATA_TELEPHONE => '606060606',
                ],
                self::KEY_BILLING_ADDRESS => [
                    self::KEY_ADDRESS_COMPANY_CUSTOMER => true,
                    self::KEY_ADDRESS_COMPANY_NAME => 'Shopsys',
                    self::KEY_ADDRESS_CITY => 'Ostrava',
                    self::KEY_ADDRESS_STREET => 'Hlubinská 5',
                    self::KEY_ADDRESS_POSTCODE => '70200',
                    self::KEY_ADDRESS_COUNTRY => $this->getReference(CountryDataFixture::COUNTRY_CZECH_REPUBLIC),
                ],
                self::KEY_DELIVERY_ADDRESS => [
                    self::KEY_ADDRESS_ADDRESS_FILLED => true,
                    self::KEY_ADDRESS_COMPANY_NAME => 'Rockpoint',
                    self::KEY_ADDRESS_FIRST_NAME => 'Eva',
                    self::KEY_ADDRESS_LAST_NAME => 'Wallicová',
                    self::KEY_ADDRESS_CITY => 'Ostrava',
                    self::KEY_ADDRESS_POSTCODE => '70030',
                    self::KEY_ADDRESS_STREET => 'Rudná',
                    self::KEY_ADDRESS_TELEPHONE => '123456789',
                    self::KEY_ADDRESS_COUNTRY => $this->getReference(CountryDataFixture::COUNTRY_CZECH_REPUBLIC),
                ],
            ],
            [
                self::KEY_CUSTOMER_USER_DATA => [
                    self::KEY_CUSTOMER_USER_DATA_FIRST_NAME => 'Ľubomír',
                    self::KEY_CUSTOMER_USER_DATA_LAST_NAME => 'Novák',
                    self::KEY_CUSTOMER_USER_DATA_EMAIL => 'no-reply.11@shopsys.com',
                    self::KEY_CUSTOMER_USER_DATA_PASSWORD => 'test123',
                    self::KEY_CUSTOMER_USER_DATA_TELEPHONE => '606060606',
                ],
                self::KEY_BILLING_ADDRESS => [
                    self::KEY_ADDRESS_COMPANY_CUSTOMER => false,
                    self::KEY_ADDRESS_CITY => 'Bratislava',
                    self::KEY_ADDRESS_STREET => 'Brněnská',
                    self::KEY_ADDRESS_POSTCODE => '1010',
                    self::KEY_ADDRESS_COUNTRY => $this->getReference(CountryDataFixture::COUNTRY_SLOVAKIA),
                ],
                self::KEY_DELIVERY_ADDRESS => [
                    self::KEY_ADDRESS_ADDRESS_FILLED => true,
                    self::KEY_ADDRESS_COMPANY_NAME => 'Rockpoint',
                    self::KEY_ADDRESS_CITY => 'Bratislava',
                    self::KEY_ADDRESS_POSTCODE => '10100',
                    self::KEY_ADDRESS_STREET => 'Ostravská 55/65A',
                    self::KEY_ADDRESS_TELEPHONE => '758686320',
                    self::KEY_ADDRESS_COUNTRY => $this->getReference(CountryDataFixture::COUNTRY_SLOVAKIA),
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getDistinctCustomerUsersDataProvider(): array
    {
        return [
            [
                self::KEY_CUSTOMER_USER_DATA => [
                    self::KEY_CUSTOMER_USER_DATA_FIRST_NAME => 'Jana',
                    self::KEY_CUSTOMER_USER_DATA_LAST_NAME => 'Anovčínová',
                    self::KEY_CUSTOMER_USER_DATA_EMAIL => 'no-reply.2@shopsys.com',
                    self::KEY_CUSTOMER_USER_DATA_PASSWORD => 'no-reply.2',
                ],
                self::KEY_BILLING_ADDRESS => [
                    self::KEY_ADDRESS_COMPANY_CUSTOMER => false,
                    self::KEY_ADDRESS_CITY => 'Aš',
                    self::KEY_ADDRESS_COUNTRY => $this->getReference(CountryDataFixture::COUNTRY_CZECH_REPUBLIC),
                ],
            ],
            [
                self::KEY_CUSTOMER_USER_DATA => [
                    self::KEY_CUSTOMER_USER_DATA_FIRST_NAME => 'Ida',
                    self::KEY_CUSTOMER_USER_DATA_LAST_NAME => 'Anpilogova',
                    self::KEY_CUSTOMER_USER_DATA_EMAIL => 'no-reply.4@shopsys.com',
                    self::KEY_CUSTOMER_USER_DATA_PASSWORD => 'no-reply.4',
                ],
                self::KEY_BILLING_ADDRESS => [
                    self::KEY_ADDRESS_COMPANY_CUSTOMER => false,
                    self::KEY_ADDRESS_CITY => 'Praha',
                    self::KEY_ADDRESS_COUNTRY => $this->getReference(CountryDataFixture::COUNTRY_CZECH_REPUBLIC),
                ],
            ],
            [
                self::KEY_CUSTOMER_USER_DATA => [
                    self::KEY_CUSTOMER_USER_DATA_FIRST_NAME => 'Petr',
                    self::KEY_CUSTOMER_USER_DATA_LAST_NAME => 'Anrig',
                    self::KEY_CUSTOMER_USER_DATA_EMAIL => 'no-reply.6@shopsys.com',
                    self::KEY_CUSTOMER_USER_DATA_PASSWORD => 'no-reply.6',
                ],
                self::KEY_BILLING_ADDRESS => [
                    self::KEY_ADDRESS_COMPANY_CUSTOMER => false,
                    self::KEY_ADDRESS_CITY => 'Jeseník',
                    self::KEY_ADDRESS_COUNTRY => $this->getReference(CountryDataFixture::COUNTRY_CZECH_REPUBLIC),
                ],
                self::KEY_DELIVERY_ADDRESS => [
                    self::KEY_ADDRESS_ADDRESS_FILLED => true,
                    self::KEY_ADDRESS_CITY => 'Opava',
                    self::KEY_ADDRESS_POSTCODE => '70000',
                    self::KEY_ADDRESS_STREET => 'Ostravská',
                    self::KEY_ADDRESS_COUNTRY => $this->getReference(CountryDataFixture::COUNTRY_CZECH_REPUBLIC),
                ],
            ],
            [
                self::KEY_CUSTOMER_USER_DATA => [
                    self::KEY_CUSTOMER_USER_DATA_FIRST_NAME => 'Silva',
                    self::KEY_CUSTOMER_USER_DATA_LAST_NAME => 'Anrigová',
                    self::KEY_CUSTOMER_USER_DATA_EMAIL => 'no-reply.7@shopsys.com',
                    self::KEY_CUSTOMER_USER_DATA_PASSWORD => 'no-reply.7',
                ],
                self::KEY_BILLING_ADDRESS => [
                    self::KEY_ADDRESS_COMPANY_CUSTOMER => false,
                    self::KEY_ADDRESS_CITY => 'Ostrava',
                    self::KEY_ADDRESS_COUNTRY => $this->getReference(CountryDataFixture::COUNTRY_CZECH_REPUBLIC),
                ],
            ],
            [
                self::KEY_CUSTOMER_USER_DATA => [
                    self::KEY_CUSTOMER_USER_DATA_FIRST_NAME => 'Derick',
                    self::KEY_CUSTOMER_USER_DATA_LAST_NAME => 'Ansah',
                    self::KEY_CUSTOMER_USER_DATA_EMAIL => 'no-reply.8@shopsys.com',
                    self::KEY_CUSTOMER_USER_DATA_PASSWORD => 'no-reply.8',
                ],
                self::KEY_BILLING_ADDRESS => [
                    self::KEY_ADDRESS_COMPANY_CUSTOMER => false,
                    self::KEY_ADDRESS_CITY => 'Opava',
                    self::KEY_ADDRESS_COUNTRY => $this->getReference(CountryDataFixture::COUNTRY_CZECH_REPUBLIC),
                ],
            ],
            [
                self::KEY_CUSTOMER_USER_DATA => [
                    self::KEY_CUSTOMER_USER_DATA_FIRST_NAME => 'Johny',
                    self::KEY_CUSTOMER_USER_DATA_LAST_NAME => 'English',
                    self::KEY_CUSTOMER_USER_DATA_EMAIL => 'no-reply@shopsys.com',
                    self::KEY_CUSTOMER_USER_DATA_PASSWORD => 'user123',
                    self::KEY_CUSTOMER_USER_DATA_TELEPHONE => '603123456',
                ],
                self::KEY_BILLING_ADDRESS => [
                    self::KEY_ADDRESS_COMPANY_CUSTOMER => true,
                    self::KEY_ADDRESS_COMPANY_NAME => 'Shopsys',
                    self::KEY_ADDRESS_CITY => 'Ostrava',
                    self::KEY_ADDRESS_STREET => 'Hlubinská',
                    self::KEY_ADDRESS_POSTCODE => '70200',
                    self::KEY_ADDRESS_COUNTRY => $this->getReference(CountryDataFixture::COUNTRY_CZECH_REPUBLIC),
                ],
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getDependencies()
    {
        return [
            CountryDataFixture::class,
        ];
    }

    /**
     * @param \App\Model\Customer\User\CustomerUser $customer
     */
    protected function resetPassword(CustomerUser $customer)
    {
        $resetPasswordHash = $this->hashGenerator->generateHash(CustomerUserPasswordFacade::RESET_PASSWORD_HASH_LENGTH);
        $customer->setResetPasswordHash($resetPasswordHash);
        $this->em->flush($customer);
    }
}
