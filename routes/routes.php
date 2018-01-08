<?php

Route::group(['prefix' => 'pdfgenerator', 'as' => 'pdfgeneratorapi', 'namespace' => 'ActualReports\PDFGeneratorAPILaravel\Http\Controllers', 'middleware' => ['web']], function() {
    Route::group(['prefix' => 'templates', 'as' => '.templates'], function() {
        Route::get('', 'TemplateController@getAll')->name('all');
        Route::get('{template}', 'TemplateController@get')->name('get');
        Route::match(['GET', 'POST'],'{template}/{output}/{format}', 'TemplateController@output')->name('output');
        Route::match(['GET', 'POST'],'new', 'TemplateController@openNew')->name('new');
        Route::match(['GET', 'POST'],'{template}/edit', 'TemplateController@edit')->name('edit');
        Route::match(['GET', 'POST'],'{template}/copy', 'TemplateController@editAsCopy')->name('copy');
    });
});