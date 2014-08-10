silex-rest
==========

[![Build Status](https://travis-ci.org/marcojanssen/silex-rest.png?branch=master)](https://travis-ci.org/marcojanssen/silex-rest)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/marcojanssen/silex-rest/badges/quality-score.png?s=82920a9fad928479e615daee7ae5146f1ea09b1c)](https://scrutinizer-ci.com/g/marcojanssen/silex-rest/)

## Features ##

- Easy setup for a RESTful API
- Uses [Doctrine](http://doctrine-project.org) and [JMS Serializer](http://jmsyst.com/libs/serializer)
- Completly configurable with routes
- Swagger for API documentation
- Completly customizable by event driven design

## Installation

- Install [Composer](http://getcomposer.org)
- Install/update your dependencies

```cli
composer install
```

- Create your database (default database name: silexrest)
- Create your database schema (default database username/password: root/root)

```cli
php app/console orm:schema-tool:create
```

- Access the API at http://domain/core/items

## Todo

- Usage documentation
- Implement caching
- Implement hypermedia
- Implement multiple formats (only supports JSON)