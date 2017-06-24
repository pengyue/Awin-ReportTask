<?php

namespace Awin\ReportTask\Bundle\ReportBundle\Controller\v1;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Description of DefaultController
 */
class DefaultController extends Controller
{
    /**
     * This is the documentation description of your method, it will appear
     * on a specific pane. It will read all the text until the first
     * annotation.
     *
     * @Nelmio\ApiDocBundle\Annotation\ApiDoc(
     *  resource=false,
     *  description="This is a description of your API method",
     *  headers={
     *         {
     *             "name"="Access-Token",
     *             "description"="Domain ID or token"
     *         }
     *     },
     *     statusCodes={
     *         200="Returned when successful",
     *         403="Returned when the user is not authorized to say hello",
     *         404={
     *           "Returned when the user is not found",
     *           "Returned when something else is not found"
     *         }
     *     }
     * )
     */
    public function getStatusAction()
    {
        return new JsonResponse(
            ['status' => 'OK']
        );
    }
}