<?php

namespace Awin\ReportTask\UnitTest\Bundle\ReportBundle\Service;

use Awin\ReportTask\Bundle\ReportBundle\Model\Merchant;
use Awin\ReportTask\Bundle\ReportBundle\Model\TransactionTable;
use Awin\ReportTask\Bundle\ReportBundle\Service\MerchantTransactionService;
use Awin\ReportTask\Bundle\ReportBundle\Service\MerchantTransactionServiceInterface;
use PHPUnit\Framework\TestCase;

/**
 * Merchant Transaction aggregation service test class
 *
 * @date       24/06/2017
 * @time       19:25
 * @author     Peng Yue <penyue@gmail.com>
 * @copyright  2004-2017 Peng Yue
 */

class MerchantTransactionServiceTest extends TestCase
{
    /**
     * @var MerchantTransactionServiceInterface
     */
    private $merchantTransactionService;

    public function setUp()
    {
        $this->merchantTransactionService = new MerchantTransactionService();
    }

    public function testItCanGetAllTransactionsWithoutMerchantId()
    {

        $mockedTransactionRepository = $this->prophesize(TransactionTable::class);
        $mockedTransactionRepository->getTransactions()->willReturn([1,2,3]);
        $this->merchantTransactionService->setTransactionRepository($mockedTransactionRepository->reveal());

        $resultWithoutMerchantService = $this->merchantTransactionService
            ->filterTransactionsByMerchantId();
        $this->assertSame($this->merchantTransactionService, $resultWithoutMerchantService);
        $this->assertSame([1, 2, 3], $resultWithoutMerchantService->getReportData());
    }

    public function testItCanGetTransactionsWithMerchantId()
    {
        $mockedTransactionRepository = $this->prophesize(TransactionTable::class);
        $mockedTransactionRepository->getTransactions()->willReturn([1,2,3]);
        $this->merchantTransactionService->setTransactionRepository($mockedTransactionRepository->reveal());

        $mockedMerchantRepository = $this->prophesize(Merchant::class);
        $mockedMerchantRepository
            ->setTransactionRepository($mockedTransactionRepository->reveal())
            ->willReturn($mockedMerchantRepository->reveal());
        $mockedMerchantRepository->getTransactionsByMerchantId(1)->willReturn([4,5,6]);
        $this->merchantTransactionService->setMerchantRepository($mockedMerchantRepository->reveal());
        $resultWithMerchantService  = $this->merchantTransactionService->filterTransactionsByMerchantId(1);

        $this->assertSame($this->merchantTransactionService, $resultWithMerchantService);
        $this->assertSame([4,5,6], $resultWithMerchantService->getReportData());
    }
}