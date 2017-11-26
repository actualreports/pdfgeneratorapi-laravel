# PDF Generator API for Laravel

This Laravel package creates controllers and routes to easily use the [PDF Generator API](https://pdfgeneratorapi.com) service. 
It also includes javascript interface to print and download generated files, and to open templates in the editor view.

## Install
Require this package with composer using the following command:
```bash
composer require actualreports/pdfgeneratorapi-laravel
```

If you don't use Laravel 5.5 auto-discovery, add the service provider and the alias to config/app.php. If you run Laravel 5.5 this is done automatically.

Add provider
```php
ActualReports\PDFGeneratorAPILaravel\ServiceProvider::class,
```

Add alias
```php
'PDFGeneratorAPI' => ActualReports\PDFGeneratorAPILaravel\Facade::class,
```

Publishes configuration file to your config folder (config/pdfgeneratorapi.php)
```bash
php artisan vendor:publish --provider="ActualReports\PDFGeneratorAPILaravel\ServiceProvider" --tag=config
```

Publishes javascript to your assets folder (resources/assets/vendor/pdfgeneratorapi/index.js)
```bash
php artisan vendor:publish --provider="ActualReports\PDFGeneratorAPILaravel\ServiceProvider" --tag=public --force
```

## Configuration

Add configuration to your .env file
```dotenv
PDF_GENERATOR_KEY={YOUR_API_KEY}
PDF_GENERATOR_SECRET={YOUR_API_SECRET}
PDF_GENERATOR_USE_DATA_URL=true
```
If "PDF_GENERATOR_USE_DATA_URL" is set to true then we send an url to your data file instead of sending the data in request body.
This feature exists because of the url length limitations. When we redirect user to the editor view with json data in query param then we have are limited to browser url length and the data string can be incomplete.

To make this feature work you need to link your public folder. Data files are saved to /storage/app/public/pdfgenerator
```bash
php artisan storage:link
```


## Usage
We have included the javascript interface that makes calls to routes defined below and displays the result in browser.
If you need more flexibility you can create your own front end interface that use the routes. 

### Data Repository
The main controller depends on ActualReports\PDFGeneratorAPILaravel\Repositories\DataRepository to get the data that is sent to PDF Generator service. 
By default the data repository looks for the "data" parameter in request using Input::get('data').

You can add your own DataRepository by implementing interface ActualReports\PDFGeneratorAPILaravel\Contracts\DataRepository and overriding the binding
in your AppServiceProvider.php register() function.

```php
$this->app->bind(
    'ActualReports\PDFGeneratorAPILaravel\Contracts\DataRepository',
    'App\Repositories\DataRepository'
);
```

### Routes
Available routes
* pdfgenerator.templates.all: GET /pdfgenerator/templates
* pdfgenerator.templates.get: GET /pdfgenerator/templates/{template}
* pdfgenerator.templates.output: GET|POST /pdfgenerator/templates/{template}/{output}/{format} 
* pdfgenerator.templates.new: GET|POST /pdfgenerator/templates/new 
* pdfgenerator.templates.edit: GET|POST /pdfgenerator/templates/{template}/edit 
* pdfgenerator.templates.copy: GET|POST /pdfgenerator/templates/{template}/copy


### JavaScript interface
Fetches list of available templates
```javascript
PDFGeneratorAPI.list().then((response) => {
  console.log(response);      
});
```

Executes print command that generates the document and opens browser print dialog
```javascript
PDFGeneratorAPI.print(template, format, data).then(() => {
  console.log('resolve print promise');
});
```

Executes download command that generates the document and starts download automatically
```javascript
PDFGeneratorAPI.download(template, format, data).then(() => {
  console.log('resolve download promise');
});
```

Executes inline command that generates the document and opens it in new tab/window
```javascript
PDFGeneratorAPI.inline(template, format, data).then(() => {
  console.log('resolve inline promise');
});
```

Opens new tab/window with template editor
```javascript
PDFGeneratorAPI.edit(template, data).then(() => {
  console.log('resolves when editor tab/window is closed');
});
```

Creates a copy of a given template and opens new tab/window with template editor
```javascript
PDFGeneratorAPI.copy(template, data, 'New name for copied template').then(() => {
  console.log('resolves when editor tab/window is closed');
});
```