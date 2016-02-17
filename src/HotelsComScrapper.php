<?php

namespace projectivemotion;

/**
 * Project: HotelsComScrapper
 *
 * @author Amado Martinez <amado@projectivemotion.com>
 */
class HotelsComScrapper extends BaseScrapper
{
    protected $domain       =   'www.hotels.com';
    public $curl_verbose    =   false;
    public $use_cache       =   1;

    protected $HotelFilter  =   '';

    public function hotelSetCurrency($currencyCode)
    {
        $response = $this->cache_get('http://www.hotels.com/change_currency.html?currency=' . $currencyCode);

        return $response;
    }

    public function processResults($response)
    {
        $data = array('response' => $response, 'hotel' => false, 'results' => $response->data->body->searchResults);
        if(isset($response->data->body->query->filters) && $response->data->body->query->filters->hotelId != '')
        {
            // found a hotel..
            $data['hotel']  =   $response->data->body->query->filters;  // save object for easy retrieval
        }

        return (object)$data;
    }

    public function doSearchInit($city_country, $checkin, $checkout, $currencyCode = 'EUR')
    {
        $this->hotelSetCurrency($currencyCode);
        $response   =   $this->submit_search($city_country, $checkin, $checkout);
        if(!$response)
            throw new Exception("FAiled to get json response.");

        return $this->processResults($response);
    }

    public function doSearch($data)
    {
        // go to next page
        $response = $this->cache_get('/search/listings.json' . $data->results->pagination->nextPageUrl . '&callback=dio.pages.sha.searchResultsCallback');
        $response = preg_replace('#^\s*dio.pages.sha.searchResultsCallback\(([\s\S]*?)\);\s*$#', '\1', $response);
        $json_result    =   json_decode($response);

        return $this->processResults($json_result);
    }

    public function hasMorePages($data)
    {
        return isset($data->results->pagination->nextPageUrl);
    }

    public function gotoNextPage($data)
    {
        return true;
    }

    public function submit_search($city, $checkin_YMD, $checkout_YMD)
    {
        $checkin_string = date("d/m/Y", strtotime($checkin_YMD));
        $checkout_string = date("d/m/Y", strtotime($checkout_YMD));

        $url = array (
            'scheme' => 'http',
            'host' => 'www.hotels.com',
//            'path' => '/search.do'
            'path' => '/search/listings.json'
        );
        $get = array (
            'q-destination' => $city,
            'as-shown' => 'true',
            'as-type' => 'CITY',
            'as-redirect-page' => 'DEFAULT_PAGE',
            'destination-id' => '', //'1634727',
            'as-srs-report' => '', //HomePage|AutoS|CITY|Abu|6|3|3|3|1|15|1634727',
            'q-localised-check-in' => $checkin_string,
            'q-localised-check-out' => $checkout_string,
            'q-rooms' => '1',
            'q-room-0-adults' => '2',
            'q-room-0-children' => '0',
            'page-name' => 'HomePage',
        );

        if($this->HotelFilter){
            $get['include-filters'] = 'true';
            $get['f-name']  =   $this->HotelFilter;
        }

        $url_string = sprintf("%s://%s%s%s", $url['scheme'], $url['host'], $url['path'], '?' . http_build_query($get));

        $response   =   $this->cache_get($url_string);
        return json_decode($response);
    }

    /**
     * Return some basic hotel info.
     *
     * @param $data
     * @return array
     */
    public function getHotels($data)
    {
        $hotels =   array();
        foreach($data->results->results as $hoteldata)
        {
            $hotels[$hoteldata->id] =   array('name'  => $hoteldata->name, 'image' => $hoteldata->thumbnailUrl, 'stars' => $hoteldata->starRating, 'price' => $hoteldata->ratePlan->price->exactCurrent);
        }
        return $hotels;
    }

    public function setHotelFilter($HotelFilter)
    {
        $this->HotelFilter = $HotelFilter;
    }

    public function getHotelFilter()
    {
        return $this->HotelFilter;
    }
}