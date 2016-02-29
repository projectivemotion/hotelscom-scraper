<?php
/**
 * Project: HotelsComScrapper
 *
 * @author Amado Martinez <amado@projectivemotion.com>
 */
// Used for testing. Run from command line.
if(!isset($argv))
    die("Run from command line.");

// copied this from doctrine's bin/doctrine.php
$autoload_files = array( __DIR__ . '/../vendor/autoload.php',
    __DIR__ . '/../../../autoload.php');

foreach($autoload_files as $autoload_file)
{
    if(!file_exists($autoload_file)) continue;
    require_once $autoload_file;
}
// end autoloader finder

$demo_pages =   1;

$HotelsCom  =   new \projectivemotion\HotelsComScraper\Scraper();
$HotelsCom->verboseOff();

if($argc > 1 && $argv[1] == '1')
    $HotelsCom->cacheOn();
else
    $HotelsCom->cacheOff();

//$HotelsCom->setHotelFilter('Hotel Félicien by Elegancia');
$result = false;

do{
    // initialize
    if($result == false)
    {
        $result = $HotelsCom->doSearchInit('Paris, France','2016-04-15','2016-04-19');

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
            if($result->results->pagination->currentPage > $demo_pages)
            {
                $parse_result = false;  // break out
                break;
            }
    }

    if(false === $parse_result)
        break;

}while($HotelsCom->hasMorePages($result) && $HotelsCom->gotoNextPage($result));

echo 'done';

