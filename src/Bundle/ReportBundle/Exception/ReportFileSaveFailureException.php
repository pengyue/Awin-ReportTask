<?php

namespace Awin\ReportTask\Bundle\ReportBundle\Exception;

use RuntimeException;

/**
 * The exception on saving the csv report file
 *
 * @date       25/06/2017
 * @time       22:15
 * @author     Peng Yue <penyue@gmail.com>
 * @copyright  2004-2017 Peng Yue
 */

class ReportFileSaveFailureException extends RuntimeException
{
    public function __construct($path)
    {
        $message = "The report file could not be saved to %s";

        parent::__construct(sprintf($message, $path), ErrorCode::REPORT_FILE_SAVING_FAILURE_ERROR_CODE);
    }
}