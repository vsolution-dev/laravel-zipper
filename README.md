# Laravel Zipper Library

This library provides a convenient way to zip files in Laravel applications.

## Installation

You can install this library using Composer. Run the following command:

```
composer require vsolution-dev/laravel-zipper
```

After the installation, you need to add the service provider and facade to your `app.php` configuration file.

Add the following line to the `providers` array:

```php
VSolutionDev\LaravelZipper\ZipperServiceProvider::class,
```

And add the following line to the `aliases` array:

```php
'Zipper' => VSolutionDev\LaravelZipper\ZipperFacade::class,
```

## Requirements

This library requires PHP 7.4 or above. Make sure your server meets this requirement before installing.

## Usage

To use this library, you can follow the example below:

```php
$files = collect();

Qrcode::query()
    ->lazyById(1000)
    ->each(function ($qrcode) use ($files) {
        $files->add([
            'path' => $qrcode->url, // http://www.example.com/qrcode.png
            'name' => "{$qrcode->id}.png"
        ]);
    });

\Zipper::queue($files, 'qrcode.zip', 's3');
```

In the above example, we create a collection of files to be added to the zip archive. Each file is represented by an array with path and name properties. The path should be the URL or file path of the file, and the name should be the desired name of the file inside the zip archive.

The \Zipper::queue() method is used to queue the files for zipping. The first argument is the collection of files, the second argument is the desired name of the zip archive, and the third argument is the storage type (in this case, 's3' represents Amazon S3).

Feel free to customize and expand on this example based on your specific use case.

For more information on how to use this library, please refer to the package documentation.