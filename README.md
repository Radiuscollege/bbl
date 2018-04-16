# Progress Tracker

A web app to track the progress of BBL AMO students of the Application- and Mediadevelopment course at ROC West Brabant

# Installation

* Fork this project
* Clone the project you just cloned
* Set the git upstream to https://github.com/Radiuscollege/bbl ( `git remote add upstream https://github.com/Radiuscollege/bbl` )


```sh
$ git clone https://github.com/Radiuscollege/bbl
$ git fork https://github.com/'yourname'/bbl
$ git remote add upstream https://github.com/Radiuscollege/bbl
```

To install the assets run:

```
$ npm install
```

or

```sh
$ yarn install
```

Then install the Laravel dependencies, fill the database

```
$ composer install
$ php artisan migrate --seed
```

## Configuration

* Copy .env.example and rename it to .env
* Request an AMO Client API key and Secret from [@bartjroos](https://github.com/bartjroos) and include it in the .env
* Edit the .env file to the right values (MySQL database credentials) if necesarry

## Contributing

* Commit and push to forked repository
* Create a pull request to github.com/Radiuscollege/bbl (through the UI of your forked repository on github.com)
* Please be descriptive in the pull request
* Comply with the PSR-1 and PSR-2 PHP code standards

## Running the application

To compile the assets run:

```
$ npm run watch
```

Finally, to run PHP's built-in development server:

* Run `php artisan serve` or use [Laravel Homestead](https://laravel.com/docs/master/homestead) to run the application

## Laravel packages

* https://github.com/StudioKaa/amoclient
