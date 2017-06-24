<?php

namespace Awin\ReportTask\UnitTest\Bundle\ReportBundle\Service\Observer;

use Awin\ReportTask\Bundle\ReportBundle\Model\CurrencyConverter;
use Awin\ReportTask\Bundle\ReportBundle\Service\CurrencyService;
use Awin\ReportTask\Bundle\ReportBundle\Service\Observer\ReportObserver;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

/**
 * The unit test for the report generation observer
 *
 * @date       23/06/2017
 * @time       21:08
 * @author     Peng Yue <penyue@gmail.com>
 * @copyright  2004-2017 Peng Yue
 */

class ReportObserverTest extends TestCase
{
    public function testItCanListenToReportGenerationSuccessfully()
    {
        $data = [
            [1, '24/06/2017', '£7.50', '£', '7.50']
        ];
        $currencies = [
            'EUR' => '€'
        ];

        $currencyService = $this->prophesize(CurrencyService::class);
        $currencyService->getCurrencies()->willReturn($currencies);
        $currencyService->symbolToCurrency(Argument::cetera())->willReturn('GBP');
        $currencyConverter = $this->prophesize(CurrencyConverter::class);
        $currencyConverter->setOriginalCurrency(Argument::cetera())->willReturn($currencyConverter->reveal());
        $currencyConverter->setTargetCurrency(Argument::cetera())->willReturn($currencyConverter->reveal());
        $currencyConverter->convert(Argument::cetera())->willReturn(7.50 * 1.15);
        $currencyService->getCurrencyConverter()->willReturn($currencyConverter->reveal());

        $observer   = new ReportObserver();
        $result     = $observer->listenReportGeneration($data, $currencyService->reveal());

        $expected = array_shift($data);
        $actual   = array_shift($result);

        $this->assertSame($expected[0], $actual[0]);
        $this->assertSame($expected[1], $actual[1]);
        $this->assertSame($expected[2], $actual[2]);
        $this->assertSame($expected[3], $actual[3]);
        $this->assertSame($expected[4], $actual[4]);

        $actualConvertedSymbol = mb_substr($actual[5], 0, 1, 'UTF-8');
        $actualConvertedAmount = mb_substr($actual[5], 1);

        $this->assertSame('€', $actualConvertedSymbol);
        $this->assertGreaterThanOrEqual(7.50 * 1.11, $actualConvertedAmount);
        $this->assertLessThanOrEqual(7.50 * 1.15, $actualConvertedAmount);
    }
}