<?php

namespace Awin\ReportTask\Bundle\ReportBundle\Model;


class CurrencyWebservice
{
    /**
     * Return random value here for basic currencies like GBP USD EUR (simulates real API)
     * @param string $originalCurrency
     * @param string $targetCurrency
     *
     * @return float
     */
    public function getExchangeRate($originalCurrency, $targetCurrency)
    {
        $rate = null;

        switch ($originalCurrency) {
            case 'GBP':
                switch($targetCurrency) {
                    case 'GBP':
                        $rate = 1.00;
                        break;
                    case 'USD':
                        $rate = random_int(124, 139) / 100;
                        break;
                    case 'EUR':
                        $rate = random_int(111, 115) / 100;
                        break;
                    default:
                        break;
                }
                break;
            case 'USD':
                switch($targetCurrency) {
                    case 'GBP':
                        $rate = random_int(77, 82) / 100;
                        break;
                    case 'USD':
                        $rate = 1.00;
                        break;
                    case 'EUR':
                        $rate = random_int(87, 92) / 100;
                        break;
                    default:
                        break;
                }
                break;
            case 'EUR':
                switch($targetCurrency) {
                    case 'GBP':
                        $rate = random_int(85, 90) / 100;
                        break;
                    case 'USD':
                        $rate = random_int(109, 114) / 100;
                        break;
                    case 'EUR':
                        $rate = 1.00;
                        break;
                    default:
                        break;
                }
                break;
            default:
                break;
        }

        //TODO, if rate is not set, then throw exception


        return (float)$rate;
    }
}