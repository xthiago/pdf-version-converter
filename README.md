# PDF version converter 
PHP library for converting the version of PDF files (for compatibility purposes).

[![Build Status](https://travis-ci.org/xthiago/pdf-version-converter.svg?branch=master)](https://travis-ci.org/xthiago/pdf-version-converter) 
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/33db053e-d59b-4787-9a03-e4ab1e2a7382/mini.png)](https://insight.sensiolabs.com/projects/33db053e-d59b-4787-9a03-e4ab1e2a7382)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/4f0a04e3cc2048deb7415cd669dcf2a1)](https://www.codacy.com/app/xthiago/pdf-version-converter?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=xthiago/pdf-version-converter&amp;utm_campaign=Badge_Grade)

## Requirements

- PHP 5.3+
- Ghostscript (gs command on Linux)

## Installation

Run `php composer.phar require xthiago/pdf-version-converter dev-master` or add the follow lines to composer and run `composer install`:

```
{
    "require": {
        "xthiago/pdf-version-converter": "dev-master"
    }
}
```

## Usage

Guessing a version of PDF File:

```php
<?php

// import the composer autoloader
require_once __DIR__.'/vendor/autoload.php'; 

// import the namespaces
use Xthiago\PDFVersionConverter\Guesser\RegexGuesser;
// [..]

$guesser = new RegexGuesser();
echo $guesser->guess('/path/to/my/file.pdf'); // will print something like '1.4'
```

Converting file to a new PDF version:

```php
<?php

// import the composer autoloader
require_once __DIR__.'/vendor/autoload.php'; 

// import the namespaces
use Symfony\Component\Filesystem\Filesystem,
    Xthiago\PDFVersionConverter\Converter\GhostscriptConverterCommand,
    Xthiago\PDFVersionConverter\Converter\GhostscriptConverter;

// [..]

$command = new GhostscriptConverterCommand();
$filesystem = new Filesystem();

$converter = new GhostscriptConverter($command, $filesystem);
$converter->convert('/path/to/my/file.pdf', '1.4');
```

## Contributing

Is really simple add new implementation of guesser or converter, just implement `GuessInterface` or `ConverterInterface`.

## Running unit tests

Run `phpunit -c tests`.
