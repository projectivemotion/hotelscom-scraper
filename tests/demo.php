<?php
/**
 * Project: HotelsComScrapper
 *
 * @author Amado Martinez <amado@projectivemotion.com>
 */
// Used for testing. Run from command line.
if(!isset($argv))
    die("Run from command line.");

if ($argc < 5)
{
    printf(
<<<'DOC'
Usage:
    %s [use-cache] [location] [checkin-date] [checkout-date]
    
Arguments:
    use-cache:      0 or 1
    location:       Cancun, Mexico
    checkin-date:   2016-11-16
    checkout-date:  2016-11-19
    
Examples:
    $ php -f %s 1 "Cancun, Mexico" 2016-11-16 2016-11-19
    $ php -f %s 1 "Madrid, Spain" $(date +%%Y-%%m-%%d) $(date -d '5 days' +%%Y-%%m-%%d)

DOC
    , $argv[0], $argv[0]
    , $argv[0]
);
    exit;
}
// copied this from doctrine's bin/doctrine.php
$autoload_files = array( __DIR__ . '/../vendor/autoload.php',
    __DIR__ . '/../../../autoload.php');

foreach($autoload_files as $autoload_file)
{
    if(!file_exists($autoload_file)) continue;
    require_once $autoload_file;
}
// end autoloader finder

$cache_on   =   $argv[1] == '1';
$city_txt   =   $argv[2];
$checkin    =   $argv[3];
$checkout   =   $argv[4];

printf(<<<HDOC
Using Params:

    cache:      $cache_on
    city:       $city_txt
    checkin:    $checkin
    checkout:   $checkout
    
HDOC
);

$demo_hotels =   5;

$HotelsCom  =   new \projectivemotion\HotelsComScraper\Scraper();
$HotelsCom->verboseOff();

if($cache_on)
    $HotelsCom->cacheOn();
else
    $HotelsCom->cacheOff();

//$HotelsCom->setHotelFilter('Hotel Félicien by Elegancia');
$result = false;

$cur_hotelnumber    =   0;
do{
    // initialize
    if($result == false)
    {
        $result = $HotelsCom->doSearchInit($city_txt, $checkin, $checkout);

        if($result->hotel)
        {
            echo "Found Hotel: ", print_r($result->hotel), "\n";
            $hotels = $HotelsCom->getHotels($result, null);

            foreach($hotels as $hotelinfo)
            {
                if($hotelinfo->id == $result->hotel->hotelId)
                {
                    print_r($hotelinfo);
                    break;
                }
            }
        }
    }else{
        $result = $HotelsCom->doSearch($result);
    }

    if(false === $result) break;

    $hotels =   array();
    $parse_result   =   $HotelsCom->getHotels($result);

    foreach($parse_result as $i => $hoteldata)
    {
        echo "$i => ";
            $total_payment  =   $HotelsCom->getHotelBookingPrice($hoteldata);
            $hotelinfo  =   array(
                'name'              => $hoteldata->name,
                'checkout-price'    => $total_payment[0],
                'checkout-currency' =>  $total_payment[1],
                'booking-url'       => $hoteldata->urls->pdpDescription,
                'price-pernight'    => $hoteldata->ratePlan->price->exactCurrent,
                'image'             => $hoteldata->thumbnailUrl, 'stars' => $hoteldata->starRating
            );
            print_r($hotelinfo);
//            if($result->results->pagination->currentPage > $demo_hotels)
            if(++$cur_hotelnumber > $demo_hotels)
            {
                $parse_result = false;  // break out
                break;
            }
    }

    if(false === $parse_result)
        break;

}while($HotelsCom->hasMorePages($result) && $HotelsCom->gotoNextPage($result));

