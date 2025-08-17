# Setup
This application is setup to run with PHP 8.4. If the php version is below that, application will exit.


## Dependencies
This application requires the following dependencies:

- PHP 8.4 or above
- Composer
- Laravel Prompts (Laravel Prompts supports macOS, Linux, and Windows with WSL)
- PHPUnit
- Mockery

## Getting Started

Assuming that the you have installed with latest PHP version (8.4+), please clone the repository:
*Note: I have only tested this application with PHP 8.4. If you want to try with other old versions, please comment the lines 17 - 20 in the beesinthetrap file. Also update the php version in composer.json*

``` bash
$ git clone https://github.com/kurianvarkey/php-game-beesinthetrap.git php-game-beesinthetrap
$ cd php-game-beesinthetrap
$ composer install
$ php beeinthetrap
```

## Running Tests
```
composer test
```