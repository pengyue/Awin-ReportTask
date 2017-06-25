<?php

namespace Awin\ReportTask\Bundle\ReportBundle\Model;

/**
 * The transaction storage interface.
 * It is an abstract layer for multiple data access storage like DB, csv, remote api, etc
 *
 * @date       24/06/2017
 * @time       14:04
 * @author     Peng Yue <penyue@gmail.com>
 * @copyright  2004-2017 Peng Yue
 */

interface TransactionStorageInterface
{
    /**
     * Get the transaction data
     * There is no pagination for simplicity
     *
     * @param int $offset
     * @param int $limit
     *
     * @return array
     */
    public function getData($offset, $limit);

    /**
     * Set the data for saving into storage
     *
     * @param array $data
     *
     * @return $this
     */
    public function setData(array $data);

    /**
     * Save the data into storage
     *
     * @param string $targetCsvFile
     *
     * @return bool
     */
    public function save($targetCsvFile);
}