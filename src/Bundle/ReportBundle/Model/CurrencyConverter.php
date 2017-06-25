<?php

namespace Awin\ReportTask\Bundle\ReportBundle\Model;

use Awin\ReportTask\Bundle\ReportBundle\Exception\CurrencyNotFoundException;

/**
 * The currency service which pull the (remote) currency webservice and (local) converter class.
 * It also converts the currency symbol from/to currency ISO code for more standard process
 *
 * @date       24/06/2017
 * @time       12:47
 * @author     Peng Yue <penyue@gmail.com>
 * @copyright  2004-2017 Peng Yue
 */

class CurrencyConverter
{
    /**
     * Predefined currencies list supported
     *
     * @var array
     */
    private $currencies = [];

    /**
     * The currency converted from
     *
     * @var string
     */
    private $originalCurrency;

    /**
     * The currency converted to
     *
     * @var string
     */
    private $targetCurrency;

    /**
     * @var CurrencyWebservice
     */
    private $currencyWebservice;

    /**
     * CurrencyConverter constructor.
     *
     * @param CurrencyWebservice $currencyWebservice
     * @param array $availableCurrencies
     */
    public function __construct(CurrencyWebservice $currencyWebservice, array $availableCurrencies)
    {
        $this->currencyWebservice = $currencyWebservice;
        $this->currencies = $availableCurrencies;
    }

    /**
     * Convert the certain amount in one currency into the amount in another currency
     *
     * @param float $amount
     *
     * @return float
     */
    public function convert($amount)
    {
        return money_format('%+n',
            $amount *
            $this->currencyWebservice->getExchangeRate(
                $this->getOriginalCurrency(),
                $this->getTargetCurrency()
            )
        );
    }

    /**
     * Set the original currency converted from
     *
     * @param string $currency
     *
     * @throw  \Awin\ReportTask\Bundle\ReportBundle\Exception\CurrencyNotFoundException
     * @return $this
     */
    public function setOriginalCurrency($currency)
    {
        if (!array_key_exists($currency, $this->currencies)) {
            throw new CurrencyNotFoundException($currency);
        }

        $this->originalCurrency = $currency;

        return $this;
    }

    /**
     * Set the original currency converted from
     *
     * @return string
     */
    public function getOriginalCurrency()
    {
        return $this->originalCurrency;
    }

    /**
     * Set the target currency converted to
     *
     * @param string $currency
     *
     * @throw  \Awin\ReportTask\Bundle\ReportBundle\Exception\CurrencyNotFoundException
     * @return $this
     */
    public function setTargetCurrency($currency)
    {
        if (!array_key_exists($currency, $this->currencies)) {
            throw new CurrencyNotFoundException($currency);
        }

        $this->targetCurrency = $currency;

        return $this;
    }

    /**
     * Set the target currency converted to
     *
     * @return string
     */
    public function getTargetCurrency()
    {
        return $this->targetCurrency;
    }
}