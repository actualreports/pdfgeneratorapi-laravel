<?php
/**
 * Created by tanel @21.11.17 16:09
 */

namespace ActualReports\PDFGeneratorAPILaravel\Http\Controllers;

use \ActualReports\PDFGeneratorAPI\Client as APIClient;
use ActualReports\PDFGeneratorAPI\Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

class TemplateController extends Controller
{
    const OUTPUT_INLINE = 'inline';
    const OUTPUT_DOWNLOAD = 'download';
    const OUTPUT_PRINT = 'print';

    protected $dataFolder = 'pdfgenerator';
    protected $publicStorageFolder = 'storage';

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll(Request $request)
    {
        $templates = collect(\PDFGeneratorAPI::getAll());

        return response()->json([
            'private' => $templates->filter(function($t) {
                return $t->owner;
            })->values(),
            'default' => $templates->filter(function($t) {
                return !$t->owner;
            })->values()
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param $template
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(Request $request, $template)
    {
        try
        {
            $template = \PDFGeneratorAPI::get($template);
        }
        catch(Exception $e)
        {
            return response()->json([
                'error' => $e->getMessage()
            ], $e->getCode());
        }

        return response()->json($template);
    }

    /**
     * @param Request $request
     * @param integer $template
     * @param string $output
     * @param string $format
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function output(Request $request, $template, $output = self::OUTPUT_DOWNLOAD, $format = APIClient::FORMAT_PDF)
    {
        $name = $request->get('name');

        /**
         * Automatically triggers print action once the file is loaded
         */
        $params = [
            'print' => $output === self::OUTPUT_PRINT ? 1 : 0
        ];

        $data = $this->getData();

        try
        {
            $result = \PDFGeneratorAPI::output($template, $data, $format, $name, $params);
        }
        catch(Exception $e)
        {
            return response()->json([
                'error' => $e->getMessage()
            ], $e->getCode());
        }

        $headers = [
            'Content-type' => $result->meta->{'content-type'},
            'Cache-Control' => 'private, must-revalidate, post-check=0, pre-check=0, max-age=1',
            'Cache' => 'public',
            'Expires' => 'Sat, 26 Jul 1997 05:00:00 GMT',
            'Last-Modified' => gmdate('D, d M Y H:i:s').' GMT',
            'Content-Disposition' => 'inline; filename="'.$result->meta->name.'"'
        ];

        if ($output === self::OUTPUT_DOWNLOAD)
        {
            $headers['Content-Description'] = 'File Transfer';
            $headers['Content-Disposition'] = 'attachment; filename="'.$result->meta->name.'"';
            $headers['Content-Transfer-Encoding'] = 'binary';
        }

        return response(base64_decode($result->response), 200, $headers);
    }

    /**
     * Redirects to editor to edit the new template
     *
     * @param \Illuminate\Http\Request $request
     * @param integer $template
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request, $template)
    {
        $data = $this->getData();

        return redirect()->away(\PDFGeneratorAPI::editor($template, $data));
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function openNew(Request $request)
    {
        $data = $this->getData();

        return redirect()->away(\PDFGeneratorAPI::editor(null, $data));
    }

    /**
     * Creates a copy of given template and redirects to editor to edit the new template
     *
     * @param \Illuminate\Http\Request $request
     * @param integer $template
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editAsCopy(Request $request, $template)
    {
        $name = $request->get('name');
        $data = $this->getData();

        try
        {
            $newTemplate = \PDFGeneratorAPI::copy($template, $name);
        }
        catch(Exception $e)
        {
            return response()->json([
                'error' => $e->getMessage()
            ], $e->getCode());
        }

        return redirect()->away(\PDFGeneratorAPI::editor($newTemplate->id, $data));
    }

    /**
     * @return bool
     */
    protected function useDataUrl()
    {
        return config('pdfgeneratorapi.use_data_url');
    }

    /**
     * Saves a temporary json file and generates public url that is sent to PDF Generator service
     *
     * @param array|\stdClass $data
     * @return string
     */
    protected function getDataUrl($data)
    {
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
     * Override to implement your own data source
     * @return array|mixed|string
     */
    protected function getData()
    {
        $data = Input::get('data');

        if (!$data)
        {
            $data = ['dummy' => 'data'];
        }

        if ($this->useDataUrl())
        {
            $data = $this->getDataUrl($data);
        }

        return $data;
    }

}