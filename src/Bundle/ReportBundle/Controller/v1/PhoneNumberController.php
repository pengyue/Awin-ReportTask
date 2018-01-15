<?php

namespace Awin\ReportTask\Bundle\ReportBundle\Controller\v1;

use Awin\ReportTask\Bundle\ReportBundle\Model\Merchant;
use Awin\ReportTask\Bundle\ReportBundle\Model\TransactionCsvStorage;
use Awin\ReportTask\Bundle\ReportBundle\Model\TransactionTable;
use Awin\ReportTask\Bundle\ReportBundle\Service\Observer\ReportObserver;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class ReportController extends FOSRestController
{
    /**
     * @ApiDoc(
     *  resource=false,
     *  description="The endpoint to generate merchant report",
     *  headers={
     *         {
     *             "name"="Access-Token",
     *             "description"="Domain ID or token"
     *         }
     *     },
     *     statusCodes={
     *          200="Returned when report is generated successful",
     *          403="Returned when request is not authorised",
     *          500={
     *           "Returned when the report generation fails",
     *           "Returned when input param lead to the validation failure"
     *         }
     *     },
     *     parameters={
     *         {"name"="merchant_id", "dataType"="int", "required"=false, "description"="the merchant_id for generate report"},
     *         {"name"="date", "dataType"="string", "required"=false, "description"="the date for generate report"}
     *     }
     *  )
     *
     * @return JsonResponse
     */
    public function generateReport(Request $request)
    {
        $merchantId = $request->get('merchant_id');
        $date       = $request->get('date');

        $reportService              = $this->container->get('app.report_service');
        $reportService->attach(new ReportObserver());
        $currencyService            = $this->container->get('app.currency_service');
        $merchantTransactionService = $this->container->get('app.merchant_transaction_service');
        $merchantTransactionService->setTransactionRepository(new TransactionTable('var/storage/data.csv'));
        $merchantTransactionService->setMerchantRepository(new Merchant());
        $storage                    = new TransactionCsvStorage('var/storage/data.csv');

        $reportService->generate($merchantTransactionService, $currencyService, $storage, $merchantId, $date);

        $response = new JsonResponse([
            'success' => true,
        ]);

        return $response;
    }
}