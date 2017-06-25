<?php

namespace Awin\ReportTask\Bundle\ReportBundle\Utility;

/**
 * Quick utility class for the static method usuage
 *
 * @date       25/06/2017
 * @time       22:19
 * @author     Peng Yue <penyue@gmail.com>
 * @copyright  2004-2017 Peng Yue
 */

class Helper
{
    /**
     * recursively intersect arrays
     *
     * @param array $array1
     * @param array $array2
     *
     * @return array
     */
    public static function array_intersect_recursive($array1, $array2)
    {
        foreach($array1 as $key => $value) {
            if (!isset($array2[$key])) {
                unset($array1[$key]);
            } else {
                if (is_array($array1[$key])) {
                    $array1[$key] = self::array_intersect_recursive($array1[$key], $array2[$key]);
                } elseif ($array2[$key] !== $value) {
                    unset($array1[$key]);
                }
            }
        }
        return $array1;
    }
}