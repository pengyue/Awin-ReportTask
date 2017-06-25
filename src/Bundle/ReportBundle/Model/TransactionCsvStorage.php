<?php

namespace Awin\ReportTask\Bundle\ReportBundle\Model;

use Awin\ReportTask\Bundle\ReportBundle\Exception\ReportFileSaveFailureException;
use League\Csv\Reader;
use League\Csv\Writer;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * The class to read data from CSV file, it is implemented from TransactionStorageInterface
 * and other implementation could introduced, for example, database, elasticsearch, API, etc
 *
 * @date       24/06/2017
 * @time       15:20
 * @author     Peng Yue <penyue@gmail.com>
 * @copyright  2004-2017 Peng Yue
 */

class TransactionCsvStorage implements TransactionStorageInterface
{
    /**
     * The source csv file path
     *
     * @var string
     */
    private $csvFilePath;

    /**
     * The data to be save into storage
     *
     * @var array
     */
    private $data = [];

    /**
     * TransactionCsvStorage constructor.
     *
     * @param string $csvFilePath
     */
    public function __construct($csvFilePath)
    {
        $this->csvFilePath  = $csvFilePath;

        if (!ini_get("auto_detect_line_endings")) {
            ini_set("auto_detect_line_endings", '1');
        }
    }

    /**
     * Get the all the data from source csv
     * There is no pagination for simplicity
     *
     * @param int $offset
     * @param int $limit
     *
     * @return array
     */
    public function getData($offset, $limit)
    {
        $csv = Reader::createFromPath($this->csvFilePath);
        $csv->setDelimiter(';');
        $data = $csv->setOffset($offset)->setLimit($limit)->fetchAll();

        return $data;
    }

    /**
     * Set the data for saving into storage
     *
     * @param array $data
     *
     * @return $this
     */
    public function setData(array $data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Save the data into storage
     *
     * @param string $targetCsvFile
     *
     * @return bool
     */
    public function save($targetCsvFile)
    {
        try {
            if (file_exists($targetCsvFile)) {
                @unlink($targetCsvFile);
            }

            $csv = Writer::createFromPath($targetCsvFile, 'a+');

            $csv->insertOne([
                'Merchant ID',
                'Date',
                'Original Transaction',
                'Currency Symbol',
                'Transaction Amount',
                'Transaction In GBP',
                'Transaction In EUR',
                'Transaction In USD',
            ]);

            $csv->insertAll($this->data);
        } catch (\Exception $e) {
            throw new ReportFileSaveFailureException($targetCsvFile);
        }

        return true;
    }
}