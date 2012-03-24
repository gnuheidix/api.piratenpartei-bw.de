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
    public $helpers = array('Html', 'Session');

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
    
    /**
     * The URL of the wiki. Just the name of the page has to be added
     * for proper retrival.
     */
    private $wikiBaseUrl = 'http://wiki.piratenpartei.de/wiki//index.php?action=render&title=';
    
    // ############## PUBLICLY ACCESSIBLE METHODS ################
    /**
     * Displays a static manual page
     */
    public function index() {
        // see /app/View/Wikis/index.ctp
    }
    
    /**
     * Delivers a page element extracted from another website.
     * The extracted content will be delivered without the bloaty
     * HTML stuff around it.
     */
    public function get(){
        $this->layout = 'ajax';
        $content = ':(';
        
        $params = $this->parseGetParams($this->params);
        if(!empty($params)){
            extract($params);
            
            // lookup the WikiPage or fetch it
            $this->WikiPage->recursive = -1;
            $wikipage = $this->WikiPage->findByTitle($title);
            if(empty($wikipage)){
                $wikipage = $this->updateWikiPage($title);
            }
            
            if(!empty($wikipage['WikiPage'])){
                $content = 'Die Wikiseite '.$title.' wurde gefunden.';
                
                // lookup the WikiElement or extract it
                $this->WikiElement->recursive = -1;
                $wikielement = $this->WikiElement->findByPageIdAndElementId($wikipage['WikiPage']['id'], $elementId);
                if(empty($wikielement)){
                    $wikielement = $this->updateWikiElement($wikipage, $elementId);
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
        
        $params = $this->parseGetParams($this->params);
        if(!empty($params)){
            extract($params);
        
            // lookup the WikiPage or fetch it
            $this->WikiPage->recursive = -1;
            $wikipage = $this->WikiPage->findByTitle($title);
            if(empty($wikipage)){
                $wikipage = $this->updateWikiPage($title);
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
    protected function parseGetParams($paramsObject){
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
     * TODO move to model
     * Updates the dataset of a certain WikiElement. If the dataset doesn't
     * exist, it will be deleted.
     * @param array $wikipage A WikiPage dataset the new WikiElement
     *     should be updated with.
     * @param string $elementId The HTML element id which should be stored
     *     within the WikiElement
     */
    protected function updateWikiElement($wikipage, $elementId){
        $pageId = $wikipage['WikiPage']['id'];
        $pageContent = $wikipage['WikiPage']['content'];
        $content = $this->extractElement($pageContent, $elementId);
        $retval = array();
        if(!empty($content)){
            $this->WikiElement->create();
            $data = $this->WikiElement->findByPageIdAndElementId($pageId, $elementId);
            $data['WikiElement']['page_id'] = $pageId;
            $data['WikiElement']['element_id'] = $elementId;
            $data['WikiElement']['content'] = $content;
            $this->WikiElement->save($data);
            $data['WikiElement']['id'] = $this->WikiElement->id;
            $retval = $data;
        }
        return $retval;
    }
    
    /**
     * TODO move to model
     * Updates the dataset of a certain WikiPage. If the dataset for the
     * specified page doesn't exist, he will be created.
     * @param string $title The title of the page to be updated.
     * @return The updated WikiPage dataset.
     */
    protected function updateWikiPage($title){
        // request page from wiki
        $content = @file_get_contents($this->wikiBaseUrl . $title);
        $retval = false;
        if($content !== FALSE){
            
            $content = str_replace( // TODO check if it's clever to do here
                'src="/wiki/images/'
                , 'src="http://wiki.piratenpartei.de/wiki/images/'
                , $content
            );
            
            // remove comments
            $content = preg_replace('/<!--(.*)-->/Uis', '', $content);
            
            // read, update, save
            $data = $this->WikiPage->findByTitle($title);
            $data['WikiPage']['title'] = $title;
            $data['WikiPage']['content'] = $content;
            $updateAgainAt = time() + 3600; // now + 1h
            $data['WikiPage']['updated'] = date('Y-m-d H:i:s',$updateAgainAt );
            $this->WikiPage->save($data);
            $data['WikiPage']['id'] = $this->WikiPage->id;
            
            $retval = $data;
        }
        return $retval;
    }
    
    /**
     * TODO move to libs
     * Extracts an element from HTML which holds a specific element id.
     * @param string $html The utf8-encoded HTML string to parse.
     * @param string $id The element id of the element to extract.
     * @return string The extracted element as HTML or an empty string in case
     *     the element was not found.
     */
    protected function extractElement($html, $id){
        $retval = '';
        
        // Load into DOMDocument and extract the element
        $dom = new DOMDocument('1.0', 'utf-8');
        $dom->loadHTML(utf8_decode($html));
        $xpath = new DOMXPath($dom);
        $content = $xpath->query("//*[@id='$id']")->item(0);
        if(isset($content)){
            // TODO update image urls
            
            // copy the content over to an empty DOM
            $children = $content->childNodes;
            foreach($children as $child){
                $document = new DOMDocument('1.0', 'utf-8');
                $document->appendChild($document->importNode($child,true));
                $retval .= $document->saveHTML();
            }
        }
        
        $retval = str_replace('<br>', '<br/>', $retval);
        $retval = trim ($retval);
        return $retval;
    }
}
