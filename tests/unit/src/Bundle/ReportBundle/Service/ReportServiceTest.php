<?php

namespace Awin\ReportTask\UnitTest\Bundle\ReportBundle\Service;

use Awin\ReportTask\Bundle\ReportBundle\Model\TransactionCsvStorage;
use Awin\ReportTask\Bundle\ReportBundle\Service\CurrencyService;
use Awin\ReportTask\Bundle\ReportBundle\Service\MerchantTransactionService;
use Awin\ReportTask\Bundle\ReportBundle\Service\Observer\ReportObserver;
use Awin\ReportTask\Bundle\ReportBundle\Service\ReportService;
use PHPUnit\Framework\TestCase ;
use Prophecy\Argument;

/**
 * The report service test
 *
 * @date       24/06/2017
 * @time       17:59
 * @author     Peng Yue <penyue@gmail.com>
 * @copyright  2004-2017 Peng Yue
 */

class ReportServiceTest extends TestCase
{
    /**
     * @var ReportService
     */
    private $reportService;

    public function setUp()
    {
        $this->reportService = new ReportService();
    }

    /**
     * Test attach and detach of the observer
     */
    public function testItCanAttachAndDetachObserver()
    {
        $observer = new ReportObserver();

        $self = $this->reportService->attach($observer);

        $this->assertInstanceOf(ReportService::class, $self);

        $result = $this->reportService->detach($observer);

        $this->assertInstanceOf(ReportService::class, $result);
    }

    /**
     * Test it can notify the observer
     */
    public function testItCanNotify()
    {
        $sourceData = [
            [1, 2],
            [3, 4]
        ];
        $resultData = [
            [1,2,3],
            [3,4,5]
        ];
        $mockCurrencyService = $this->prophesize(CurrencyService::class);
        $mockObserver = $this->prophesize(ReportObserver::class);
        $mockObserver->listenReportGeneration($sourceData, $mockCurrencyService->reveal())->willReturn($resultData);
        $this->reportService->attach($mockObserver->reveal());

        $result = $this->reportService->notify($sourceData, $mockCurrencyService->reveal());

        $this->assertSame($resultData, $result);
    }

    /**
     * Test it can generate report
     */
    public function testItCanGenerate()
    {
        $merchantId = 1;
        $sourceData = [
            [1, 2],
            [16, 256]
        ];
        $resultData = [
            [1, 2, 4],
            [16, 256, 65536]
        ];
        $mockedMerchantTransactionService = $this->prophesize(MerchantTransactionService::class);
        $mockedMerchantTransactionService
            ->filterTransactionsByMerchantId($merchantId)
            ->willReturn($mockedMerchantTransactionService->reveal());
        $mockedMerchantTransactionService
            ->filterTransactionsByDate(Argument::cetera())
            ->willReturn($mockedMerchantTransactionService->reveal());
        $mockedMerchantTransactionService->getReportData()->willReturn($resultData);
        $mockedCurrencyService = $this->prophesize(CurrencyService::class);
        $mockedTransactionCsvStorage = $this->prophesize(TransactionCsvStorage::class);
        $mockedTransactionCsvStorage->setData($resultData)->willReturn($mockedTransactionCsvStorage);
        $mockedTransactionCsvStorage->save('var/storage/test/test.csv')->willReturn(true);
        $mockObserver = $this->prophesize(ReportObserver::class);
        $mockObserver->listenReportGeneration(Argument::cetera(), $mockedCurrencyService->reveal())->willReturn($resultData);
        $this->reportService->attach($mockObserver->reveal())->setReportFilePath('var/storage/test/test.csv');

        $result = $this->reportService->generate(
            $mockedMerchantTransactionService->reveal(),
            $mockedCurrencyService->reveal(),
            $mockedTransactionCsvStorage->reveal(),
            $merchantId,
            '01/05/2010'
        );

        $this->assertTrue($result);

    }
}