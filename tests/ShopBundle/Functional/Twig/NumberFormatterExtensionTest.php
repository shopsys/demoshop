<?php

declare(strict_types=1);

namespace Tests\ShopBundle\Functional\Twig;

use CommerceGuys\Intl\NumberFormat\NumberFormatRepository;
use Shopsys\FrameworkBundle\Model\Administration\AdministrationFacade;
use Shopsys\FrameworkBundle\Model\Localization\Localization;
use Shopsys\FrameworkBundle\Twig\NumberFormatterExtension;
use Tests\ShopBundle\Test\FunctionalTestCase;

class NumberFormatterExtensionTest extends FunctionalTestCase
{
    public const NBSP = "\xc2\xa0";

    public function formatNumberDataProvider()
    {
        return [
            ['input' => '12', 'locale' => 'cs', 'result' => '12'],
            ['input' => '12.00', 'locale' => 'cs', 'result' => '12'],
            ['input' => '12.600', 'locale' => 'cs', 'result' => '12,6'],
            ['input' => '12.630000', 'locale' => 'cs', 'result' => '12,63'],
            ['input' => '12.638000', 'locale' => 'cs', 'result' => '12,638'],
            ['input' => 12.630000, 'locale' => 'cs', 'result' => '12,63'],
            ['input' => '123456789.123456789', 'locale' => 'cs', 'result' => '123' . self::NBSP . '456' . self::NBSP . '789,123456789'],

            ['input' => '12', 'locale' => 'en', 'result' => '12'],
            ['input' => '12.00', 'locale' => 'en', 'result' => '12'],
            ['input' => '12.600', 'locale' => 'en', 'result' => '12.6'],
            ['input' => '12.630000', 'locale' => 'en', 'result' => '12.63'],
            ['input' => '12.638000', 'locale' => 'en', 'result' => '12.638'],
            ['input' => 12.630000, 'locale' => 'en', 'result' => '12.63'],
            ['input' => '123456789.123456789', 'locale' => 'en', 'result' => '123,456,789.123456789'],
        ];
    }

    /**
     * @dataProvider formatNumberDataProvider
     * @param mixed $input
     * @param mixed $locale
     * @param mixed $result
     */
    public function testFormatNumber($input, $locale, $result)
    {
        $localizationMock = $this->getMockBuilder(Localization::class)
            ->disableOriginalConstructor()
            ->setMethods(['getLocale'])
            ->getMock();
        $localizationMock->expects($this->any())->method('getLocale')
            ->willReturn($locale);

        /* @var $numberFormatRepository \CommerceGuys\Intl\NumberFormat\NumberFormatRepository */
        $numberFormatRepository = $this->getContainer()->get(NumberFormatRepository::class);

        /** @var \Shopsys\FrameworkBundle\Model\Administration\AdministrationFacade $administrationFacade */
        $administrationFacade = $this->getContainer()->get(AdministrationFacade::class);

        $numberFormatterExtension = new NumberFormatterExtension($localizationMock, $numberFormatRepository, $administrationFacade);

        $this->assertSame($result, $numberFormatterExtension->formatNumber($input, $locale));
    }
}
