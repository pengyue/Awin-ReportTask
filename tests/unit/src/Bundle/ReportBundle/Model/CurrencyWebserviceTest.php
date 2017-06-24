<?php

namespace Awin\ReportTask\UnitTest\Bundle\ReportBundle\Model;

use Awin\ReportTask\Bundle\ReportBundle\Model\CurrencyConverter;
use Awin\ReportTask\Bundle\ReportBundle\Model\CurrencyWebservice;
use PHPUnit\Framework\TestCase;

/**
 * The faked currency webservice class
 *
 * @date       21/06/2017
 * @time       22:07
 * @author     Peng Yue <penyue@gmail.com>
 * @copyright  2004-2017 Peng Yue
 */

class CurrencyWebserviceTest extends TestCase
{
    /**
     * @var CurrencyWebservice
     */
    private $currencyWebservice;

    public function setUp()
    {
        $this->currencyWebservice = new CurrencyWebservice();
    }

    /**
     * @dataProvider currencyProvider
     */
    public function testItCanGetExchangeRate($originalCurrency, $targetCurrency)
    {
        $rate = $this->currencyWebservice
                ->getExchangeRate(
                    $originalCurrency,
                    $targetCurrency
                );

        $this->assertInternalType('float', $rate);
    }

    public function currencyProvider()
    {
        return [
            ['GBP', 'GBP', '='],
            ['GBP', 'USD', '>'],
            ['GBP', 'EUR', '>'],
            ['EUR', 'GBP', '<'],
            ['EUR', 'USD', '>'],
            ['EUR', 'EUR', '='],
            ['USD', 'GBP', '<'],
            ['USD', 'USD', '='],
            ['USD', 'EUR', '<'],
        ];
    }
}