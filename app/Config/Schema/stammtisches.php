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
 * Use it to configure database
 */

/*
 *
 * Using the Schema command line utility
 * cake schema create Stammtischs
 *
 */
class StammtischesSchema extends CakeSchema {
    
    public $name = 'Stammtisches';
    
    public function before($event = array()) {
        return true;
    }
    
    public function after($event = array()) {
    }
    
    public $stammtisches = array(
            'id' => array('type'=>'integer', 'null' => false, 'length' => 10, 'key' => 'primary'),
            'date' => array('type'=>'datetime', 'null' => true),
            'data' => array('type'=>'text', 'null' => false),
            'created' => array('type'=>'datetime', 'null' => false),
            'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
    );
}
