<?php

namespace Awin\ReportTask\UnitTest\Bundle\ReportBundle\Model;

use Awin\ReportTask\Bundle\ReportBundle\Model\Merchant;
use Awin\ReportTask\Bundle\ReportBundle\Model\TransactionTable;

/**
 * The merchant repository test class
 *
 * @date       25/06/2017
 * @time       12:05
 * @author     Peng Yue <penyue@gmail.com>
 * @copyright  2004-2017 Peng Yue
 */
class MerchantTest extends BaseTestCase
{
    const CSV_FILE_PATH = 'var/storage/data.csv';

    /**
     * @var Merchant
     */
    private $merchant;

    public function setUp()
    {
        parent::setUp();

        $this->merchant = new Merchant();
    }

    /**
     * Test the merchant class set transaction repository method
     */
    public function testItCanSetTransactionRepository()
    {
        $this->assertInstanceOf(
            Merchant::class,
            $this->merchant->setTransactionRepository(new TransactionTable(self::CSV_FILE_PATH)));
    }

    /**
     * Test the merchant class get transaction repository method
     */
    public function testItCanGetTransactionRepository()
    {
        $this->assertInstanceOf(
            TransactionTable::class,
            $this->merchant
                ->setTransactionRepository(new TransactionTable(self::CSV_FILE_PATH))
                ->getTransactionRepository()
        );
    }

    /**
     * @dataProvider merchantDataProvider
     * @expectedException \Awin\ReportTask\Bundle\ReportBundle\Exception\TransactionRepositoryNotFoundException
     * @expectedExceptionCode 10002
     * @expectedExceptionMessage Invalid transaction repository
     */
    public function testItCanThrowTransactionRepositoryExceptionWhenGetTransactionByMerchantIdWithoutTransactionRepository($merchantId)
    {
        $this->merchant->getTransactionsByMerchantId($merchantId);
    }

    /**
     * @dataProvider merchantDataProvider
     */
    public function testItCanGetTransactionsByMerchantId($merchantId, $expected)
    {
        $this->assertEquals(
            $expected,
            $this->merchant
                ->setTransactionRepository(new TransactionTable(self::CSV_FILE_PATH))
                ->getTransactionsByMerchantId($merchantId)
        );
    }

    /**
     * @return array
     */
    public function merchantDataProvider()
    {
        return [
            [1, $this->getTransactionByMerchantId(1)],
            [2, $this->getTransactionByMerchantId(2)],
        ];
    }
}