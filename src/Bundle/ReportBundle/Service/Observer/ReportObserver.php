<?php

namespace Awin\ReportTask\Bundle\ReportBundle\Service\Observer;

use Awin\ReportTask\Bundle\ReportBundle\Service\CurrencyServiceInterface;

/**
 * Get the exchange rate from currency service and and do the currency exchange calculation
 *
 * @date       24/06/2017
 * @time       17:56
 * @author     Peng Yue <penyue@gmail.com>
 * @copyright  2004-2017 Peng Yue
 */

class ReportObserver implements ReportObserverInterface
{
    /**
     * Listen to the report generation and apply the changes to data
     *
     * @param array                     $data
     * @param CurrencyServiceInterface  $currencyService
     *
     * @return array
     */
    public function listenReportGeneration(array $data, CurrencyServiceInterface $currencyService)
    {
        $results = [];
        foreach ($data as $item) {
            $temp = [];
            foreach ($currencyService->getCurrencies() as $currency => $symbol) {
                $convertAmount = $currencyService
                    ->getCurrencyConverter()
                    ->setOriginalCurrency($currencyService->symbolToCurrency($item[3]))
                    ->setTargetCurrency($currency)
                    ->convert($item[4]);
                $temp[] = sprintf('%s%.2F', $symbol, $convertAmount);
            }
            $results[] = array_merge($item, $temp);
        }

        return $results;
    }
}