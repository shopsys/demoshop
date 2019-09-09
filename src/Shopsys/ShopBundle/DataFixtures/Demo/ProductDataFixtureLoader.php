<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\DataFixtures\Demo;

use DateTime;
use Shopsys\FrameworkBundle\Component\Domain\Domain;
use Shopsys\FrameworkBundle\Component\Domain\Exception\InvalidDomainIdException;
use Shopsys\FrameworkBundle\Component\Money\Money;
use Shopsys\FrameworkBundle\Model\Pricing\Group\PricingGroupFacade;
use Shopsys\FrameworkBundle\Model\Product\Product;
use Shopsys\FrameworkBundle\Model\Product\ProductData;
use Shopsys\FrameworkBundle\Model\Product\ProductDataFactoryInterface;

class ProductDataFixtureLoader
{
    public const COLUMN_NAME_CS = 0;
    public const COLUMN_NAME_EN = 1;
    public const COLUMN_NAME_DE = 2;
    public const COLUMN_CATNUM = 3;
    public const COLUMN_PARTNO = 4;
    public const COLUMN_EAN = 5;
    public const COLUMN_DESCRIPTION_CS = 6;
    public const COLUMN_DESCRIPTION_EN = 7;
    public const COLUMN_DESCRIPTION_DE = 8;
    public const COLUMN_SHORT_DESCRIPTION_CS = 9;
    public const COLUMN_SHORT_DESCRIPTION_EN = 10;
    public const COLUMN_SHORT_DESCRIPTION_DE = 11;
    public const COLUMN_MANUAL_PRICES_DOMAIN_1 = 12;
    public const COLUMN_MANUAL_PRICES_DOMAIN_2 = 13;
    public const COLUMN_MANUAL_PRICES_DOMAIN_3 = 14;
    public const COLUMN_VAT = 15;
    public const COLUMN_SELLING_FROM = 16;
    public const COLUMN_SELLING_TO = 17;
    public const COLUMN_STOCK_QUANTITY = 18;
    public const COLUMN_UNIT = 19;
    public const COLUMN_AVAILABILITY = 20;
    public const COLUMN_PARAMETERS = 21;
    public const COLUMN_CATEGORIES_1 = 22;
    public const COLUMN_CATEGORIES_2 = 23;
    public const COLUMN_CATEGORIES_3 = 24;
    public const COLUMN_FLAGS = 25;
    public const COLUMN_SELLING_DENIED = 26;
    public const COLUMN_BRAND = 27;
    public const COLUMN_MAIN_VARIANT_CATNUM = 28;

    /**
     * @var \Shopsys\ShopBundle\DataFixtures\Demo\ProductParametersFixtureLoader
     */
    protected $productParametersFixtureLoader;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Pricing\Vat\Vat[]
     */
    protected $vats;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Product\Availability\Availability[]
     */
    protected $availabilities;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Category\Category[]
     */
    protected $categories;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Product\Flag\Flag[]
     */
    protected $flags;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Product\Brand\Brand[]
     */
    protected $brands;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Product\Unit\Unit[]
     */
    protected $units;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Pricing\Group\PricingGroup[]
     */
    protected $pricingGroups;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Product\ProductDataFactoryInterface
     */
    protected $productDataFactory;

    /**
     * @var \Shopsys\FrameworkBundle\Component\Domain\Domain
     */
    protected $domain;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Pricing\Group\PricingGroupFacade
     */
    protected $pricingGroupFacade;

    /**
     * @param \Shopsys\ShopBundle\DataFixtures\Demo\ProductParametersFixtureLoader $productParametersFixtureLoader
     * @param \Shopsys\FrameworkBundle\Model\Product\ProductDataFactoryInterface $productDataFactory
     * @param \Shopsys\FrameworkBundle\Component\Domain\Domain $domain
     * @param \Shopsys\FrameworkBundle\Model\Pricing\Group\PricingGroupFacade $pricingGroupFacade
     */
    public function __construct(
        ProductParametersFixtureLoader $productParametersFixtureLoader,
        ProductDataFactoryInterface $productDataFactory,
        Domain $domain,
        PricingGroupFacade $pricingGroupFacade
    ) {
        $this->productParametersFixtureLoader = $productParametersFixtureLoader;
        $this->productDataFactory = $productDataFactory;
        $this->domain = $domain;
        $this->pricingGroupFacade = $pricingGroupFacade;
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Pricing\Vat\Vat[] $vats
     * @param \Shopsys\FrameworkBundle\Model\Product\Availability\Availability[] $availabilities
     * @param \Shopsys\FrameworkBundle\Model\Category\Category[] $categories
     * @param \Shopsys\FrameworkBundle\Model\Product\Flag\Flag[] $flags
     * @param \Shopsys\FrameworkBundle\Model\Product\Brand\Brand[] $brands
     * @param \Shopsys\FrameworkBundle\Model\Product\Unit\Unit[] $units
     * @param \Shopsys\FrameworkBundle\Model\Pricing\Group\PricingGroup[] $pricingGroups
     */
    public function refreshCachedEntities(
        array $vats,
        array $availabilities,
        array $categories,
        array $flags,
        array $brands,
        array $units,
        array $pricingGroups
    ) {
        $this->vats = $vats;
        $this->availabilities = $availabilities;
        $this->categories = $categories;
        $this->flags = $flags;
        $this->brands = $brands;
        $this->units = $units;
        $this->pricingGroups = $pricingGroups;
        $this->productParametersFixtureLoader->clearCache();
    }

    /**
     * @param array $row
     * @return \Shopsys\FrameworkBundle\Model\Product\ProductData
     */
    public function createProductDataFromRowForFirstDomain($row)
    {
        $productData = $this->productDataFactory->create();
        $this->updateProductDataFromCsvRowForFirstDomain($productData, $row);

        return $productData;
    }

    /**
     * @param array $rows
     * @return string[][]
     */
    public function getVariantCatnumsIndexedByMainVariantCatnum($rows)
    {
        $variantCatnumsByMainVariantCatnum = [];
        foreach ($rows as $row) {
            if ($row[self::COLUMN_MAIN_VARIANT_CATNUM] !== null && $row[self::COLUMN_CATNUM] !== null) {
                $variantCatnumsByMainVariantCatnum[$row[self::COLUMN_MAIN_VARIANT_CATNUM]][] = $row[self::COLUMN_CATNUM];
            }
        }

        return $variantCatnumsByMainVariantCatnum;
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Product\ProductData $productData
     * @param array $row
     */
    protected function updateProductDataFromCsvRowForFirstDomain(ProductData $productData, array $row)
    {
        $domainId = 1;

        $productData->name[$this->domain->getDomainConfigById($domainId)->getLocale()] = $row[$this->getCsvProductColumnNameByDomainId($domainId)];
        $productData->catnum = $row[self::COLUMN_CATNUM];
        $productData->partno = $row[self::COLUMN_PARTNO];
        $productData->ean = $row[self::COLUMN_EAN];
        $productData->descriptions[$domainId] = $row[$this->getDescriptionColumnForDomain($domainId)];
        $productData->shortDescriptions[$domainId] = $row[$this->getShortDescriptionColumnForDomain($domainId)];
        $this->setProductDataPricesFromCsv($row, $productData, $domainId);
        switch ($row[self::COLUMN_VAT]) {
            case 'high':
                $productData->vat = $this->vats['high'];
                break;
            case 'low':
                $productData->vat = $this->vats['low'];
                break;
            case 'second_low':
                $productData->vat = $this->vats['second_low'];
                break;
            case 'zero':
                $productData->vat = $this->vats['zero'];
                break;
            default:
                $productData->vat = null;
        }
        if ($row[self::COLUMN_SELLING_FROM] !== null) {
            $productData->sellingFrom = new DateTime($row[self::COLUMN_SELLING_FROM]);
        }
        if ($row[self::COLUMN_SELLING_TO] !== null) {
            $productData->sellingTo = new DateTime($row[self::COLUMN_SELLING_TO]);
        }
        $productData->usingStock = $row[self::COLUMN_STOCK_QUANTITY] !== null;
        $productData->stockQuantity = $row[self::COLUMN_STOCK_QUANTITY];
        $productData->unit = $this->units[$row[self::COLUMN_UNIT]];
        $productData->outOfStockAction = Product::OUT_OF_STOCK_ACTION_HIDE;
        switch ($row[self::COLUMN_AVAILABILITY]) {
            case 'in-stock':
                $productData->availability = $this->availabilities['in-stock'];
                break;
            case 'out-of-stock':
                $productData->availability = $this->availabilities['out-of-stock'];
                break;
            case 'on-request':
                $productData->availability = $this->availabilities['on-request'];
                break;
        }
        $productData->parameters = $this->productParametersFixtureLoader->getProductParameterValuesDataFromString(
            $row[self::COLUMN_PARAMETERS]
        );
        foreach ($this->domain->getAllIds() as $domainId) {
            $productData->categoriesByDomainId[$domainId] =
                $this->getValuesByKeyString($row[self::COLUMN_CATEGORIES_1], $this->categories);
        }
        $productData->flags = $this->getValuesByKeyString($row[self::COLUMN_FLAGS], $this->flags);
        $productData->sellingDenied = $row[self::COLUMN_SELLING_DENIED];

        if ($row[self::COLUMN_BRAND] !== null) {
            $productData->brand = $this->brands[$row[self::COLUMN_BRAND]];
        }
    }

    /**
     * @param array $row
     * @return string
     */
    public function getCatnumFromRow($row)
    {
        return $row[self::COLUMN_CATNUM];
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Product\ProductData $productData
     * @param array $row
     */
    public function updateProductDataFromCsvRowForSecondDomain(ProductData $productData, array $row)
    {
        $domainId = 2;
        $productData->descriptions[$domainId] = $row[$this->getDescriptionColumnForDomain($domainId)];
        $productData->shortDescriptions[$domainId] = $row[$this->getShortDescriptionColumnForDomain($domainId)];
        $productData->name['cs'] = $row[self::COLUMN_NAME_CS];
        $this->setProductDataPricesFromCsv($row, $productData, $domainId);
        $productData->categoriesByDomainId[$domainId] =
            $this->getValuesByKeyString($row[self::COLUMN_CATEGORIES_2], $this->categories);
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Product\ProductData $productData
     * @param array $row
     */
    public function updateProductDataFromCsvRowForThirdDomain(ProductData $productData, array $row)
    {
        $domainId = 3;
        $productData->descriptions[$domainId] = $row[$this->getDescriptionColumnForDomain($domainId)];
        $productData->shortDescriptions[$domainId] = $row[$this->getShortDescriptionColumnForDomain($domainId)];
        $productData->name['de'] = $row[self::COLUMN_NAME_DE];
        $this->setProductDataPricesFromCsv($row, $productData, $domainId);
        $productData->categoriesByDomainId[$domainId] =
            $this->getValuesByKeyString($row[self::COLUMN_CATEGORIES_3], $this->categories);
    }

    /**
     * @param int $domainId
     * @return int
     */
    protected function getShortDescriptionColumnForDomain($domainId)
    {
        $locale = $this->domain->getDomainConfigById($domainId)->getLocale();

        switch ($locale) {
            case 'cs':
                return self::COLUMN_SHORT_DESCRIPTION_CS;
            case 'en':
                return self::COLUMN_SHORT_DESCRIPTION_EN;
            case 'de':
                return self::COLUMN_SHORT_DESCRIPTION_DE;
            default:
                throw new \Shopsys\FrameworkBundle\Component\DataFixture\Exception\UnsupportedLocaleException($locale);
        }
    }

    /**
     * @param int $domainId
     * @return int
     */
    protected function getDescriptionColumnForDomain($domainId)
    {
        $locale = $this->domain->getDomainConfigById($domainId)->getLocale();

        switch ($locale) {
            case 'cs':
                return self::COLUMN_DESCRIPTION_CS;
            case 'en':
                return self::COLUMN_DESCRIPTION_EN;
            case 'de':
                return self::COLUMN_DESCRIPTION_DE;
            default:
                throw new \Shopsys\FrameworkBundle\Component\DataFixture\Exception\UnsupportedLocaleException($locale);
        }
    }

    /**
     * @param string $string
     * @return \Shopsys\FrameworkBundle\Component\Money\Money[]
     */
    protected function getProductManualPricesIndexedByPricingGroupFromString($string)
    {
        $productManualPricesByPricingGroup = [];
        $rowData = explode(';', $string);
        foreach ($rowData as $pricingGroupAndPrice) {
            list($pricingGroup, $price) = explode('=', $pricingGroupAndPrice);
            $productManualPricesByPricingGroup[$pricingGroup] = Money::create($price);
        }

        return $productManualPricesByPricingGroup;
    }

    /**
     * @param string $keyString
     * @param array $valuesByKey
     * @return string[]
     */
    protected function getValuesByKeyString($keyString, array $valuesByKey)
    {
        $values = [];
        if (!empty($keyString)) {
            $keys = explode(';', $keyString);
            foreach ($keys as $key) {
                $values[] = $valuesByKey[$key];
            }
        }

        return $values;
    }

    /**
     * @param array $row
     * @param \Shopsys\FrameworkBundle\Model\Product\ProductData $productData
     * @param int $domainId
     */
    protected function setProductDataPricesFromCsv(array $row, ProductData $productData, $domainId)
    {
        if ($domainId === 1) {
            $manualPricesColumn = $row[self::COLUMN_MANUAL_PRICES_DOMAIN_1];
        } elseif ($domainId === 2) {
            $manualPricesColumn = $row[self::COLUMN_MANUAL_PRICES_DOMAIN_2];
        } elseif ($domainId === 3) {
            $manualPricesColumn = $row[self::COLUMN_MANUAL_PRICES_DOMAIN_3];
        } else {
            throw new InvalidDomainIdException(sprintf('Invalid domain ID "%d"', $domainId));
        }

        $manualPricesFromCsv = $this->getProductManualPricesIndexedByPricingGroupFromString($manualPricesColumn);
        foreach ($manualPricesFromCsv as $pricingGroup => $manualPrice) {
            $pricingGroup = $this->pricingGroups[$pricingGroup];
            $productData->manualInputPricesByPricingGroupId[$pricingGroup->getId()] = $manualPrice;
        }

        $manualInputPricesFromCsvByPricingGroupId = $productData->manualInputPricesByPricingGroupId;
        $manualPricesForAllPricingGroups = $this->addZeroPricesForPricingGroupsThatAreMissingInDemoData($manualInputPricesFromCsvByPricingGroupId);
        $productData->manualInputPricesByPricingGroupId = $manualPricesForAllPricingGroups;
    }

    /**
     * @param \Shopsys\FrameworkBundle\Component\Money\Money[] $demoDataManualPrices
     * @return \Shopsys\FrameworkBundle\Component\Money\Money[]
     */
    protected function addZeroPricesForPricingGroupsThatAreMissingInDemoData(array $demoDataManualPrices): array
    {
        $allPricingGroups = $this->pricingGroupFacade->getAll();

        foreach ($allPricingGroups as $pricingGroup) {
            if (!isset($demoDataManualPrices[$pricingGroup->getId()])) {
                $demoDataManualPrices[$pricingGroup->getId()] = Money::zero();
            }
        }

        return $demoDataManualPrices;
    }

    /**
     * @param int $domainId
     * @return int
     */
    protected function getCsvProductColumnNameByDomainId(int $domainId)
    {
        switch ($this->domain->getDomainConfigById($domainId)->getLocale()) {
            case 'cs':
                return self::COLUMN_NAME_CS;
            case 'en':
                return self::COLUMN_NAME_EN;
            case 'de':
                return self::COLUMN_NAME_DE;
            default:
                throw new \Shopsys\FrameworkBundle\Component\DataFixture\Exception\UnsupportedLocaleException($this->domain->getDomainConfigById($domainId)->getLocale());
        }
    }
}
