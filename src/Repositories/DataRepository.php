<?php
/**
 * Created by tanel @26.11.17 18:03
 */

namespace ActualReports\PDFGeneratorAPILaravel\Repositories;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

class DataRepository implements \ActualReports\PDFGeneratorAPILaravel\Contracts\DataRepository
{
    /**
     * @var string
     */
    protected $dataFolder = 'pdfgenerator';
    /**
     * @var string
     */
    protected $publicStorageFolder = 'storage';


    /**
     * Saves a temporary json file and generates public url that is sent to PDF Generator service
     *
     * @return string
     */
    public function getUrl()
    {
        $data = $this->getRawData();
        /**
         * If data is already an url don't save new file
         */
        if ($data && !filter_var($data, FILTER_VALIDATE_URL) !== false)
        {
            if(!is_string($data))
            {
                $data = \GuzzleHttp\json_encode($data);
            }
            $identifier = Auth::guest() ? time() : Auth::id();
            $file = $this->dataFolder.DIRECTORY_SEPARATOR.md5($identifier).'.json';
            Storage::disk('public')->put($file, $data);

            $data =  asset($this->publicStorageFolder.DIRECTORY_SEPARATOR.$file);
        }

        return $data;
    }

    /**
     * Returns data as array/object or url to public data file
     *
     * @return array|\stdClass|string
     */
    public function get()
    {
        $data = $this->getRawData();

        if (!$data)
        {
            $data = ['dummy' => 'data'];
        }

        if ($this->useDataUrl())
        {
            $data = $this->getUrl();
        }
        return $data;
    }

    /**
     * Returns raw data
     *
     * @return mixed
     */
    public function getRawData()
    {
        return Input::get('data');
    }

    /**
     * @return bool
     */
    protected function useDataUrl()
    {
        return config('pdfgeneratorapi.use_data_url');
    }
}