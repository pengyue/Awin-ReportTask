<?php

namespace Awin\ReportTask\Bundle\ReportBundle\Service;

use Awin\ReportTask\Bundle\ReportBundle\Model\Merchant;
use Awin\ReportTask\Bundle\ReportBundle\Model\TransactionTable;
use Awin\ReportTask\Bundle\ReportBundle\Utility\Helper;

/**
 * The merchant transaction aggregation service, it filter the transaction data with merchant_id
 * or date, and prepare the data for the report service class
 *
 * @date       24/06/2017
 * @time       19:17
 * @author     Peng Yue <penyue@gmail.com>
 * @copyright  2004-2017 Peng Yue
 */

class MerchantTransactionService implements MerchantTransactionServiceInterface
{
    /**
     * @var TransactionTable
     */
    private $transactionRepository;

    /**
     * @var Merchant
     */
    private $merchantRepository;

    /**
     * The data to be report after filtered by merchant_id, date, if either exists.
     * each item has the filter csv data, and intersect all the items to get final filter data
     *
     * @var array
     */
    private $data = [];

    /**
     * @param TransactionTable $transactionRepository
     *
     * @return $this
     */
    public function setTransactionRepository($transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;

        return $this;
    }

    /**
     * @param Merchant $merchantRepository
     *
     * @return $this
     */
    public function setMerchantRepository($merchantRepository)
    {
        $this->merchantRepository = $merchantRepository;

        return $this;
    }

    /**
     * Get all the transactions by merchant id.
     * If merchant id is null, then the transaction on all the merchants will be returned
     *
     * @param int|null      $merchantId
     *
     * @return $this
     */
    public function filterTransactionsByMerchantId($merchantId = null)
    {
        $this->data[] = (null === $merchantId)
            ? $this->transactionRepository->getTransactions()
            : $this->data[] = $this->merchantRepository
                ->setTransactionRepository($this->transactionRepository)
                ->getTransactionsByMerchantId($merchantId);

        return $this;
    }

    /**
     * Get the transactions by the date,
     * If the date is null, then all the transactions will be return
     *
     * @param string|null $date
     *
     * @return $this
     */
    public function filterTransactionsByDate($date = null)
    {
        $this->data[] = $this->transactionRepository->getTransactionsByDate($date);

        return $this;
    }

    /**
     * Intersect and keep the same values transactions on each filter data set
     *
     * @return array
     */
    public function getReportData()
    {
        //remove the empty array for the intersection later on
        $nonEmptyResults = array_filter($this->data, function($item) {
            return !empty($item);
        });

        //get the first array from list and intersect with the rest items
        $result = array_shift($nonEmptyResults);

        foreach ($nonEmptyResults as $item) {
            $result = Helper::array_intersect_recursive($result, $item);
        }

        return $result;
    }
}