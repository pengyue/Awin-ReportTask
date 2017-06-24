<?php

namespace Awin\ReportTask\Bundle\ReportBundle\Service;

use Awin\ReportTask\Bundle\ReportBundle\Model\CurrencyConverter;
use Awin\ReportTask\Bundle\ReportBundle\Model\CurrencyWebservice;

/**
 * The currency service interface
 *
 * @date       23/06/2017
 * @time       12:26
 * @author     Peng Yue <penyue@gmail.com>
 * @copyright  2004-2017 Peng Yue
 */

interface CurrencyServiceInterface
{
    /**
     * Get the instance of the currency converter
     *
     * @return CurrencyConverter
     */
    public function getCurrencyConverter();

    /**
     * Get the instance of the currency webservice
     *
     * @return CurrencyWebservice
     */
    public function getCurrencyWebservice();

    /**
     * Get the available currencies list
     *
     * @return array
     */
    public function getCurrencies();
}