<?php

namespace Awin\ReportTask\UnitTest\Bundle\ReportBundle\Model;

use PHPUnit\Framework\TestCase;

/**
 * The base test class for running the tests on transactions and merchant test
 *
 * @date       25/06/2017
 * @time       10:05
 * @author     Peng Yue <penyue@gmail.com>
 * @copyright  2004-2017 Peng Yue
 */

class BaseTestCase extends TestCase
{
    /**
     * Read the csv with native php fgetcsv(), which is different from original csv file reader class
     * This is purely used for testing
     *
     * @return array
     */
    protected function nativeReadCsv()
    {
        $row       = 0;
        $rawResult = [];

        if (false !== ($handle = fopen("var/storage/data.csv", "r"))) {
            while (false !== ($data = fgetcsv($handle, 1000, ";"))) {
                $row++;
                if (1 === $row) {
                    continue;
                }
                $num    = count($data);
                $item   = [];
                for ($c = 0; $c < $num; $c++) {
                    $item[] = $data[$c];
                }

                $rawResult[] = $item;
            }
            fclose($handle);
        }

        foreach ($rawResult as $item) {
            $symbol = mb_substr($item[2], 0, 1, 'UTF-8');
            $amount = mb_substr($item[2], 1);
            $temp   = array_merge($item, [$symbol, $amount]);
            $results[] = $temp;
        }

        return $results;
    }

    /**
     * Get the transactions by merchant id from the native csv reader resultset
     *
     * @param int $merchantId
     *
     * @return array
     */
    protected function getTransactionByMerchantId($merchantId)
    {
        $result = array_filter(
            $this->nativeReadCsv(),
            function ($e) use ($merchantId) {
                return (int)$e[0] == (int)$merchantId;
            }
        );

        return $result;
    }
}