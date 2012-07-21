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
 * This is i18n Schema file
 *
 * Use it to configure database
 *
 */

/*
 *
 * Using the Schema command line utility
 * cake schema create GeoCoordinates
 *
 */
class GeoCoordinatesSchema extends CakeSchema {

    public $name = 'GeoCoordinates';

    public function before($event = array()) {
        return true;
    }

    public function after($event = array()) {
    }

    public $geo_coordinates = array(
            'id' => array('type'=>'integer', 'null' => false, 'length' => 10, 'key' => 'primary'),
            'street' => array('type'=>'string', 'null' => true, 'length' => 256, 'key' => 'index'),
            'postcode' => array('type'=>'string', 'null' => true, 'length' => 16, 'key' => 'index'),
            'town' => array('type'=>'string', 'null' => true, 'length' => 256, 'key' => 'index'),
            'lat' => array('type'=>'float', 'null' => true, 'length' => '9,6'),
            'lon' => array('type'=>'float', 'null' => true, 'length' => '9,6'),
            'created' => array('type'=>'datetime', 'null' => false),
            'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'street' => array('column' => 'street'), 'postcode' => array('column' => 'postcode'), 'town' => array('column' => 'town'))
    );
}
