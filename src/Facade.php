<?php
/**
 * Created by tanel @21.11.17 14:20
 */

namespace ActualReports\PDFGeneratorAPILaravel;

/**
 * Class Facade
 *
 * @package ActualReports\PDFGeneratorAPILaravel
 */
class Facade extends \Illuminate\Support\Facades\Facade
{
    public static function getFacadeAccessor()
    {
        return 'pdfgeneratorapi';
    }
}