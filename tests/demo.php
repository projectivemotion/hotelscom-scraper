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

$HotelsCom  =   new \projectivemotion\HotelsComScrapper();
$HotelsCom->curl_verbose    =   false;
$HotelsCom->use_cache   =   ($argc > 1 && $argv[1] == '1');
//$HotelsCom->setHotelFilter('Emporio');
$result = false;

$stdout = fopen('php://stdout', 'w+');
do{
    // initialize
    if($result == false)
    {
        $result = $HotelsCom->doSearchInit('Cancun, Mexico','2016-03-10','2016-03-14');

        if($result->hotel)
        {
            echo "Found Hotel: ", print_r($result->hotel), "\n";
            $hotels = $HotelsCom->getHotels($result);

            print_r($hotels[$result->hotel->hotelId]);
            break;
        }
    }else{
        $result = $HotelsCom->doSearch($result);
    }

    if(false === $result) break;

    $hotels =   array();
    $parse_result   =   $HotelsCom->getHotels($result, function ($hoteldata, $pagenumber) use (&$hotels, $HotelsCom, $stdout){
        $total_payment  =   $HotelsCom->getHotelBookingPrice($hoteldata);
        $hotelinfo  =   array('name'  => $hoteldata->name,
            'image' => $hoteldata->thumbnailUrl, 'stars' => $hoteldata->starRating,
            'price-pernight' => $hoteldata->ratePlan->price->exactCurrent,
            'booking-url' => $hoteldata->urls->pdpDescription,
            'checkout-price' => $total_payment[0],
            'checkout-currency' =>  $total_payment[1]);
        fputcsv($stdout, $hotelinfo);
        if($pagenumber > 1)
            return false;
        return true;
    });

    if(false === $parse_result)
        break;

}while($HotelsCom->hasMorePages($result) && $HotelsCom->gotoNextPage($result));

fclose($stdout);
echo 'done';

