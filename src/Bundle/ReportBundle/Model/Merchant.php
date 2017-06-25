<?php

namespace Awin\ReportTask\Bundle\ReportBundle\Model;

use Awin\ReportTask\Bundle\ReportBundle\Exception\TransactionRepositoryNotFoundException;

/**
 * The merchant model class, which is for the merchant data management
 *
 * @date       24/06/2017
 * @time       13:15
 * @author     Peng Yue <penyue@gmail.com>
 * @copyright  2004-2017 Peng Yue
 */

class Merchant
{
    /**
     * Set the transaction repository
     *
     * @var TransactionTable
     */
    private $transactionRepository;

    public function setTransactionRepository(TransactionTable $transactionTable)
    {
        $this->transactionRepository = $transactionTable;

        return $this;
    }

    /**
     * Get the transaction repository
     *
     * @return TransactionTable
     */
    public function getTransactionRepository()
    {
        return $this->transactionRepository;
    }

    /**
     * Get the transactions by merchant id
     *
     * @param int $merchantId
     *
     * @return array
     */
    public function getTransactionsByMerchantId($merchantId)
    {
        if (!$this->getTransactionRepository() instanceof TransactionTable) {
            throw new TransactionRepositoryNotFoundException();
        }

        return array_filter(
            $this->getTransactionRepository()->getTransactions(),
            function ($e) use ($merchantId) {
                return (int)$e[0] === (int)$merchantId;
            }
        );
    }
}