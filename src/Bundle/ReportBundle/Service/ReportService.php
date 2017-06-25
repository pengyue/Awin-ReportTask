<?php

namespace Awin\ReportTask\Bundle\ReportBundle\Service;

use Awin\ReportTask\Bundle\ReportBundle\Service\Observer\ReportObserver;
use Awin\ReportTask\Bundle\ReportBundle\Service\Observer\ReportObserverInterface;
use Awin\ReportTask\Bundle\ReportBundle\Model\TransactionStorageInterface;

/**
 * The report service class for generating the report
 *
 * @date       22/06/2017
 * @time       13:09
 * @author     Peng Yue <penyue@gmail.com>
 * @copyright  2004-2017 Peng Yue
 */

class ReportService implements ReportServiceInterface
{
    /**
     * @var array|ReportObserverInterface[]
     */
    private $observers = [];

    /**
     * The report csv file path
     *
     * @var string
     */
    private $reportFilePath = 'var/storage/report.csv';

    /**
     * Generate the report
     *
     * @param MerchantTransactionServiceInterface   $merchantTransactionService
     * @param CurrencyServiceInterface              $currencyService
     * @param TransactionStorageInterface           $transactionStorage
     * @param int|null                              $merchantId
     * @param string|null                           $date
     *
     * @return bool
     */
    public function generate(
        MerchantTransactionServiceInterface $merchantTransactionService,
        CurrencyServiceInterface $currencyService,
        TransactionStorageInterface $transactionStorage,
        $merchantId,
        $date = null
    ) {
        $data = $merchantTransactionService
            ->filterTransactionsByMerchantId($merchantId)
            ->filterTransactionsByDate($date)
            ->getReportData();
        $results = $this->notify($data, $currencyService);

        return $transactionStorage->setData($results)->save($this->getReportFilePath());
    }

    /**
     * Set the report file path
     *
     * @param string $filePath
     *
     * @return $this
     */
    public function setReportFilePath($filePath)
    {
        $this->reportFilePath = $filePath;

        return $this;
    }

    /**
     * Get the csv file path
     *
     * @return string
     */
    public function getReportFilePath()
    {
        return $this->reportFilePath;
    }

    /**
     * Attach an observer for report generation
     *
     * @param ReportObserverInterface $observer
     *
     * @return $this
     */
    public function attach(ReportObserverInterface $observer)
    {
        $this->observers[] = $observer;

        return $this;
    }

    /**
     * detach an observer for report generation
     *
     * @param ReportObserverInterface $observer
     *
     * @return $this
     */
    public function detach(ReportObserverInterface $observer)
    {
        $key = array_search($observer, $this->observers);

        unset($this->observers[$key]);

        return $this;
    }

    /**
     * @param array           $data
     * @param CurrencyServiceInterface $currencyService
     *
     * @return array
     */
    public function notify(array $data, CurrencyServiceInterface $currencyService)
    {
        $result = [];
        foreach ($this->observers as $observer) {
            $result = $observer->listenReportGeneration($data, $currencyService);
        }

        return $result;
    }
}