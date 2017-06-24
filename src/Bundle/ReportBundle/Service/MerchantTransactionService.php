<?php

namespace Awin\ReportTask\Bundle\ReportBundle\Service;

use Awin\ReportTask\Bundle\ReportBundle\Model\Merchant;
use Awin\ReportTask\Bundle\ReportBundle\Model\TransactionTable;

/**
 * The merchant transaction service class
 *
 * @date       22/06/2017
 * @time       14:19
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
        //TODO, add a try catch
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
     * intersect and keep the same values transactions on each filter data set
     *
     * @return array
     */
    public function getReportData()
    {
        $result = array_shift($this->data);

        foreach ($this->data as $item) {
            $result = self::array_intersect_recursive($result, $item);
        }

        return $result;
    }

    /**
     * recursively intersect arrays
     * TODO, move it to a utilize class
     *
     * @param array $array1
     * @param array $array2
     *
     * @return array
     */
    public static function array_intersect_recursive($array1, $array2)
    {
        foreach($array1 as $key => $value) {
            if (!isset($array2[$key])) {
                unset($array1[$key]);
            } else {
                if (is_array($array1[$key])) {
                    $array1[$key] = self::array_intersect_recursive($array1[$key], $array2[$key]);
                } elseif ($array2[$key] !== $value) {
                    unset($array1[$key]);
                }
            }
        }
        return $array1;
    }
}