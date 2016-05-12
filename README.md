# Hotelscom-Scraper
Hotels.com Price Scraper

[![Build Status](https://travis-ci.org/projectivemotion/hotelscom-scraper.svg?branch=master)](https://travis-ci.org/projectivemotion/hotelscom-scraper)

## Use at your own risk!
* I am not responsible for your use of this software.
* Please do not abuse!
* Please do not be stupid!


### Installation

Using Github:

    $ git clone https://github.com/projectivemotion/hotelscom-scraper.git
    $ cd hotelscom-scraper ; php -f tests/demo.php
    
Using Composer:

    composer require projectivemotion/hotelscom-scraper


## Usage

For an example see tests/demo.php!

    $ php -f vendor/projectivemotion/hotelscom-scraper/tests/demo.php
    Usage:
        tests/demo.php [use-cache] [location] [checkin-date] [checkout-date]
        
    Arguments:
        use-cache:      0 or 1
        location:       City, Country
        checkin-date:   YYYY-mm-dd Format
        checkout-date:  YYYY-mm-dd Format
        
    Examples:
        $ php -f tests/demo.php 1 "Cancun, Mexico" 2016-11-16 2016-11-19
        $ php -f tests/demo.php 1 "Madrid, Spain" $(date +%Y-%m-%d) $(date -d '5 days' +%Y-%m-%d)



# License
The MIT License (MIT)

Copyright (c) 2016 Amado Martinez

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
