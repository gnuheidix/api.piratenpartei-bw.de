<?php //CPYRGHT
/**
 * api.piratenpartei-bw.de
 * 
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @author  Thomas Heidrich
 * @copyright Copyright (c) 2012 Thomas Heidrich and other authors
 */
?><?php
App::uses('GeoCoordinate', 'Model');

class GeoCoordinateTestCase extends CakeTestCase {
    public $fixtures = array(
        'app.geoCoordinate'
    );
    
    public function setup(){
        parent::setUp();
        $this->GeoCoordinate = ClassRegistry::init('GeoCoordinate');
    }
    
    /**
     * Tests if a the geocoordinates are getting fetched and cached correctly
     */
    public function testGeocoding(){
        $result = $this->GeoCoordinate->getCoordinates('Auerbacherstraße 1', '08485', 'Lengenfeld');
        $this->assertTrue(!empty($result));
        $this->assertTrue(empty($result['GeoCoordinate']['created']));
        $this->assertWithinMargin($result['GeoCoordinate']['lat'], 50.5, 1);
        $this->assertWithinMargin($result['GeoCoordinate']['lon'], 12.3, 1);
        
        $resultCached = $this->GeoCoordinate->getCoordinates('Auerbacherstraße 1', '08485', 'Lengenfeld');
        $this->assertFalse(empty($resultCached['GeoCoordinate']['created']));
        $this->assertWithinMargin($result['GeoCoordinate']['lat'], $resultCached['GeoCoordinate']['lat'], 0.000001);
        $this->assertWithinMargin($result['GeoCoordinate']['lon'], $resultCached['GeoCoordinate']['lon'], 0.000001);
    }
    
    /**
     * Tests if a the geocoordinates are getting fetched and cached correctly
     */
    public function testGeocodingFailure(){
        $result = $this->GeoCoordinate->getCoordinates('Straße 323/1', '8485', 'nicht existierende Stadt');
        $this->assertTrue(empty($result));
    }
}
?>