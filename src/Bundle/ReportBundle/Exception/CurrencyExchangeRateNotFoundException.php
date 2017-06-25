<?php

namespace Awin\ReportTask\Bundle\ReportBundle\Exception;

use RuntimeException;

/**
 * The (remote) currency exchange rate not found exception
 *
 * @date       25/06/2017
 * @time       22:11
 * @author     Peng Yue <penyue@gmail.com>
 * @copyright  2004-2017 Peng Yue
 */

class CurrencyExchangeRateNotFoundException extends RuntimeException
{
    public function __construct($originalCurrency, $targetCurrency)
    {
        $message = "Invalid currency exchange rate from %s to %s";

        parent::__construct(sprintf($message, $originalCurrency, $targetCurrency), ErrorCode::INVALID_CURRENCY_EXCHANGE_RATE_ERROR_CODE);
    }
}