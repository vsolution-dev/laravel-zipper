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
VSolutionDev\LaravelZipper\ServiceProvider::class,
```

And add the following line to the `aliases` array:

```php
'Zipper' => VSolutionDev\LaravelZipper\Facade::class,
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

Zipper::queue($files, 'qrcodes.zip', 's3')->chain([
    (new SendMail())->attachFromStorageDisk('qrcodes.zip', 's3')
]);
```

In this example, after queuing the files for zipping using Zipper::queue(), we chain it with the SendMail job. The SendMail job is responsible for sending an email with the zip file attached. It uses the attachFromStorageDisk() method to attach the zip file from the specified storage disk ('s3' in this case).

Make sure you have the SendMail job defined with the appropriate logic to send the email. You may need to customize it according to your email sending implementation.
