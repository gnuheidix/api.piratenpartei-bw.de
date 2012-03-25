<?php
class WikiElement extends AppModel {
    public $name = 'WikiElement';
    
    public $belongsTo = array(
        'WikiPage' => array(
            'className'    => 'WikiPage',
            'foreignKey'   => 'page_id'
        )
    );
    
    // TODO validation needed
    
    /**
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
?>
