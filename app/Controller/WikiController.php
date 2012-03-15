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
	public $uses = array();

/**
 * Displays a static manual page
 */
	public function index() {
		// see /app/View/Wikis/index.ctp
	}

/**
 * Delivers content fetched from other websites
 */
    public function get(){
        $this->layout = 'ajax';
        
        //
        if(!empty($this->params->pass)
            && count($this->params->pass) > 1){
            
            $url = substr($this->params->url, strlen('wiki/get/'));
            $idDivider = strrpos($url, '/');
            $name = substr($url, 0, $idDivider);
            $id = substr($url, $idDivider + 1);
            
            pr($name);
            pr($id);
        }else{
           // $this->redirect('index');
        }
    }
}
