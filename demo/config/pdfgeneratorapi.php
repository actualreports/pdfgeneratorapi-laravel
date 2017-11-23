<?php
return [
    'token' => env('PDF_GENERATOR_TOKEN'),
    'secret' => env('PDF_GENERATOR_SECRET'),
    'base_url' => env('PDF_GENERATOR_BASE_URL', 'https://pdfgeneratorapi.com/api/v3/'),
    'default_workspace' => env('PDF_GENERATOR_DEFAULT_WORKSPACE'),
    /**
     * If set to true then a json file is generated and url is sent to service instead of posting the data
     */
    'use_data_url' => env('PDF_GENERATOR_USE_DATA_URL', true)
];