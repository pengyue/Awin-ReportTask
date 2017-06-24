<?php

namespace Awin\ReportTask\Behat\Context;

use Awin\ReportTask\Bundle\ReportBundle\Model\Merchant;
use Awin\ReportTask\Bundle\ReportBundle\Model\TransactionTable;
use Awin\ReportTask\Bundle\ReportBundle\Service\MerchantTransactionService;
use Behat\Behat\Context\Context;

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
        $this->merchantTransactionService->setTransactionRepository(new TransactionTable('var/storage/data.csv'));
    }

    /**
     * @Given The transaction data can be read on merchant :merchant_id
     */
    public function readTransactions($merchant_id)
    {
        $self = $this->merchantTransactionService->filterTransactionsByMerchantId($merchant_id);
        \PHPUnit_Framework_Assert::assertInstanceOf($this->merchantTransactionService, $self);
    }
}
