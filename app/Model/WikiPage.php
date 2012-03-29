<?php
class WikiPage extends AppModel {
    public $name = 'WikiPage';
    
    /**
     * incoming link from WikiElement
     * @var array
     */
    public $hasMany = array(
        'WikiElement' => array(
            'className'     => 'WikiElement',
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
        
        if(!empty($wikipage['WikiPage']['id'])){
            // update access time
            $this->id = $wikipage['WikiPage']['id'];
            $this->saveField('requested', date('Y-m-d H:i:s', time()));
        }else{
            $wikipage = $this->updateWikiPage($title);
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
            $this->create();
            $data = $this->findByTitle($title);
            $data['WikiPage']['title'] = $title;
            $data['WikiPage']['content'] = $content;
            $updateAgainAt = time() + 3600; // now + 1h
            $data['WikiPage']['updated'] = date('Y-m-d H:i:s',$updateAgainAt);
            $data['WikiPage']['requested'] = date('Y-m-d H:i:s',time());
            $this->save($data);
            $data['WikiPage']['id'] = $this->id;
            
            $retval = $data;
        }
        return $retval;
    }
    
    /**
     * Retrieves the HTML from the configured data source. This method can be
     * overwritten in a test case in order to test the model.
     * @param string $title The title of the page to retrieve.
     * @return string The HTMl retrieved from the datasource or false if sth.
     *     bad happened.
     */
    protected function retrievePageContent($title){
        $baseUrl = Configure::read('WikiPage.basepageurl');
        
        // @ deactivates warnings caused by HTTP404 or other filing stuff
        return @file_get_contents($baseUrl.$title);
    }
}
?>