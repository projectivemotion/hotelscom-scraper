<?php

namespace projectivemotion\HotelsComScraper;

use projectivemotion\PhpScraperTools\BaseScraper;

/**
 * Project: HotelsComScrapper
 *
 * @author Amado Martinez <amado@projectivemotion.com>
 */
class Scraper extends BaseScraper
{
    protected $domain       =   'www.hotels.com';
    protected $HotelFilter  =   '';

    public function hotelSetCurrency($currencyCode)
    {
        $response = $this->cache_get('http://www.hotels.com/change_currency.html?currency=' . $currencyCode);

        return $response;
    }

    public function processResults($response)
    {
        if(!isset($response->data->body->searchResults))
        {
            var_dump($response);
        }

        $data = array('response' => $response, 'hotel' => false, 'results' => $response->data->body->searchResults);
        if(isset($response->data->body->query->filters) && $response->data->body->query->filters->hotelId != '')
        {
            // found a hotel..
            $data['hotel']  =   $response->data->body->query->filters;  // save object for easy retrieval
        }

        return (object)$data;
    }

    /**
     * Call this to initialize the search.
     *
     * @param $city_country
     * @param $checkin
     * @param $checkout
     * @param string $currencyCode
     * @return object
     * @throws Exception
     */
    public function doSearchInit($city_country, $checkin, $checkout, $currencyCode = 'EUR')
    {
        $this->hotelSetCurrency($currencyCode);
        $response   =   $this->submit_search($city_country, $checkin, $checkout);
        if(!$response)
            throw new Exception("FAiled to get json response.");

        return $this->processResults($response);
    }

    /**
     * After calling doSearchInit, pass the result to this.
     * This function will take the nextPage url from the results object and fetch the next page.
     *
     * @param $data
     * @return object
     */
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

    public function logParsError($doc, $filename, $error)
    {
        file_put_contents($filename, $doc);
        throw new \Exception($error);
    }

    public function getHotelBookingPrice($hoteldata)
    {
        $url    =   $hoteldata->urls->pdpDescription;
        $page   =   $this->cache_get($url);

        $doc    =   \phpQuery::newDocument($page);

        $form   =   $doc['.rateplan:first form'];

        if($form->length < 1)
            $this->logParsError($page, 'hotelpage.html', "Unable to find submit form: $url");

        $form_action    =   $form->attr('action');
        $form_input     =   $form->find('input');

        $post_data  =   array();

        foreach($form_input as $input)
        {
            $qel    =   pq($input);
            $post_data[$qel->attr('name')]    =   $qel->val();
        }

        $checkout_page  =   $this->cache_get($form_action, $post_data);
        $m  =   preg_match('#"totalPaymentAmount":\s*"([^"]*?)"#', $checkout_page, $matches);

        if(!$m)
            $this->logParsError($checkout_page, 'checkout-page.html', 'Could not find Total Payment Amount.');

        list($amount, $currency)    =   explode(',', $matches[1]);

        return array($amount, $currency);
    }

    /**
     * Return some basic hotel info.
     *
     * @param $data
     * @return array
     */
    public function getHotels($data)
    {
        foreach($data->results->results as $hoteldata)
        {
            yield $hoteldata;
            continue;
        }
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