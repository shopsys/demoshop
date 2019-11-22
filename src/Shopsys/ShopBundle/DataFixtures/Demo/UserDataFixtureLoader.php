<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\DataFixtures\Demo;

use Shopsys\FrameworkBundle\Component\Csv\CsvReader;
use Shopsys\FrameworkBundle\Component\Domain\Domain;
use Shopsys\FrameworkBundle\Component\String\EncodingConverter;
use Shopsys\FrameworkBundle\Component\String\TransformString;
use Shopsys\FrameworkBundle\Model\Country\Country;
use Shopsys\FrameworkBundle\Model\Customer\BillingAddressDataFactoryInterface;
use Shopsys\FrameworkBundle\Model\Customer\CustomerDataFactoryInterface;
use Shopsys\FrameworkBundle\Model\Customer\DeliveryAddressDataFactoryInterface;
use Shopsys\FrameworkBundle\Model\Customer\UserDataFactoryInterface;

class UserDataFixtureLoader
{
    public const COLUMN_FIRSTNAME = 0;
    public const COLUMN_LASTNAME = 1;
    public const COLUMN_EMAIL = 2;
    public const COLUMN_PASSWORD = 3;
    public const COLUMN_COMPANY_CUSTOMER = 4;
    public const COLUMN_COMPANY_NAME = 5;
    public const COLUMN_COMPANY_NUMBER = 6;
    public const COLUMN_COMPANY_TAX_NUMBER = 7;
    public const COLUMN_STREET = 8;
    public const COLUMN_CITY = 9;
    public const COLUMN_POSTCODE = 10;
    public const COLUMN_TELEPHONE = 11;
    public const COLUMN_COUNTRY = 12;
    public const COLUMN_DELIVERY_ADDRESS_FILLED = 13;
    public const COLUMN_DELIVERY_CITY = 14;
    public const COLUMN_DELIVERY_COMPANY_NAME = 15;
    public const COLUMN_DELIVERY_FIRST_NAME = 16;
    public const COLUMN_DELIVERY_LAST_NAME = 17;
    public const COLUMN_DELIVERY_POSTCODE = 18;
    public const COLUMN_DELIVERY_STREET = 19;
    public const COLUMN_DELIVERY_TELEPHONE = 20;
    public const COLUMN_DELIVERY_COUNTRY = 21;
    public const COLUMN_DOMAIN_ID = 22;

    /**
     * @var \Shopsys\FrameworkBundle\Component\Csv\CsvReader
     */
    protected $csvReader;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var \Shopsys\ShopBundle\Model\Customer\UserDataFactory
     */
    protected $userDataFactory;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Country\Country[]
     */
    protected $countries;

    /**
     * @var \Shopsys\ShopBundle\Model\Customer\CustomerDataFactory
     */
    protected $customerDataFactory;

    /**
     * @var \Shopsys\ShopBundle\Model\Customer\BillingAddressDataFactory
     */
    protected $billingAddressDataFactory;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Customer\DeliveryAddressDataFactoryInterface
     */
    protected $deliveryAddressDataFactory;

    /**
     * @var \Shopsys\FrameworkBundle\Component\Domain\Domain
     */
    protected $domain;

    /**
     * @param string $path
     * @param \Shopsys\FrameworkBundle\Component\Csv\CsvReader $csvReader
     * @param \Shopsys\ShopBundle\Model\Customer\UserDataFactory $userDataFactory
     * @param \Shopsys\ShopBundle\Model\Customer\CustomerDataFactory $customerDataFactory
     * @param \Shopsys\ShopBundle\Model\Customer\BillingAddressDataFactory $billingAddressDataFactory
     * @param \Shopsys\FrameworkBundle\Model\Customer\DeliveryAddressDataFactoryInterface $deliveryAddressDataFactory
     * @param \Shopsys\FrameworkBundle\Component\Domain\Domain $domain
     */
    public function __construct(
        $path,
        CsvReader $csvReader,
        UserDataFactoryInterface $userDataFactory,
        CustomerDataFactoryInterface $customerDataFactory,
        BillingAddressDataFactoryInterface $billingAddressDataFactory,
        DeliveryAddressDataFactoryInterface $deliveryAddressDataFactory,
        Domain $domain
    ) {
        $this->path = $path;
        $this->csvReader = $csvReader;
        $this->userDataFactory = $userDataFactory;
        $this->customerDataFactory = $customerDataFactory;
        $this->billingAddressDataFactory = $billingAddressDataFactory;
        $this->deliveryAddressDataFactory = $deliveryAddressDataFactory;
        $this->domain = $domain;
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Country\Country[] $countries
     */
    public function injectReferences(array $countries)
    {
        $this->countries = $countries;
    }

    /**
     * @param int $domainId
     * @return  \Shopsys\ShopBundle\Model\Customer\CustomerData[]
     */
    public function getCustomersDataByDomainId($domainId)
    {
        $rows = $this->csvReader->getRowsFromCsv($this->path);
        $filteredRows = $this->filterRowsByDomainId($rows, $domainId);

        $customersData = [];
        foreach ($filteredRows as $row) {
            $row = array_map([TransformString::class, 'emptyToNull'], $row);
            $row = EncodingConverter::cp1250ToUtf8($row);
            $customersData[] = $this->getCustomerDataFromCsvRow($row);
        }

        return $customersData;
    }

    /**
     * @param array $rows
     * @param int $domainId
     * @return array
     */
    protected function filterRowsByDomainId(array $rows, $domainId)
    {
        $filteredRows = [];
        $rowId = 0;
        foreach ($rows as $row) {
            $rowId++;
            if ($rowId === 1) {
                // skip header
                continue;
            }

            if ((int)$row[self::COLUMN_DOMAIN_ID] !== $domainId) {
                // filter by domain ID
                continue;
            }

            $filteredRows[] = $row;
        }

        return $filteredRows;
    }

    /**
     * @param array $row
     * @return \Shopsys\ShopBundle\Model\Customer\CustomerData
     */
    protected function getCustomerDataFromCsvRow(array $row)
    {
        $customerData = $this->customerDataFactory->create();
        $domainId = (int)$row[self::COLUMN_DOMAIN_ID];
        $userData = $this->userDataFactory->createForDomainId($domainId);
        $billingAddressData = $this->billingAddressDataFactory->create();

        $userData->firstName = $row[self::COLUMN_FIRSTNAME];
        $userData->lastName = $row[self::COLUMN_LASTNAME];
        $userData->email = $row[self::COLUMN_EMAIL];
        $userData->password = $row[self::COLUMN_PASSWORD];
        $userData->telephone = $row[self::COLUMN_TELEPHONE];

        $billingAddressData->companyCustomer = $row[self::COLUMN_COMPANY_CUSTOMER];
        $billingAddressData->companyName = $row[self::COLUMN_COMPANY_NAME];
        $billingAddressData->companyNumber = $row[self::COLUMN_COMPANY_NUMBER];
        $billingAddressData->companyTaxNumber = $row[self::COLUMN_COMPANY_TAX_NUMBER];
        $billingAddressData->street = $row[self::COLUMN_STREET];
        $billingAddressData->city = $row[self::COLUMN_CITY];
        $billingAddressData->postcode = $row[self::COLUMN_POSTCODE];
        $billingAddressData->country = $this->getCountryByNameAndDomain($row[self::COLUMN_COUNTRY], $domainId);
        if ($row[self::COLUMN_DELIVERY_ADDRESS_FILLED] === 'true') {
            $deliveryAddressData = $this->deliveryAddressDataFactory->create();
            $deliveryAddressData->addressFilled = true;
            $deliveryAddressData->city = $row[self::COLUMN_DELIVERY_CITY];
            $deliveryAddressData->companyName = $row[self::COLUMN_DELIVERY_COMPANY_NAME];
            $deliveryAddressData->firstName = $row[self::COLUMN_DELIVERY_FIRST_NAME];
            $deliveryAddressData->lastName = $row[self::COLUMN_DELIVERY_LAST_NAME];
            $deliveryAddressData->postcode = $row[self::COLUMN_DELIVERY_POSTCODE];
            $deliveryAddressData->street = $row[self::COLUMN_DELIVERY_STREET];
            $deliveryAddressData->telephone = $row[self::COLUMN_DELIVERY_TELEPHONE];
            $deliveryAddressData->country = $this->getCountryByNameAndDomain($row[self::COLUMN_DELIVERY_COUNTRY], $domainId);
            $customerData->deliveryAddressData = $deliveryAddressData;
        } else {
            $customerData->deliveryAddressData = $this->deliveryAddressDataFactory->create();
        }
        $userData->domainId = $domainId;

        $customerData->userData = $userData;
        $customerData->billingAddressData = $billingAddressData;

        return $customerData;
    }

    /**
     * @param string $countryName
     * @param int $domainId
     * @return \Shopsys\FrameworkBundle\Model\Country\Country
     */
    protected function getCountryByNameAndDomain(string $countryName, int $domainId): Country
    {
        $locale = $this->domain->getDomainConfigById($domainId)->getLocale();

        foreach ($this->countries as $country) {
            if ($country->getName($locale) === $countryName) {
                return $country;
            }
        }

        $message = 'Country with name "' . $countryName . '" was not found.';
        throw new \Shopsys\FrameworkBundle\Model\Country\Exception\CountryNotFoundException($message);
    }
}
