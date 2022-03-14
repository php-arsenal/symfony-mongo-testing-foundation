# php-arsenal/symfony-mongo-testing-foundation

PhpUnit Symfony Unit, Functional, Integration test cases (+MongoDB) 

```
composer require --dev php-arsenal/symfony-mongo-testing-foundation
```

[![Release](https://img.shields.io/github/v/release/php-arsenal/symfony-mongo-testing-foundation)](https://github.com/php-arsenal/symfony-mongo-testing-foundation/releases)
[![CI](https://img.shields.io/github/workflow/status/php-arsenal/symfony-mongo-testing-foundation/CI)](https://github.com/php-arsenal/symfony-mongo-testing-foundation/actions/workflows/ci.yml)
[![Packagist](https://img.shields.io/packagist/dt/php-arsenal/symfony-mongo-testing-foundation)](https://packagist.org/packages/php-arsenal/symfony-mongo-testing-foundation)

## Setup

Define `MONGODB_DB` of your test database in `.env.test`

Example `tests/autoload.php`:
```php
<?php

$rootDir = realpath(__DIR__.'/..');

require $rootDir.'/vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;

$dotenv = (new Dotenv())->usePutenv(true);
$dotenv->load("$rootDir/.env");
$dotenv->load("$rootDir/.env.test");

if (file_exists("$rootDir/.env.test.local")) {
    $dotenv->load("$rootDir/.env.test.local");
}

```

## Features

Extend the following for your tests respectivelly as needed:
* `PhpArsenal\SymfonyMongoTestingFoundation\UnitTestCase`
* `PhpArsenal\SymfonyMongoTestingFoundation\FunctionalTestCase`
* `PhpArsenal\SymfonyMongoTestingFoundation\IntegrationTestCase`

Traits:
* `DatabaseTrait` handles commond DocumentManager functions and clears the test database upon test start.
* `FakerTrait` gives you access to the [faker](https://github.com/php-arsenal/Faker) to help you generate data for tests
* `LoggerTrait` gives you easy access to the logger if necessary
