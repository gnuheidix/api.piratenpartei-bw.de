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
 * Replaces images in a WikiPage with locally cached copies of them.
 */
class WikiImage extends AppModel {
    var $name = 'WikiImage';
    
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
     * Finds all embedded images within the content (HTML) field, caches the
     * images locally and modifies the image locations properly.
     * The WikiPage dataset gets updated in the model.
     * @param array $data A WikiPage dataset being ready
     * @return The updated WikiPage dataset with replaced images where possible.
     */
    public function replaceImages($data){
        // init DOMDocument
        $dom = new DOMDocument('1.0', 'utf-8');
        $dom->loadHTML(utf8_decode($data['WikiPage']['content']));
        $xpath = new DOMXPath($dom);
        
        // Drop all WikiImages for the WikiPage whose content shall be replaced.
        $this->deleteWithPage($data['WikiPage']['id']);
        
        // iterate over all embedded images
        $images = $xpath->query('//img');
        foreach($images as $image){
            // extract information for WikiImage model
            $dataset = array();
            $dataset['WikiImage']['source_url'] = $image->getAttribute("src");
            $imageExtension = pathinfo($dataset['WikiImage']['source_url'], PATHINFO_EXTENSION);
            $dataset['WikiImage']['image_file'] = String::uuid().'.'.$imageExtension;
            $dataset['WikiImage']['page_id'] = $data['WikiPage']['id'];
            
            // save to WikiImage and replace content in DOM
            $this->create();
            if($this->save($dataset)){
                $imageDir = Configure::read('WikiImage.basepath');
                $image->setAttribute("src", $imageDir.$dataset['WikiImage']['image_file']);
            }
        }
        
        // write modified DOM to new DOMDocument
        $children = $dom->documentElement->firstChild->childNodes;
        $newDoc = new DOMDocument('1.0', 'utf-8');
        foreach($children as $child){
            $newDoc->appendChild($newDoc->importNode($child, true));
        }
        $newContent = $newDoc->saveHTML();
        $newContent = str_replace('<br>', '<br/>', $newContent);
        $newContent = trim($newContent);
        // write replaced content to WikiPage model
        
        if(!empty($newContent)){
            $wikiPageObj = ClassRegistry::init('WikiPage');
            $wikiPageObj->id = $data['WikiPage']['id'];
            if($wikiPageObj->saveField('content', $newContent)){
                $data['WikiPage']['content'] = $newContent;
            }
        }
        
        return $data;
    }
    
    /**
     * Each save downloads the dependent file into local filesystem.
     * @see Model::save()
     */
    public function save($data = null, $validate = true, $fieldList = array()){
        $retval = false;
        
        $imageFile =
            APP
            .WEBROOT_DIR
            .Configure::read('WikiImage.basepath')
            .$data['WikiImage']['image_file']
        ;
        
        $retval = parent::save($data, $validate, $fieldList)
            && copy($data['WikiImage']['source_url'], $imageFile, $this->streamContext)
        ;
        
        return $retval;
    }
    
    /**
     * Deletes all dependent WikiImages of a WikiPage.
     */
    protected function deleteWithPage($pageID){
        // iterate over all WikiImages of a certain WikiPage
        $wikiImages = $this->findAllByPageId($pageID);
        foreach($wikiImages as $wikiImage){
            if(!empty($wikiImage['WikiImage']['image_file'])){
                $imageFile =
                    APP
                    .WEBROOT_DIR
                    .Configure::read('WikiImage.basepath')
                    .$wikiImage['WikiImage']['image_file']
                ;
                
                // delete and unlink
                if(is_file($imageFile)){
                    unlink($imageFile);
                }
                parent::delete($wikiImage['WikiImage']['id']);
            }
        }
    }
}
?>
