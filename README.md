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

## Example Output
*Checkout Price is currently broken, Sorry! I will fix this when I have some free time.*

    0 => Array
    (
        [name] => Iberostar Las Letras Gran Via
        [checkout-price] => unknown *currently broken*
        [checkout-currency] => unknown *currently broken*
        [booking-url] => /travelads/trackredirect.html?trackingUrl=H4sIAAAAAAAAAD2SWa-qWBSE_815oT1sZriJ6Sgik8goiG-bDQIyz8Ovb3Mf-qmSr7IqVcnKxrEd_uD42MM5KWE8_GblkPRz8ouaCvf-0lM8uF-UowSfKVxpxqQc_rfEMkfFv99zVOR1eoEjPIpVe-iKU7pWmhlWZBT4w0A3n5dgOJ1YZB7NiswD9fl5vIpTpIt1IysGB0QlzXnDWu6UoD-7XSTovu-aji5Kvcy5NLKlmV3cQYsJNJCsR8zpZ5qqu5UZl9ZMsjpN8bx205lRzMECerAyvhWw3Vhpzfmem4Nq6DRBO9E9gsbs8nAdddMOWfFSQIFcvdaVoDxML2BfTkF3ZdW-kPdBzCRQ6g__W6E9eYQPhDP3DbLia6NeFmGwXpoOeCYUOOGz-s2dUiEZnww-xzZIYaptuTJR9Nh2f412jyUvacmAogu4irdiVZlrcG29G8Ru1RbN7nNyrkQ2Q_O1At1gjSewBtI6x09yBLP8KF1ZeL1RRyWBWO9D7QzPWKxl4LuSUzo2akJF1_TKJF020HF59_Htk3k8fyUufNOxrn5lDdFuXzcs2DeMNLWPfSpuD_W2hy9zlNSzW_f8PqI73srRqk1Yen-P4_Ves9HoPM7YpLu5ne13qe6SZqRPKJnnTO3xi2wvygmXOUvqQufSPfH5SaP9lbtW-ik2T9R18FDwlS5CrFEDsdLIZ9M0oIkQiRuSxPVouRV4LC0a8poyWR3xNt21R2EotvbYnmbYIv32tvPwkRdMuTU2adHTexUyBmAAozJ1j4npYYvWMp6J03RbzjvvruL4UAeanwSBg9rY3uAV-nfE11l_LTpnOjGejVNhv9u8bBhaad6c6OJSOXVexq2l1gfjKuzJmTUVs-XWJ254301rE20X74WPmDNI73bT49GxIj-siwrSg0dXfmyCs2oFiYFtfcN09rJ0YS5feXmgObQ5VJDFGqWbzGvfcqa8YZkZ7LafAn8NeckUolQUSE-iBOW2vc8QT4gHh8GGNtLu05GVH37_-ruv29sEZ1xZ9BATOCaLcX0LU9JZn2C9YP2CRyiILDA2H4jcE9PTsoRcctQT0iJjGqg0x66byIa2pqp5oWrUlXAlZVjoxWuUt_rdll4be9neXYEp6cB5tqAuy3aqK7PzvRrb7LJuiAvxpBuNm7R-8VEb4Onx-NPDujgSP2MyjH7SD3lTm3PS93mcHAmO437BPyQn_BUBCF-hACv8Ev9QHPWFPwjWcR7DMVEqecrjI8NDnoYIHPiYQgc6joUDFAjmEMcAxBFEEEX8T5RA1NTqMExJfCQBwR6AcCBoDzB_APjDkD_K0KNMvRyjCHFvgWIOJP_NoAHDHgRAvg8CKRAR-RYYKiH-Aw6tF3cRBQAA&targetUrl=H4sIAAAAAAAAAEWNsQ7CIBiE34YNC9SaLn9crJ2MQ020bggkkFKo8Pf9RRPT5Ya77-4qG9H4ShuUzuedxdkf31RZoyYaVwTB-IFyTvme_G0XNpeT-7WDr56gKUSKcaasgM7rZAIwssgSo3yBNlklt6CLgfxOqdMgRNPyditKvXrMIMjYn6F8Xvob1OQ5PMrSMHZQfwCUq-wisAAAAA..
        [price-pernight] => 149.48
        [image] => https://exp.cdn-hotels.com/hotels/2000000/1070000/1065700/1065617/5112310a_l.jpg
        [stars] => 4
    )
    1 => Array
    (
        [name] => Zenit Abeba
        [checkout-price] => unknown *currently broken*
        [checkout-currency] => unknown *currently broken*
        [booking-url] => /hotel/details.html?q-check-out=2016-11-14&q-check-in=2016-11-11&WOE=1&WOD=5&q-room-0-children=0&pa=2&tab=description&hotel-id=178443&q-room-0-adults=2&YGF=14&MGT=3&ZSX=0&SYE=3
        [price-pernight] => 65.67
        [image] => https://exp.cdn-hotels.com/hotels/1000000/200000/192400/192357/192357_71_l.jpg
        [stars] => 4
    ) ...

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
