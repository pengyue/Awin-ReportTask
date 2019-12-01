<?php

namespace Awin\ReportTask\Behat\Context;

use Awin\ReportTask\Bundle\ReportBundle\Model\TransactionCsvStorage;
use Awin\ReportTask\Bundle\ReportBundle\Service\CurrencyService;
use Awin\ReportTask\Bundle\ReportBundle\Service\Observer\ReportObserver;
use Awin\ReportTask\Bundle\ReportBundle\Service\ReportService;
use Awin\ReportTask\Bundle\ReportBundle\Model\Merchant;
use Awin\ReportTask\Bundle\ReportBundle\Model\TransactionTable;
use Awin\ReportTask\Bundle\ReportBundle\Service\MerchantTransactionService;
use Awin\ReportTask\Bundle\ReportBundle\Service\MerchantTransactionServiceInterface;
use PHPUnit\Framework\Assert;
use Behat\Behat\Context\Context;
use League\Csv\Reader;

/**
 * The behat context class for ReportTask
 *
 * @date       24/06/2017
 * @time       21:58
 * @author     Peng Yue <penyue@gmail.com>
 * @copyright  2004-2017 Peng Yue
 */

class ReportContext implements Context
{
    private $merchantTransactionService;

    public function __construct(MerchantTransactionService $merchantTransactionService)
    {
        $this->merchantTransactionService = $merchantTransactionService;
        $this->merchantTransactionService->setMerchantRepository(new Merchant());
    }

    /**
     * @Given The transaction data can be read on merchant id: :merchantId
     */
    public function theTransactionDataCanBeReadOnMerchantId($merchantId)
    {
        $this->merchantTransactionService->setTransactionRepository(new TransactionTable('var/storage/data.csv'));
        $self = $this->merchantTransactionService->filterTransactionsByMerchantId($merchantId);
        Assert::assertInstanceOf(MerchantTransactionServiceInterface::class, $self);
    }

    /**
     * @When I want to generate a report on merchant id: :merchantId
     */
    public function iWantToGenerateAReportOnMerchantId($merchantId)
    {
        $reportService = new ReportService();
        $reportService->attach(new ReportObserver());
        $reportService->setReportFilePath('var/storage/report.csv');
        $reportService->generate(
            $this->merchantTransactionService,
            new CurrencyService(),
            new TransactionCsvStorage('var/storage/data.csv'),
            $merchantId
        );
    }

    /**
     * @Then I should see transactions csv file generated with only merchant id: :merchantId
     */
    public function iShouldSeeTransactionsWithMerchantId($merchantId)
    {
        $csv = Reader::createFromPath('var/storage/report.csv');
        $csv->setDelimiter(',');
        $data = $csv->setOffset(1)->setLimit(15)->fetchAll();
        $isMerchantIdAlwaysCorrect = true;
        foreach ($data as $item) {
            if ($merchantId != $item[0]) {
                $isMerchantIdAlwaysCorrect = false;
            }
        }

        Assert::assertTrue($isMerchantIdAlwaysCorrect);
    }

}
