<?php //CPYRGHT
/**
 * api.piratenpartei-bw.de
 * 
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @author  Thomas Heidrich, Adrian Kummerländer
 * @copyright Copyright (c) 2012 Thomas Heidrich and other authors
 */
?><?php

/**
 * Resolves adresses into geographical coordinates using a
 * nominatim instance developed by OpenStreetMap project.
 */
class GeoCoordinate extends AppModel{
    public $name = 'GeoCoordinate';
    
    /**
     * Retrieves the (cached) geo coordinates of a geocoded address.
     * 
     * Addresses which geo coordinates can't be found, will be cached
     * either to prevent searching for them again and again.
     * @param string $street
     * @param string $postcode
     * @param string $town
     */
    public function getCoordinates($street, $postcode, $town){
        $dataset = $this->findByStreetAndPostcodeAndTown($street, $postcode, $town);
        if(empty($dataset)){
            $dataset = array(
                'GeoCoordinate' => array(
                    'street' => $street
                    ,'postcode' => $postcode
                    ,'town' => $town
                    ,'lat' => null
                    ,'lon' => null
                )
            );
            $geocodes = $this->geocodeAdress($street, $postcode, $town);
            if(!empty($geocodes)){
                $dataset['GeoCoordinate']['lat'] = $geocodes['lat'];
                $dataset['GeoCoordinate']['lon'] = $geocodes['lon'];
            }
            $dataset = $this->create($dataset);
            if($this->save($dataset)){
                $dataset['GeoCoordinate']['id'] = $this->id;
            }else{
                // if we can't cache the result, we don't serve it
                $dataset = array();
            }
        }
        
        if(empty($dataset['GeoCoordinate']['lat'])
            && empty($dataset['GeoCoordinate']['lon'])
        ){
            // drop the dataset, if we have no result
            $dataset = array();
        }
        return $dataset;
    }
    
    /**
     * Retrieves geo coordinates from OpenStreetMap for a certain address.
     * @param string $street The street of the address to geocode.
     * @param string $postcode The postcode of the address to geocode.
     * @param string $town The town name of the address to geocode.
     * @return array with keys (lat, lon) or an empty array if the went sth. wrong
     */
    protected function geocodeAdress($street, $postcode, $town){
        $retval = array();
        $requestPath = Configure::read('GeoCoordinate.geolocationNominatimInstanceUrl')
            .'?format=json'
            .'&countrycodes=de'
            .'&limit=1'
            .'&addressdetails=0'
            .'&email='.urlencode(Configure::read('GeoCoordinate.geolocationContactAddress'))
            .'&q='.urlencode($street.', '.$postcode.', '.$town)
        ;
        $result = file_get_contents(
            $requestPath
            ,false
            ,$this->streamContext
        );
        if($result){
            $decodedResult = json_decode($result, true);
            if(!empty($decodedResult[0]['lat'])
                && !empty($decodedResult[0]['lon'])
                && Validation::numeric($decodedResult[0]['lat'])
                && Validation::numeric($decodedResult[0]['lon'])
            ){
                $retval['lat'] = $decodedResult[0]['lat'];
                $retval['lon'] = $decodedResult[0]['lon'];
            }
        }
        return $retval;
    }
}
?>
