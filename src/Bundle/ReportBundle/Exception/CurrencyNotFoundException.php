<?php

namespace Awin\ReportTask\Bundle\ReportBundle\Exception;

use InvalidArgumentException;

/**
 * Invalid currency not found exception
 *
 * @date       21/06/2017
 * @time       22:49
 * @author     Peng Yue <penyue@gmail.com>
 * @copyright  2004-2017 Peng Yue
 */

class CurrencyNotFoundException extends InvalidArgumentException
{
    public function __construct($currency)
    {
        $message = "Invalid currency: %s";

        parent::__construct(sprintf($message, $currency), ErrorCode::INVALID_CURRENCY_ERROR_CODE);
    }
}