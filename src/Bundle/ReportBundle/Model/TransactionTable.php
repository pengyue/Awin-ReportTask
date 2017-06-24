<?php

namespace Awin\ReportTask\Bundle\ReportBundle\Model;

use Faker\Provider\cs_CZ\DateTime;

/**
 * Source of transactions, can read data.csv directly for simplicity sake,
 * should behave like a database (read only)
 *
 * @date       21/06/2017
 * @time       16:35
 * @author     Peng Yue <penyue@gmail.com>
 * @copyright  2004-2017 Peng Yue
 */
class TransactionTable
{
    /**
     * @var TransactionStorageInterface
     */
    private $transactionStorage;

    /**
     * TransactionTable constructor.
     *
     * @param string $csvFilePath
     */
    public function __construct($csvFilePath)
    {
        $this->transactionStorage = new TransactionCsvStorage($csvFilePath);
    }

    /**
     * Get the transactions from data source, for simplicity, no pagination on getting transactions
     * @param int $offset the start position to read
     * @param int $limit  the item number to read
     *
     * @return array
     */
    public function getTransactions($offset = 1, $limit = 15)
    {
        $results = [];
        $data = $this->transactionStorage->getData($offset, $limit);
        foreach ($data as $item) {
            $symbol = mb_substr($item[2], 0, 1, 'UTF-8');
            $amount = mb_substr($item[2], 1);
            $temp   = array_merge($item, [$symbol, $amount]);
            $results[] = $temp;
        }

        return $results;
    }

    /**
     * Get the transactions by date
     *
     * @param string|null $date
     *
     * @return array
     */
    public function getTransactionsByDate($date = null)
    {
        $results = [];
        //for simplicity, no pagination here neither
        $data = $this->transactionStorage->getData(1, 15);

        $dateWorker = new \DateTime($date);
        $formattedDate = $dateWorker->format('Y-m-d H:i:s');

        foreach ($data as $item) {
            if (strtotime($formattedDate) - strtotime($item[1]) > 0) {
                $symbol = mb_substr($item[2], 0, 1, 'UTF-8');
                $amount = mb_substr($item[2], 1);
                $temp   = array_merge($item, [$symbol, $amount]);
                $results[] = $temp;
            }
        }

        return $results;
    }
}