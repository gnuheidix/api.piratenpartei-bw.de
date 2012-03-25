<?php
class WikiPage extends AppModel {
    public $name = 'WikiPage';
    
    public $hasMany = array(
        'WikiElement' => array(
            'className'     => 'WikiElement',
            'foreignKey'    => 'page_id',
            'dependent'     => true // delete if the WikiPage gets deleted
        )
    );
    
    /**
     * The URL of the wiki. Just the name of the page has to be added
     * for proper retrival.
     */
    private $wikiBaseUrl = 'http://wiki.piratenpartei.de/wiki//index.php?action=render&title=';
    
    // TODO validation needed
    
    /**
     * Updates the dataset of a certain WikiPage. If the dataset for the
     * specified page doesn't exist, he will be created.
     * @param string $title The title of the page to be updated.
     * @return The updated WikiPage dataset or false it sth. went wrong.
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
            $data = $this->findByTitle($title);
            $data['WikiPage']['title'] = $title;
            $data['WikiPage']['content'] = $content;
            $updateAgainAt = time() + 3600; // now + 1h
            $data['WikiPage']['updated'] = date('Y-m-d H:i:s',$updateAgainAt );
            $this->save($data);
            $data['WikiPage']['id'] = $this->id;
            
            $retval = $data;
        }
        return $retval;
    }
}
?>
