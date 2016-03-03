===============================
Skyscanner PHP SDK
===============================

.. image:: https://travis-ci.org/projectivemotion/skyscanner-php-sdk.svg?branch=projectivemotion
    :target: https://travis-ci.org/projectivemotion/skyscanner-php-sdk


This is a temporary fork of ardydedase/skyscanner-php-sdk meant to bring the package back to life.
In order to use this repository I have had to modify the composer.json file to use in my own projects.
Feel free to use this code, the original project license remains as Apache 2.0.

If the original project remains unresponsive, I may decide to create a brand new fork of skyscanner-php-sdk at some point in the future,

Use at your own risk!


Skyscanner PHP SDK for Skyscanner's API

* Free software: Apache 2.0 license

Features
--------

* Tested on PHP 5.4, 5.5, 5.6 and HHVM
* Supports Flights, Hotels, and Carhire


Installation
------------

* Composer:
    "repositories": [
    {
      "url": "https://github.com/projectivemotion/skyscanner-php-sdk",
      "type": "vcs"
    }
    ],
    "require": {
        "Skyscanner/Skyscanner": "dev-projectivemotion"
    }
* composer update
