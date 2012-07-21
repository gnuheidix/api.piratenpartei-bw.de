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
 * cake schema create WikiImages
 *
 */
class WikiImagesSchema extends CakeSchema {

    public $name = 'WikiImages';

    public function before($event = array()) {
        return true;
    }

    public function after($event = array()) {
    }

    public $wiki_images = array(
            'id' => array('type'=>'integer', 'null' => false, 'length' => 10, 'key' => 'primary'),
            'source_url' => array('type'=>'string', 'null' => false, 'length' => 511, 'key' => 'index'),
            'image_file' => array('type'=>'string', 'null' => false, 'length' => 255, 'key' => 'index'),
            'page_id' => array('type'=>'integer', 'null' => false, 'length' => 10, 'key' => 'index'),
            'created' => array('type'=>'datetime', 'null' => false),
            'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'source_url' => array('column' => 'source_url'), 'image_file' => array('column' => 'image_file'), 'page_id' => array('column' => 'page_id'))
    );
}
?>