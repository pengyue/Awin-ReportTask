<?php

namespace Awin\ReportTask\Bundle\ReportBundle\Service;

/**
 * The merchant transaction class interface
 *
 * @date       22/06/2017
 * @time       14:20
 * @author     Peng Yue <penyue@gmail.com>
 * @copyright  2004-2017 Peng Yue
 */

interface MerchantTransactionServiceInterface
{
    /**
     * Get all the transactions by merchant id.
     * If merchant id is null, then the transaction on all the merchants will be returned
     *
     * @param int|null      $merchantId
     *
     * @return $this
     */
    public function filterTransactionsByMerchantId($merchantId = null);

    /**
     * Get the transactions by the date,
     * If the date is null, then all the transactions will be return
     *
     * @param string|null $date
     *
     * @return $this
     */
    public function filterTransactionsByDate($date = null);

    /**
     * intersect and keep the same values transactions on each filter data set
     *
     * @return array
     */
    public function getReportData();
}