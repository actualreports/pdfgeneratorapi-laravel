<?php
/**
 * Created by tanel @26.11.17 17:57
 */

namespace ActualReports\PDFGeneratorAPILaravel\Services;


use ActualReports\PDFGeneratorAPILaravel\Repositories\DataRepository;

class PDFGeneratorAPI
{
    /**
     * @var \ActualReports\PDFGeneratorAPI\Client
     */
    protected $client;
    /**
     * @var string
     */
    protected $dataRepository = DataRepository::class;

    public function __construct($key, $secret, $defaultWorkspace = null)
    {
        $this->client = new \ActualReports\PDFGeneratorAPI\Client($key, $secret, $defaultWorkspace);
    }

    /**
     * Class name of data repository
     *
     * @param string $repository
     */
    public function setDataRepository($repository)
    {
        $this->dataRepository = $repository;
    }

    /**
     * @return string
     */
    public  function getDataRepository()
    {
        return $this->dataRepository;
    }

    public function __call($name, $arguments)
    {
        if (method_exists($this->client, $name))
        {
            return call_user_func_array([$this->client, $name], $arguments);
        }

        return null;
    }
}