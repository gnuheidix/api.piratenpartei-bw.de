<?php
/**
 * Website content caching
 *
 * This file [reads, caches, delivers] content from other websites.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('AppController', 'Controller');

/**
 * Website content caching controller
 */
class WikiController extends AppController {

/**
 * Controller name
 *
 * @var string
 */
	public $name = 'Wiki';

/**
 * Default helper
 *
 * @var array
 */
	public $helpers = array('Html', 'Session');

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array('WikiPage');

/**
 * Determines the divider within the request URLs between
 * wiki url and div id.
 */
    private $idDivider = '/';

/**
 * The URL of the wiki. Just the name of the page has to be added
 * for proper retrival.
 */
    private $wikiBaseUrl = 'http://wiki.piratenpartei.de/wiki//index.php?action=render&title=';

/**
 * Displays a static manual page
 */
	public function index() {
		// see /app/View/Wikis/index.ctp
	}

/**
 * Delivers content fetched from other websites
 * TODO div-id parsing
 */
    public function get(){
        $this->layout = 'ajax';
        $content = 'Die Seite konnte nicht abgerufen werden.';
        
        if(!empty($this->params->pass)
            && count($this->params->pass) > 1){
            // extract title and id of the requested wiki page
            $url = substr($this->params->url, strlen('wiki/get/'));
            $dividerPos = strrpos($url, $this->idDivider);
            $title = substr($url, 0, $dividerPos);
            $id = substr($url, $dividerPos + 1);
            
            // fetch content from database or retrieve it from wiki
            $dataset = $this->WikiPage->findByTitle($title);
            if(empty($dataset)){
                $content = $this->updateDataset($title);
            }else{
                $content = $dataset['WikiPage']['content'];
            }
        }else{
            $this->redirect('index');
        }
        $this->set('content', $content);
    }
    
    /**
     * Updates the dataset of a certain wiki page. If the dataset for the
     * specified page doesn't exist, he will be created.
     * @param string title The title of the page to be updated.
     * @return The newly retrieved content or false if something bad happened.
     */
    protected function updateDataset($title){
        // request page from wiki
        $content = file_get_contents($this->wikiBaseUrl . $title);
        $retval = false;
        if($content !== FALSE){
            // repair image urls
            // TODO implement image caching
            $content = str_replace('src="/wiki/images/'
                , 'src="http://wiki.piratenpartei.de/wiki/images/'
                , $content
            );
            
            // read, update, save
            $data = $this->WikiPage->findByTitle($title);
            $data['WikiPage']['title'] = $title;
            $data['WikiPage']['content'] = $content;
            // TODO set time of updated + 1h
            
            // TODO error checking for save
            $this->WikiPage->save($data);
            $retval = $content;
        }
        return $retval;
    }
}
