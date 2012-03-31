<?php
App::import('Model', 'WikiPage');
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
        // init Wikiimage model and DOMDocument
        $wikiImageObj = new WikiImage();
        $dom = new DOMDocument();
        $dom->loadHTML($data['WikiPage']['content']);
        $xpath = new DOMXPath($dom);
        
        $this->deleteAll(array('page_id' => $data['WikiPage']['id']));
        
        // iterate over all images
        $images = $xpath->query('//img');
        foreach($images as $image){
            // extract information for WikiImage model
            $dataset = array();
            $dataset['WikiImage']['source_url'] = $image->getAttribute("src");
            $imageExtension = pathinfo($dataset['WikiImage']['source_url'], PATHINFO_EXTENSION);
            $dataset['WikiImage']['image_file'] = String::uuid().'.'.$imageExtension;
            $dataset['WikiImage']['page_id'] = $data['WikiPage']['id'];
            
            // save to WikiImage
            $this->create();
            if($this->save($dataset)){
                $imageDir = Configure::read('WikiImage.basepath');
                $image->setAttribute("src", $imageDir.$dataset['WikiImage']['image_file']);
            }
        }
        
        // save to WikiPage
        pr($dom->saveHTML());
        exit;
    }
    
    /**
     * Each save saves the dependent file into local filesystem.
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
            && copy($data['WikiImage']['source_url'], $imageFile)
        ;
        
        return $retval;
    }
    
    /**
     * Each delete deletes the dependent file either.
     * @see Model::delete()
     */
    public function delete($id = null, $cascade = true){
        $retval = false;
        $wikiImage = $this->findById($id);
        if(!empty($wikiImage['WikiImage']['image_file'])){
            $imageFile =
                APP
                .WEBROOT_DIR
                .Configure::read('WikiImage.basepath')
                .$wikiImage['image_file']
            ;
            $retval = parent::delete($id) && unlink($imageFile);
        }
        return $retval;
    }
}
?>