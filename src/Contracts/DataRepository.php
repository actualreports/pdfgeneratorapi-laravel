<?php
/**
 * Created by tanel @26.11.17 18:03
 */

namespace ActualReports\PDFGeneratorAPILaravel\Contracts;


interface DataRepository
{
    /**
     * Returns data as array/object or url to public data file
     *
     * @return array|\stdClass|string
     */
    public function get();
    /**
     * Saves a temporary json file and generates public url that is sent to PDF Generator service
     *
     * @return string
     */
    public function getUrl();
    /**
     * Returns raw data collected from source
     *
     * @return mixed
     */
    public function getRawData();
}