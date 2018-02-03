# laravel-lazydev
[![Latest Stable Version](https://poser.pugx.org/blinktag/laravel-lazydev/v/stable?format=flat-square)](https://packagist.org/packages/blinktag/laravel-lazydev) [![Total Downloads](https://poser.pugx.org/blinktag/laravel-lazydev/downloads?format=flat-square)](https://packagist.org/packages/blinktag/laravel-lazydev) [![Latest Unstable Version](https://poser.pugx.org/blinktag/laravel-lazydev/v/unstable?format=flat-square)](https://packagist.org/packages/blinktag/laravel-lazydev) [![License](https://poser.pugx.org/blinktag/laravel-lazydev/license?format=flat-square)](https://packagist.org/packages/blinktag/laravel-lazydev)

Finds TODO and FIXME annotations that past-you left in the code for future-you to deal with

## Install

```
composer require-dev blinktag/laravel-lazydev:dev-master
```

For laravel < 5.5:

```
'providers' => [

    // ...
    
    Blinktag\Providers\FindTodosServiceProvider::class,
],
```

Publish configuration, if you wish to change the strings it searches for

```
php artisan vendor:publish --tag=findtodos
```

## Usage
```
php artistan find:todos
...
/Users/blinktag/Sites/compucorp/app/User.php
 TODO Line 53: Refactor this once permissions have been fleshed out

/Users/blinktag/Sites/compucorp/app/Ticket.php
FIXME Line 109: This method is broken when the input is a negative value
```
