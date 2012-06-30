<?php //CPYRGHT
/**
 * api.piratenpartei-bw.de
 * 
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @author  Thomas Heidrich
 * @copyright Copyright (c) 2012 Thomas Heidrich and other authors
 */
?><?php
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
 * Does basic installation
 */
class InstallController extends AppController{
    
    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Install';
    
    /**
     * Helpers in use
     * @var array
     */
    public $helpers = array();
    
    /**
     * Models in use
     * @var array
     */
    public $uses = array();
    
    /**
     * Trigger installation
     */
    public function index(){
        if(Configure::read('System.enableinstall')){
            $filename = APP.'dump.sql';
            ClassRegistry::init('ConnectionManager');
            $db = ConnectionManager::getDataSource('default');
            
            $filecontent = file_get_contents($filename);
            if($filecontent){
                $data = $db->query($filecontent);
                $this->Session->setFlash("Installation durchgeführt - Ergebnis: ".$data);
            }else{
                $this->Session->setFlash("Datei ".$filename." nicht gefunden.");
            }
        }else{
            $this->Session->setFlash("Die Installationsfunktion ist zur Zeit deaktiviert.");
        }
    }
}
?>
