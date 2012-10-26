<?php
require_once('BaseGeocoder.php');
/**
 * The geocoding functions take 1 or 2 arguments. The arument names are arg1 and arg2.
 * If 1 argument is given, it is assumed to be the address (or name of a place).
 * If 2 arguments are given, they are assumed to be the latitude and longitude.
 */ 
class GoogleGeocoder extends BaseGeocoder { 
    const BASE_ADDRESS = 'http://maps.google.com/maps/geo';

    const QUERY_KEY = 'q';
    const LAT_LON_SEPARATOR = ',';

    const OUTPUT_KEY = 'output';
    const OUTPUT_TYPE_JSON = 'json';    
    
    const ENCODING_KEY = 'oe';
    const ENCODING_TYPE_UTF8 = 'UTF-8'; 

    const APIKEY_KEY = 'key';
        
    private $key;

    public function __construct($key) {
        $this->key = $key;
    }

    public function make_url($arg1, $arg2=null, 
            $output=self::OUTPUT_TYPE_JSON, $encoding=self::ENCODING_TYPE_UTF8) {
        if (empty($arg2)) $query = urlencode($arg1);
        else $query = $arg1 . self::LAT_LON_SEPARATOR . $arg2;
        return self::BASE_ADDRESS
            .'?'. self::QUERY_KEY    .'='. $query
            .'&'. self::OUTPUT_KEY   .'='. $output
            .'&'. self::ENCODING_KEY .'='. $encoding
            .'&'. self::APIKEY_KEY   .'='. $this->key;
    }

    public function get_json($arg1, $arg2=null) {
        $url = $this->make_url($arg1, $arg2);
        return file_get_contents($url);
    }

    public function get_array($arg1, $arg2=null) {
        return json_decode($this->get_json($arg1, $arg2), true);
    }

    /**
     * JSON string -or- an Array decoded from a JSON string
     */
    public function is_valid($json) {
        if (!is_array($json)) $json = json_decode($json, true);
        return $json['Status']['code'] == '200';
    }

    /**
     * Cherry-picks useful data from a result and stores it in an
     * array using a flat structure and more 'normal' keys
     */
    public function get_info($arg1, $arg2=null) {       
        $array = $this->get_array($arg1, $arg2);

        // name, address, city, state zip, country
        $a['full_address'] = $array['Placemark'][0]['address'];

        // Accuracy in Meters
        $a['accuracy'] = $array['Placemark'][0]['AddressDetails']['Accuracy'];

        $temp = $array['Placemark'][0]['AddressDetails']['Country']['AdministrativeArea'];

        // *seems* to always return the two-letter state code
        $a['state'] = $temp['AdministrativeAreaName'];

        // if the search used a place name instead of an address
        $a['name'] = $temp['Locality']['AddressLine'][0];

        $a['city'] = $temp['Locality']['LocalityName'];
        $a['zip'] = $temp['Locality']['PostalCode']['PostalCodeNumber'];
        $a['address'] = $temp['Locality']['Thoroughfare']['ThoroughfareName'];
        
        $a['country'] = $array['Placemark'][0]['AddressDetails']['Country']['CountryName'];
        $a['country_code'] = $array['Placemark'][0]['AddressDetails']['Country']['CountryNameCode'];
        
        $a['latitude'] = $array['Placemark'][0]['Point']['coordinates'][1];
        $a['longitude'] = $array['Placemark'][0]['Point']['coordinates'][0];
        $a['elevation'] = $array['Placemark'][0]['Point']['coordinates'][2];

        return $a;
    }
}
