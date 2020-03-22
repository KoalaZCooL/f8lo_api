## Run Tests

From project root, run command :

```sh
$ ./vendor/bin/phpunit
```

## Run dev

```sh
$ php -S localhost:8000 -t public
```


## Deploying

Install the [Heroku Toolbelt](https://toolbelt.heroku.com/).

```sh
$ git push heroku master
$ heroku open
```