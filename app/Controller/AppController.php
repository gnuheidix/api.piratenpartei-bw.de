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
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
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

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller{
    
    /**
     * (non-PHPdoc)
     * @see Controller::beforeRender()
     */
    public function beforeRender(){
        // set the base url used in the layout
        $baseUrl = Configure::read('System.baseurl');
        $this->set('baseurl', $baseUrl);
    }
    
    // ############## CONVENIENCE METHODS ################
    /**
     * Parses a client request and extracts title and element-id.
     * http://url.tld/CONTROLLER/ACTION/TITLE_WITH_SLASHES/ELEMENT_ID
     * @param Object $paramsObject The object "params" of the client request.
     *     (usually $this->params)
     * @return array The extracted title and elementId or false if something
     *     bad happened.
     */
    protected function parseGetParamsWithId($paramsObject){
        $retval = false;
        
        if(!empty($paramsObject)){
            // extract title and id of the requested wiki page
            $replaceUrl = $paramsObject->params['controller']
                .'/'
                .$paramsObject->params['action']
                .'/'
            ;
            
            $url = substr($this->params->url, strlen($replaceUrl));
            $dividerPos = strrpos($url, '/');
            
            $title = substr($url, 0, $dividerPos);
            $elementId = substr($url, $dividerPos + 1);
            
            if(!empty($title)
                && !empty($elementId)){
                
                $retval = compact(
                    $title
                    , $elementId
                    , array('title', 'elementId')
                );
            }
        }
        
        return $retval;
    }
    
    /**
     * Parses a client request and extracts title
     * http://url.tld/CONTROLLER/ACTION/TITLE_WITH_SLASHES
     * @param Object $paramsObject The object "params" of the client request.
     *     (usually $this->params)
     * @return string The extracted page title and false if sth. bad happened.
     */
    protected function parseGetParams($paramsObject){
        $retval = false;
        
        if(!empty($paramsObject)){
            // extract title and id of the requested wiki page
            $replaceUrl = $paramsObject->params['controller']
                .'/'
                .$paramsObject->params['action']
                .'/'
            ;
            $url = substr($this->params->url, strlen($replaceUrl));
            
            if(!empty($url)){
                $retval = $url;
            }
        }
        
        return $retval;
    }
}
