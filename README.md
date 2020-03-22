## Requirements

PHP 7.3+

## Installation

Install the [Composer](https://getcomposer.org/).

From project root, run command :

```sh
$ composer install
```

## Available Scripts

### Run Tests

From project root, run command :

```sh
$ ./vendor/bin/phpunit
```

### Run dev

```sh
$ php -S localhost:8000 -t public
```


## Deploying

Install the [Heroku Toolbelt](https://toolbelt.heroku.com/).

```sh
$ git push heroku master
$ heroku open
```