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
 * Extracts parts from a WikiPage and caches them.
 */
class WikiElement extends AppModel {
    public $name = 'WikiElement';
    
    /**
     * outgoing link to WikiPage
     * @var array
     */
    public $belongsTo = array(
        'WikiPage' => array(
            'className'    => 'WikiPage',
            'foreignKey'   => 'page_id'
        )
    );
    
    // TODO validation needed
    
    /**
     * Retrieves a certain WikiElement from model / external source
     * @param string $pageTitle The title of the wikipage where to extract the
     *     page element.
     * @param string $elementId The ID of the element to look for within
     *     the page.
     * @return array The retrieved WikiElement or false if sth. went wrong.
     */
    public function getElement($pageTitle, $elementId){
        $elementId = urldecode($elementId);
        $maxage = Configure::read('System.autoupdateage');
        $wikiPageObj = ClassRegistry::init('WikiPage');
        $wikiPage = $wikiPageObj->getPage($pageTitle);
        $wikiElement = false;
        
        if(!empty($wikiPage['WikiPage']['id'])){
            $pageId = $wikiPage['WikiPage']['id'];
            $wikiElement = $this->findByPageIdAndElementId($pageId, $elementId);
            
            if(   empty($wikiElement['WikiElement']['id'])
               || (time() - strtotime($wikiElement['WikiElement']['updatedat']) > $maxage)){
                $wikiElement = $this->updateWikiElement($wikiPage, $elementId);
            }else{
                // update access time
                $this->id = $wikiElement['WikiElement']['id'];
                $this->saveField('requested', date('Y-m-d H:i:s', time()));
            }
        }
        return $wikiElement;
    }
    
    /**
     * Updates the dataset of a certain WikiElement. If the dataset doesn't
     * exist, it will be deleted.
     * @param array $wikiPage A WikiPage dataset the new WikiElement
     *     should be updated with.
     * @param string $elementId The HTML element id which should be stored
     *     within the WikiElement
     * @return array The updated WikiElement dataset or false if sth. went wrong
     */
    public function updateWikiElement($wikiPage, $elementId){
        $pageId = $wikiPage['WikiPage']['id'];
        $pageContent = $wikiPage['WikiPage']['content'];
        $content = $this->extractElement($pageContent, $elementId);
        $retval = false;
        if(!empty($content)){
            $this->create();
            $data = $this->findByPageIdAndElementId($pageId, $elementId);
            $data['WikiElement']['page_id'] = $pageId;
            $data['WikiElement']['element_id'] = $elementId;
            $data['WikiElement']['content'] = $content;
            $data['WikiElement']['updatedat'] =  $wikiPage['WikiPage']['updatedat'];
            $data['WikiElement']['requested'] =  date('Y-m-d H:i:s', time());
            $this->save($data);
            $data['WikiElement']['id'] = $this->id;
            $retval = $data;
        }
        return $retval;
    }
    
    /**
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
        $retval = trim($retval);
        return $retval;
    }
}
?>
