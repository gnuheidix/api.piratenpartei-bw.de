<?php
/**
 * This is i18n Schema file
 *
 * Use it to configure database for WikiPage-Model
 *
 */

/*
 *
 * Using the Schema command line utility
 * cake schema create wiki_pages
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
            'updated' => array('type'=>'datetime', 'null' => false),
            'created' => array('type'=>'datetime', 'null' => false),
            'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'title' => array('column' => 'title', 'unique' => 1))
        );

}
