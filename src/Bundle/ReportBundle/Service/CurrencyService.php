<?php

namespace Awin\ReportTask\Bundle\ReportBundle\Service;

use Awin\ReportTask\Bundle\ReportBundle\Exception\CurrencyNotFoundException;
use Awin\ReportTask\Bundle\ReportBundle\Model\CurrencyConverter;
use Awin\ReportTask\Bundle\ReportBundle\Model\CurrencyWebservice;

/**
 * The currencies aggregation services on currency conversion process
 *
 * @date       24/06/2017
 * @time       18:49
 * @author     Peng Yue <penyue@gmail.com>
 * @copyright  2004-2017 Peng Yue
 */

class CurrencyService implements CurrencyServiceInterface
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

    /**
     * CurrencyService constructor.
     */
    public function __construct()
    {
        $this->currencyWebservice   = new CurrencyWebservice();
        $this->currencyConverter    = new CurrencyConverter($this->currencyWebservice, $this->currencies);
    }

    /**
     * Get the instance of the currency converter
     *
     * @return CurrencyConverter
     */
    public function getCurrencyConverter()
    {
        return $this->currencyConverter;
    }

    /**
     * Get the instance of the currency webservice
     *
     * @return CurrencyWebservice
     */
    public function getCurrencyWebservice()
    {
        return $this->currencyWebservice;
    }

    /**
     * Get the available currencies list
     *
     * @return array
     */
    public function getCurrencies()
    {
        return $this->currencies;
    }

    /**
     * Get the currency symbol with currency ISO code
     *
     * @param string $currency
     *
     * @throw  \Awin\ReportTask\Bundle\ReportBundle\Exception\CurrencyNotFoundException
     * @return string
     */
    public function currencyToSymbol($currency)
    {
        if (!array_key_exists($currency, $this->currencies)) {
            throw new CurrencyNotFoundException($currency);
        }
        return $this->currencies[$currency];
    }

    /**
     * Get the currency ISO code with currency symbol
     *
     * @param string $symbol
     *
     * @throw  \Awin\ReportTask\Bundle\ReportBundle\Exception\CurrencyNotFoundException
     * @return string
     */
    public function symbolToCurrency($symbol)
    {
        $currencies = array_flip($this->currencies);
        if (!array_key_exists($symbol, $currencies)) {
            throw new CurrencyNotFoundException($symbol);
        }
        return $currencies[$symbol];
    }
}