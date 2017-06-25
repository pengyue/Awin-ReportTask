<?php

namespace Awin\ReportTask\UnitTest\Bundle\ReportBundle\Model;

use Awin\ReportTask\Bundle\ReportBundle\Model\TransactionCsvStorage;
use Awin\ReportTask\Bundle\ReportBundle\Model\TransactionStorageInterface;
use League\Csv\Reader;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

/**
 * The test for the transaction data storage
 *
 * @date       23/06/2017
 * @time       10:46
 * @author     Peng Yue <penyue@gmail.com>
 * @copyright  2004-2017 Peng Yue
 */

class TransactionCsvStorageTest extends TestCase
{
    const CSV_FILE_PATH = 'var/storage/data.csv';

    /**
     * @var TransactionStorageInterface
     */
    private $transactionCsvStorage;

    public function setUp()
    {
        $this->transactionCsvStorage = new TransactionCsvStorage(self::CSV_FILE_PATH);
    }

    public function testItCanSetData()
    {
        $data = $this->transactionCsvStorage->getData(1, 100);
        $self = $this->transactionCsvStorage->setData($data);

        $this->assertSame($this->transactionCsvStorage, $self);
    }
}