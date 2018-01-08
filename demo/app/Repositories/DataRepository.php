<?php
/**
 * Created by tanel @26.11.17 20:10
 */

namespace App\Repositories;

/**
 * Custom data repository implementation
 *
 * @package App\Repositories
 */

class DataRepository extends \ActualReports\PDFGeneratorAPILaravel\Repositories\DataRepository
{
    /**
     * @var string
     */
    protected $dataFolder = 'pdfgenerator';
    /**
     * @var string
     */
    protected $publicStorageFolder = 'storage';

    public function getRawData()
    {
        $data = \GuzzleHttp\json_decode(parent::getRawData());
        $data[0]->DocNumber = 'IE123121';
        return $data;
    }
}