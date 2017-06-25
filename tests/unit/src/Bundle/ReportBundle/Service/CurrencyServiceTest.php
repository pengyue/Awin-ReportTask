<?php

namespace Awin\ReportTask\UnitTest\Bundle\ReportBundle\Service;

use Awin\ReportTask\Bundle\ReportBundle\Model\CurrencyConverter;
use Awin\ReportTask\Bundle\ReportBundle\Model\CurrencyWebservice;
use Awin\ReportTask\Bundle\ReportBundle\Service\CurrencyService;
use PHPUnit\Framework\TestCase;

/**
 * The currency service which pull all the currency components together for exchange rate calculation
 *
 * @date       24/06/2017
 * @time       17:15
 * @author     Peng Yue <penyue@gmail.com>
 * @copyright  2004-2017 Peng Yue
 */

class CurrencyServiceTest extends TestCase
{
    private $currencies = [
        'GBP' => '£',
        'EUR' => '€',
        'USD' => '$'
    ];

    /**
     * @var CurrencyService
     */
    private $currencyService;

    public function setUp()
    {
        $this->currencyService = new CurrencyService();
    }

    public function testItCanGetCurrencyConverter()
    {
        $this->assertInstanceOf(CurrencyConverter::class, $this->currencyService->getCurrencyConverter());
    }

    public function testItCanGetCurrencyWebservice()
    {
        $this->assertInstanceOf(CurrencyWebservice::class, $this->currencyService->getCurrencyWebservice());
    }

    public function testItCanGetCurrencies()
    {
        $this->assertSame($this->currencies, $this->currencyService->getCurrencies());
    }

    /**
     * @expectedException \Awin\ReportTask\Bundle\ReportBundle\Exception\CurrencyNotFoundException
     * @expectedExceptionCode 10001
     */
    public function testItCanThrowCurrencyNotFoundExceptionWhenCurrencyConvertedToSymbol()
    {
        $this->currencyService->currencyToSymbol('THB');
    }

    /**
     * @expectedException \Awin\ReportTask\Bundle\ReportBundle\Exception\CurrencyNotFoundException
     * @expectedExceptionCode 10001
     */
    public function testItCanThrowCurrencyNotFoundExceptionWhenSymbolConvertedToCurrency()
    {
        $this->currencyService->symbolToCurrency('¥');
    }


    /**
     * @dataProvider currencyProvider
     */
    public function testItCanGetSymbolWithCurrency($currency, $symbol)
    {
        $this->assertSame($symbol, $this->currencyService->currencyToSymbol($currency));
    }

    /**
     * @dataProvider currencyProvider
     */
    public function testItCanGetCurrencyWithSymbol($currency, $symbol)
    {
        $this->assertSame($currency, $this->currencyService->symbolToCurrency($symbol));
    }

    public function currencyProvider()
    {
        return [
            ['GBP', '£'],
            ['USD', '$'],
            ['EUR', '€'],
        ];
    }
}