<?php

namespace Awin\ReportTask\Bundle\ReportBundle\Service;

use Awin\ReportTask\Bundle\ReportBundle\Model\TransactionStorageInterface;

/**
 * The report service interface
 *
 * @date       22/06/2017
 * @time       13:09
 * @author     Peng Yue <penyue@gmail.com>
 * @copyright  2004-2017 Peng Yue
 */

interface ReportServiceInterface
{
    /**
     * Generate the report
     *
     * @param MerchantTransactionServiceInterface   $merchantTransactionService
     * @param CurrencyServiceInterface              $currencyService
     * @param TransactionStorageInterface           $transactionStorage
     * @param int                                   $merchantId
     * @param string|null                           $date
     *
     * @return bool
     */
    public function generate(
        MerchantTransactionServiceInterface $merchantTransactionService,
        CurrencyServiceInterface $currencyService,
        TransactionStorageInterface $transactionStorage,
        $merchantId,
        $date
    );
}