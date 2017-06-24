<?php

namespace Awin\ReportTask\UnitTest\Bundle\ReportBundle\Model;

use PHPUnit\Framework\TestCase;
use Awin\ReportTask\Bundle\ReportBundle\Model\CurrencyConverter;
use Awin\ReportTask\Bundle\ReportBundle\Model\CurrencyWebservice;

/**
 * The currency converter test class
 *
 * @date       21/06/2017
 * @time       21:06
 * @author     Peng Yue <penyue@gmail.com>
 * @copyright  2004-2017 Peng Yue
 */

class CurrencyConverterTest extends TestCase
{
    /**
     * The available currencies
     *
     * @var array
     */
    private $currencies = [
        'GBP' => '£',
        'EUR' => '€',
        'USD' => '$'
    ];

    /**
     * @var CurrencyConverter
     */
    private $currencyConverter;

    /**
     * @var CurrencyWebservice
     */
    private $currencyWebservice;

    public function setUp()
    {
        $this->currencyWebservice = new CurrencyWebservice();
        $this->currencyConverter  = new CurrencyConverter($this->currencyWebservice, $this->currencies);
    }

    public function testItCanSetOriginalCurrency()
    {
        $self = $this->currencyConverter->setOriginalCurrency('GBP');

        $this->assertInstanceOf(CurrencyConverter::class, $self);
    }

    public function testItCanGetOriginalCurrency()
    {
        $self = $this->currencyConverter->setOriginalCurrency('USD');

        $this->assertSame('USD', $self->getOriginalCurrency());
    }

    public function testItCanSetTargetCurrency()
    {
        $self = $this->currencyConverter->setTargetCurrency('EUR');

        $this->assertInstanceOf(CurrencyConverter::class, $self);
    }

    public function testItCanGetTargetCurrency()
    {
        $self = $this->currencyConverter->setTargetCurrency('GBP');

        $this->assertSame('GBP', $self->getTargetCurrency());
    }

    /**
     * @expectedException \Awin\ReportTask\Bundle\ReportBundle\Exception\CurrencyNotFoundException
     * @expectedExceptionCode 10001
     */
    public function testItCanThrowCurrencyNotFoundExceptionWhenOriginalCurrencyNotFound()
    {
        $this->currencyConverter->setOriginalCurrency('THB');
    }

    /**
     * @expectedException \Awin\ReportTask\Bundle\ReportBundle\Exception\CurrencyNotFoundException
     * @expectedExceptionCode 10001
     */
    public function testItCanThrowCurrencyNotFoundExceptionWhenTargetCurrencyNotFound()
    {
        $this->currencyConverter->setTargetCurrency('YEN');
    }

    /**
     * @dataProvider currencyProvider
     */
    public function testItCanConvertCurrency($amount, $originalCurrency, $targetCurrency, $expected)
    {
        $targetAmount = $this->currencyConverter
                            ->setOriginalCurrency($originalCurrency)
                            ->setTargetCurrency($targetCurrency)
                            ->convert($amount);

        if ($targetAmount > $amount) {
            $actual = '>';
        } elseif ($targetAmount < $amount) {
            $actual = '<';
        } else {
            $actual = '=';
        }
        $this->assertSame($expected,  $actual);
    }

    public function currencyProvider()
    {
        return [
            [8.98, 'GBP', 'GBP', '='],
            [4.34, 'GBP', 'USD', '>'],
            [0.45, 'GBP', 'EUR', '>'],
            [20.98, 'EUR', 'GBP', '<'],
            [14.34, 'EUR', 'USD', '>'],
            [86.45, 'EUR', 'EUR', '='],
            [100.48, 'USD', 'GBP', '<'],
            [123.91, 'USD', 'USD', '='],
            [956.43, 'USD', 'EUR', '<'],
        ];
    }
}