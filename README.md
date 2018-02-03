# laravel-lazydev
[![Latest Stable Version](https://poser.pugx.org/blinktag/laravel-lazydev/v/stable?format=flat-square)](https://packagist.org/packages/blinktag/laravel-lazydev) [![Total Downloads](https://poser.pugx.org/blinktag/laravel-lazydev/downloads?format=flat-square)](https://packagist.org/packages/blinktag/laravel-lazydev) [![Latest Unstable Version](https://poser.pugx.org/blinktag/laravel-lazydev/v/unstable?format=flat-square)](https://packagist.org/packages/blinktag/laravel-lazydev) [![License](https://poser.pugx.org/blinktag/laravel-lazydev/license?format=flat-square)](https://packagist.org/packages/blinktag/laravel-lazydev)

Finds TODO and FIXME annotations that past-you left in the code for future-you to deal with

## Install

```
composer require-dev blinktag/laravel-lazydev:dev-master
```

### Laravel < 5.5:

```
'providers' => [

    // ...
    
    Blinktag\Providers\FindTodosServiceProvider::class,
],
```
### Laravel >= 5.5

The package will be autodiscovered

### Configuration

If you wish to change the strings this tool searches for, publish the configuration and then edit the value of `find_strings` in `config/findtodos.php`. Each term should be separated by a pipe character

```
php artisan vendor:publish --tag=findtodos
```

## Usage

Add comments in your code that begin with TODO, or FIXME, like so:

```
<?php

...

    public function calculateStore(int $total)
    {
    	...
    	// TODO: This method is broken when the input is a negative value
    	...
    }
...
```

You can then find these comments later on using the command

```
php artistan find:todos
...
/Users/blinktag/Sites/compucorp/app/User.php
 TODO Line 53: Refactor this once permissions have been fleshed out

/Users/blinktag/Sites/compucorp/app/Ticket.php
FIXME Line 109: This method is broken when the input is a negative value
```
