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
class WikiDivsSchema extends CakeSchema {

	public $name = 'WikiDivs';

	public function before($event = array()) {
		return true;
	}

	public function after($event = array()) {
	}

	public $wiki_divs = array(
			'id' => array('type'=>'integer', 'null' => false, 'length' => 10, 'key' => 'primary'),
			'page_id' => array('type'=>'integer', 'null' => false, 'length' => 10, 'key' => 'index'),
			'content' => array('type'=>'text', 'null' => true, 'default' => NULL),
			'requested' => array('type'=>'datetime', 'null' => false),
			'created' => array('type'=>'datetime', 'null' => false),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'page_id' => array('column' => 'page_id'))
		);

}
