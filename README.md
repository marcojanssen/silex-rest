silex-rest
==========

[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/marcojanssen/silex-rest/badges/quality-score.png?s=82920a9fad928479e615daee7ae5146f1ea09b1c)](https://scrutinizer-ci.com/g/marcojanssen/silex-rest/)
[![Code Coverage](https://scrutinizer-ci.com/g/marcojanssen/silex-rest/badges/coverage.png?s=9a2932344ecb42af4adcf0360f09bf78ee10eed9)](https://scrutinizer-ci.com/g/marcojanssen/silex-rest/)

Silex-rest is a REST implementation based on [Silex](http://silex.sensiolabs.org/),
[Doctrine](http://doctrine-project.org) and many other components. It allows you
to create a restful service in no-time.

Installation
------------

You can either set-up the project by yourself or use [Vagrant](http://www.vagrantup.com/).

 * Installation using Vagrant:
    * [Install vagrant](http://docs.vagrantup.com/v2/installation/index.html)
    * Run _vagrant up_
    * Verify your working service on _http://192.168.2.222/core/items_
 * Manual installation:
    * Make sure to install the project dependencies _apache2 mysql-server php5 php-apc php5-xdebug php5-cli php5-mysql_
    * [Install composer](http://getcomposer.org)
    * Run _composer update_
    * Edit _app/config/config.yml_ to match your requirements
    * Make sure the __cache__ and __logs__ that the directories you've configured are writable
    * Make sure the database you've configured exists
    * Run _app/console orm:schema-tool:update_ to load the database schema
    * Configure your webserver to point to _public/_ and enable mod_rewrite
    * Verify your working service
