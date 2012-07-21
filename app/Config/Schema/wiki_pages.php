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
 * cake schema create WikiPages
 *
 */
class WikiPagesSchema extends CakeSchema {
    
    public $name = 'WikiPages';
    
    public function before($event = array()) {
        return true;
    }
    
    public function after($event = array()) {
    }
    
    public $wiki_pages = array(
            'id' => array('type'=>'integer', 'null' => false, 'length' => 10, 'key' => 'primary'),
            'title' => array('type'=>'string', 'null' => false, 'length' => 255, 'key' => 'index'),
            'content' => array('type'=>'text', 'null' => true, 'default' => NULL),
            'requested' => array('type'=>'datetime', 'null' => false),
            'updatedat' => array('type'=>'datetime', 'null' => false),
            'created' => array('type'=>'datetime', 'null' => false),
            'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'title' => array('column' => 'title', 'unique' => 1))
    );
}
