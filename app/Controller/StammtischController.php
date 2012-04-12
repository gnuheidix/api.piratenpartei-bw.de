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
 * OSM-Location extractor
 *
 * This file renders OSM locations into a nice looking map
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
 * Stammtisch location extractor controller
 */
class StammtischController extends AppController{
    
    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Stammtisch';
    
    /**
     * Models in use
     * @var array
     */
    public $uses = array('Stammtisch');
    
    /**
     * Helpers in use
     * @var array
     */
    public $helpers = array('Html');
    
    // ############## PUBLICLY ACCESSIBLE METHODS ################
    /**
     * Displays a static manual page
     */
    public function index(){
        $this->layout = 'default-trans';
    }
    
    /**
     * Displays a static manual page
     */
    public function karte(){
        $this->layout = 'barebone';
        
        $this->set('min_zoom', 6);
        $this->set('max_zoom', 18);
        $this->set('default_zoom', 8);
        $this->set('pos_lat', 48.54);
        $this->set('pos_lon', 9.04);
        
        $this->Stammtisch->updateStammtische();
    }
}
