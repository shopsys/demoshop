<?php

declare(strict_types=1);

namespace Tests\ShopBundle\Functional\Twig;

use CommerceGuys\Intl\Currency\CurrencyRepositoryInterface;
use CommerceGuys\Intl\NumberFormat\NumberFormatRepository;
use Shopsys\FrameworkBundle\Component\CurrencyFormatter\CurrencyFormatterFactory;
use Shopsys\FrameworkBundle\Component\Domain\Config\DomainConfig;
use Shopsys\FrameworkBundle\Component\Domain\Domain;
use Shopsys\FrameworkBundle\Component\Money\Money;
use Shopsys\FrameworkBundle\Component\Setting\Setting;
use Shopsys\FrameworkBundle\Model\Localization\Localization;
use Shopsys\FrameworkBundle\Model\Pricing\Currency\Currency;
use Shopsys\FrameworkBundle\Model\Pricing\Currency\CurrencyDataFactoryInterface;
use Shopsys\FrameworkBundle\Model\Pricing\Currency\CurrencyFacade;
use Shopsys\FrameworkBundle\Model\Pricing\Currency\CurrencyFactoryInterface;
use Shopsys\FrameworkBundle\Twig\PriceExtension;
use Tests\ShopBundle\Test\FunctionalTestCase;

class PriceExtensionTest extends FunctionalTestCase
{
    protected const NBSP = "\xc2\xa0";

    /**
     * @var \Shopsys\FrameworkBundle\Component\Domain\Domain
     */
    private $domain;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Localization\IntlCurrencyRepository
     */
    private $intlCurrencyRepository;

    /**
     * @var \CommerceGuys\Intl\NumberFormat\NumberFormatRepositoryInterface
     */
    private $numberFormatRepository;

    /**
     * @var \Shopsys\FrameworkBundle\Component\CurrencyFormatter\CurrencyFormatterFactory
     */
    private $currencyFormatterFactory;

    protected function setUp()
    {
        $domainConfig1 = new DomainConfig(Domain::FIRST_DOMAIN_ID, 'http://example.com', 'example', 'en');
        $domainConfig2 = new DomainConfig(Domain::SECOND_DOMAIN_ID, 'http://example.com', 'example', 'cs');

        /** @var \Shopsys\FrameworkBundle\Component\Setting\Setting|\PHPUnit\Framework\MockObject\MockObject $settingMock */
        $settingMock = $this->getMockBuilder(Setting::class)
            ->disableOriginalConstructor()
            ->setMethods(['getForDomain'])
            ->getMock();
        $settingMock
            ->method('getForDomain')
            ->with($this->equalTo(Setting::DOMAIN_DATA_CREATED))
            ->willReturn(true);

        $this->domain = new Domain([$domainConfig1, $domainConfig2], $settingMock);
        $this->intlCurrencyRepository = $this->getContainer()->get(CurrencyRepositoryInterface::class);
        $this->numberFormatRepository = $this->getContainer()->get(NumberFormatRepository::class);
        $this->currencyFormatterFactory = $this->getContainer()->get(CurrencyFormatterFactory::class);

        parent::setUp();
    }

    public function priceFilterDataProvider()
    {
        return [
            ['input' => Money::create(12), 'domainId' => Domain::FIRST_DOMAIN_ID, 'result' => 'CZK12.00'],
            ['input' => Money::create('12.00'), 'domainId' => Domain::FIRST_DOMAIN_ID, 'result' => 'CZK12.00'],
            ['input' => Money::create('12.600'), 'domainId' => Domain::FIRST_DOMAIN_ID, 'result' => 'CZK12.60'],
            ['input' => Money::create('12.630000'), 'domainId' => Domain::FIRST_DOMAIN_ID, 'result' => 'CZK12.63'],
            ['input' => Money::create('12.638000'), 'domainId' => Domain::FIRST_DOMAIN_ID, 'result' => 'CZK12.638'],
            ['input' => Money::create('12.630000'), 'domainId' => Domain::FIRST_DOMAIN_ID, 'result' => 'CZK12.63'],
            [
                'input' => Money::create('123456789.123456789'),
                'domainId' => Domain::FIRST_DOMAIN_ID,
                'result' => 'CZK123,456,789.123456789',
            ],
            [
                'input' => Money::create('123456789.123456789123456789'),
                'domainId' => Domain::FIRST_DOMAIN_ID,
                'result' => 'CZK123,456,789.1234567891',
            ],
            ['input' => Money::create(12), 'domainId' => Domain::SECOND_DOMAIN_ID, 'result' => '12,00' . self::NBSP . '€'],
            ['input' => Money::create('12.00'), 'domainId' => Domain::SECOND_DOMAIN_ID, 'result' => '12,00' . self::NBSP . '€'],
            ['input' => Money::create('12.600'), 'domainId' => Domain::SECOND_DOMAIN_ID, 'result' => '12,60' . self::NBSP . '€'],
            ['input' => Money::create('12.630000'), 'domainId' => Domain::SECOND_DOMAIN_ID, 'result' => '12,63' . self::NBSP . '€'],
            ['input' => Money::create('12.638000'), 'domainId' => Domain::SECOND_DOMAIN_ID, 'result' => '12,638' . self::NBSP . '€'],
            ['input' => Money::create('12.630000'), 'domainId' => Domain::SECOND_DOMAIN_ID, 'result' => '12,63' . self::NBSP . '€'],
            [
                'input' => Money::create('123456789.123456789'),
                'domainId' => Domain::SECOND_DOMAIN_ID,
                'result' => '123' . self::NBSP . '456' . self::NBSP . '789,123456789' . self::NBSP . '€',
            ],
            [
                'input' => Money::create('123456789.123456789123456789'),
                'domainId' => Domain::SECOND_DOMAIN_ID,
                'result' => '123' . self::NBSP . '456' . self::NBSP . '789,1234567891' . self::NBSP . '€',
            ],
        ];
    }

    /**
     * @dataProvider priceFilterDataProvider
     * @param mixed $input
     * @param mixed $domainId
     * @param mixed $result
     */
    public function testPriceFilter($input, $domainId, $result)
    {
        $this->domain->switchDomainById($domainId);

        $priceExtension = $this->getPriceExtensionWithMockedConfiguration();

        $this->assertSame($result, $priceExtension->priceFilter($input));
    }

    /**
     * @return \Shopsys\FrameworkBundle\Twig\PriceExtension
     */
    private function getPriceExtensionWithMockedConfiguration(): PriceExtension
    {
        /** @var \Shopsys\FrameworkBundle\Model\Pricing\Currency\CurrencyFactoryInterface $currencyFactory */
        $currencyFactory = $this->getContainer()->get(CurrencyFactoryInterface::class);
        /** @var \Shopsys\FrameworkBundle\Model\Pricing\Currency\CurrencyDataFactoryInterface $currencyFactory */
        $currencyDataFactory = $this->getContainer()->get(CurrencyDataFactoryInterface::class);
        $domain1DefaultCurrencyData = $currencyDataFactory->create();
        $domain1DefaultCurrencyData->name = 'Czech crown';
        $domain1DefaultCurrencyData->code = Currency::CODE_CZK;
        $domain1DefaultCurrencyData->exchangeRate = 1;
        $domain2DefaultCurrencyData = $currencyDataFactory->create();
        $domain2DefaultCurrencyData->name = 'Euro';
        $domain2DefaultCurrencyData->code = Currency::CODE_EUR;
        $domain2DefaultCurrencyData->exchangeRate = 25;
        $domain1DefaultCurrency = $currencyFactory->create($domain1DefaultCurrencyData);
        $domain2DefaultCurrency = $currencyFactory->create($domain2DefaultCurrencyData);

        /** @var \Shopsys\FrameworkBundle\Model\Pricing\Currency\CurrencyFacade|\PHPUnit\Framework\MockObject\MockObject $currencyFacadeMock */
        $currencyFacadeMock = $this->getMockBuilder(CurrencyFacade::class)
            ->disableOriginalConstructor()
            ->setMethods(['getDomainDefaultCurrencyByDomainId', 'getDefaultCurrency'])
            ->getMock();
        $currencyFacadeMock
            ->method('getDomainDefaultCurrencyByDomainId')
            ->willReturnMap([
                [Domain::FIRST_DOMAIN_ID, $domain1DefaultCurrency],
                [Domain::SECOND_DOMAIN_ID, $domain2DefaultCurrency],
            ]);
        $currencyFacadeMock
            ->method('getDefaultCurrency')
            ->willReturn($domain1DefaultCurrency);
        $localization = new Localization($this->domain, 'en');

        return new PriceExtension(
            $currencyFacadeMock,
            $this->domain,
            $localization,
            $this->numberFormatRepository,
            $this->intlCurrencyRepository,
            $this->currencyFormatterFactory
        );
    }
}
