<?php

namespace Awin\ReportTask\UnitTest\Bundle\ReportBundle\Model;

use Awin\ReportTask\Bundle\ReportBundle\Model\TransactionTable;

/**
 * Test transaction table model class
 *
 * @date       21/06/2017
 * @time       16:43
 * @author     Peng Yue <penyue@gmail.com>
 * @copyright  2004-2017 Peng Yue
 */

class TransactionTableTest extends BaseTestCase
{
    const CSV_FILE_PATH = 'var/storage/data.csv';

    /**
     * Test if it can get the transactions
     */
    public function testItCanGetTransactions()
    {
        $transactionTable = new TransactionTable(self::CSV_FILE_PATH);

        $result = $transactionTable->getTransactions();

        $expected = $this->nativeReadCsv();

        $this->assertEquals($expected, $result);
    }
}