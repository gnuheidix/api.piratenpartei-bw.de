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
class WikiController extends AppController{
    
    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Wiki';
    
    /**
     * Helpers in use
     * @var array
     */
    public $helpers = array('Html');
    
    /**
     * Models in use
     * @var array
     */
    public $uses = array('WikiPage', 'WikiElement');
    
    /**
     * Determines the divider within the request URLs between
     * wiki url and div id.
     */
    private $idDivider = '/';
    
    
    
    // ############## PUBLICLY ACCESSIBLE METHODS ################
    /**
     * Displays a static manual page
     */
    public function index() {
        // see /app/View/Wiki/index.ctp
    }
    
    /**
     * Delivers a page element extracted from another website.
     * The extracted content will be delivered without the bloaty
     * HTML stuff around it.
     */
    public function get(){
        $this->layout = 'ajax';
        $content = ':(';
        
        $params = $this->parseGetParamsWithId($this->params);
        if(!empty($params)){
            extract($params);
            
            // lookup the WikiPage or fetch it
            $this->WikiPage->recursive = -1;
            $wikipage = $this->WikiPage->findByTitle($title);
            if(empty($wikipage)){
                $wikipage = $this->WikiPage->updateWikiPage($title);
            }
            
            if(!empty($wikipage['WikiPage'])){
                $content = 'Die Wikiseite '.$title.' wurde gefunden.';
                
                // lookup the WikiElement or extract it
                $this->WikiElement->recursive = -1;
                $wikielement = $this->WikiElement->findByPageIdAndElementId($wikipage['WikiPage']['id'], $elementId);
                if(empty($wikielement)){
                    $wikielement = $this->WikiElement->updateWikiElement($wikipage, $elementId);
                }
                
                // prepare the WikiElement for being delivered 
                // and update the access time
                if(!empty($wikielement['WikiElement'])){
                    $content = $wikielement['WikiElement']['content'];
                    $this->WikiElement->id = $wikielement['WikiElement']['id'];
                    $this->WikiElement->saveField(
                        'requested'
                        , date('Y-m-d H:i:s', time())
                    );
                }else{
                    $content .= ' Das Element mit der ID "'
                        .$elementId
                        .'" wurde innerhalb der Wikiseite nicht gefunden.'
                    ;
                }
            }else{
                $content = 'Die Wikiseite konnte nicht vom Wiki abgerufen'
                    .' werden und befindet sich nicht (mehr) im Speicher. :(';
            }
        }else{
            $content = 'Der Aufruf schlug aufgrund fehlerhafter Eingaben fehl.';
        }
        $this->set('content', $content);
    }
    
    /**
     * Delivers a page from another website without the bloaty
     * HTML stuff around it.
     */
    public function getpage(){
        $this->layout = 'ajax';
        $this->view = 'get';
        $content = ':(';
        
        $title = $this->parseGetParams($this->params);
        if(!empty($title)){
            
            // lookup the WikiPage or fetch it
            $this->WikiPage->recursive = -1;
            $wikipage = $this->WikiPage->findByTitle($title);
            if(empty($wikipage)){
                $wikipage = $this->WikiPage->updateWikiPage($title);
            }
            
            if(empty($wikipage['WikiPage'])){
                $content = 'Die Wikiseite '.$title.' wurde nicht gefunden. :(';
            }else{
                $content = $wikipage['WikiPage']['content'];
            }
        }else{
            $content = 'Der Aufruf schlug aufgrund fehlerhafter Eingaben fehl.';
        }
        $this->set('content', $content);
    }
    
    public function getpagehtml(){
        $this->getpage();
        $this->layout = 'barebone';
        $this->view = 'get';
    }
    
    /**
     * Delivers a page element extracted from another website.
     * The extracted content will be delivered within a bare HTML
     * page.
     */
    public function gethtml(){
        $this->get();
        $this->layout = 'barebone';
        $this->view = 'get';
    }
    
    // ############## CONVENIENCE METHODS ################
    /**
     * Parses a client request and extracts title and element-id.
     * http://url.tld/CONTROLLER/ACTION/TITLE_WITH_SLASHES/ELEMENT_ID
     * @param Object $paramsObject The object "params" of the client request.
     *     (usually $this->params)
     * @return array The extracted title and elementId or false if something
     *     bad happended.
     */
    protected function parseGetParamsWithId($paramsObject){
        $retval = false;
        
        if(!empty($paramsObject)
            && count($paramsObject->params['pass']) > 1){
            // extract title and id of the requested wiki page
            $replaceUrl = $paramsObject->params['controller']
                .'/'
                .$paramsObject->params['action']
                .'/'
            ;
            
            $url = substr($this->params->url, strlen($replaceUrl));
            $dividerPos = strrpos($url, $this->idDivider);
            
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
     * @return title The extracted page title false if something bad happended.
     */
    protected function parseGetParams($paramsObject){
        $retval = false;
        
        if(!empty($paramsObject)
                && !empty($paramsObject->params['pass'])){
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
