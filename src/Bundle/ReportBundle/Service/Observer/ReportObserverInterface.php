<?php

namespace Awin\ReportTask\Bundle\ReportBundle\Service\Observer;

use Awin\ReportTask\Bundle\ReportBundle\Service\CurrencyServiceInterface;

/**
 * The interface defines the default observer behaviours
 *
 * @date       24/06/2017
 * @time       18:01
 * @author     Peng Yue <penyue@gmail.com>
 * @copyright  2004-2017 Peng Yue
 */

interface ReportObserverInterface
{
    /**
     * Listen to report generation
     *
     * @param array             $data
     * @param CurrencyServiceInterface   $currencyService
     *
     * @return mixed
     */
    public function listenReportGeneration(array $data, CurrencyServiceInterface $currencyService);
}