<?php

namespace Awin\ReportTask\Bundle\ReportBundle\Exception;

use DomainException;

/**
 * Invalid transaction table repository exception
 *
 * @date       21/06/2017
 * @time       23:27
 * @author     Peng Yue <penyue@gmail.com>
 * @copyright  2004-2017 Peng Yue
 */

class TransactionRepositoryNotFoundException extends DomainException
{
    public function __construct()
    {
        $message = "Invalid transaction repository";

        parent::__construct($message, ErrorCode::INVALID_TRANSACTION_REPOSITORY_ERROR_CODE);
    }
}