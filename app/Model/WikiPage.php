<?php //CPYRGHT
/**
 * api.piratenpartei-bw.de
 * 
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @author  Thomas Heidrich
 * @copyright Copyright (c) 2012 Thomas Heidrich and other authors
 */
?><?php
class WikiPage extends AppModel {
    public $name = 'WikiPage';
    
    /**
     * incoming link from WikiElement and WikiImage
     * @var array
     */
    public $hasMany = array(
        'WikiElement' => array(
            'className'     => 'WikiElement',
            'foreignKey'    => 'page_id',
            'dependent'     => true // delete if the WikiPage gets deleted
        )
        ,'WikiImage' => array(
                'className'     => 'WikiImage',
                'foreignKey'    => 'page_id',
                'dependent'     => true // delete if the WikiPage gets deleted
        )
    );
    
    // TODO model validation needed
    
    /**
     * Retrieves a certain WikiPage from model / external source
     * @param string $title
     * @return array the resultset
     */
    public function getPage($title){
        $title = urldecode($title);
        $wikipage = $this->findByTitle($title);
        $maxage = Configure::read('System.autoupdateage');
        
        if(   empty($wikipage['WikiPage']['id'])
           || (!empty($wikipage['WikiPage']['updatedat'])
                && time() - strtotime($wikipage['WikiPage']['updatedat']) > $maxage
            )
        ){
            // cache miss or outdated
            $wikipage = $this->updateWikiPage($title);
        }else{
            // cache hit - just update access time
            $this->id = $wikipage['WikiPage']['id'];
            $this->saveField('requested', date('Y-m-d H:i:s', time()));
        }
        return $wikipage;
    }
    
    /**
     * Updates the dataset of a certain WikiPage. If the dataset for the
     * specified page doesn't exist, he will be created.
     * @param string $title The title of the page to be updated.
     * @return The updated WikiPage dataset or false it sth. went wrong.
     */
    public function updateWikiPage($title){
        // request page from wiki
        $content = $this->retrievePageContent($title);
        
        if($content !== false){
            
            // replace all relative source links
            $content = str_replace(
                'src="/'
                , 'src="'.Configure::read('WikiPage.baseimageurl')
                , $content
            );
            
            // remove comments
            $content = preg_replace('/<!--(.*)-->/Uis', '', $content);
            
            // read, update, save
            $this->create();
            $data = $this->findByTitle($title);
            $data['WikiPage']['title'] = $title;
            $data['WikiPage']['content'] = $content;
            if(empty($data['WikiPage']['requested'])){
                $data['WikiPage']['requested'] = date('Y-m-d H:i:s', time());
            }
            $data['WikiPage']['updatedat'] = date('Y-m-d H:i:s', time());
            if($this->save($data)){
                $data['WikiPage']['id'] = $this->id;
                /*
                // deactivated due to possible race condition
                if(Configure::read('WikiImage.enabled')){
                    App::import('Model', 'WikiImage');
                    $wikiImageObj = new WikiImage();
                    
                    // Start transaction for this page title - otherwise other
                    // threads could execute the same code and create a mess
                    // in the file system. (orphan image files)
                    // If we fail, uncached images with working image links
                    // will be delivered.
                    if($this->isFreeLock(md5($title))&&
                        $this->lock(md5($title))
                    ){
                        $data = $wikiImageObj->replaceImages($data);
                        $this->unlock(md5($title));
                    }
                }
                */
                $retval = $data;
            }
        }else{
            // the external source seems to be down, retrieve from database
            $dbResultset = $this->findByTitle($title);
            if(empty($dbResultset)){
                $retval = false;
            }
        }
        return $retval;
    }
    
    /**
     * Retrieves the HTML from the configured data source. This method can be
     * overwritten in a test case in order to test the model.
     * @param string $title The title of the page to retrieve.
     * @return string The HTML retrieved from the datasource or false if sth.
     *     bad happened.
     */
    protected function retrievePageContent($title){
        $baseUrl = Configure::read('WikiPage.basepageurl');
        
        // @ deactivates warnings caused by HTTP404 or other failing stuff
        return @file_get_contents($baseUrl.$title, false, $this->streamContext);
    }
}
?>
