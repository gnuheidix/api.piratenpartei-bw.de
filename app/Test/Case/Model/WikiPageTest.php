<?php //CPYRGHT
/**
 * api.piratenpartei-bw.de
 * 
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @author  Thomas Heidrich
 * @copyright Copyright (c) 2012 Thomas Heidrich and other authors
 */
?><?php
App::uses('WikiPage', 'Model');

class WikiPageTestCase extends CakeTestCase {
    public $fixtures = array(
        'app.wikiElement'
        ,'app.wikiImage'
        ,'app.wikiPage'
    );
    
    public function setup(){
        parent::setUp();
        // set autoupdate to a fairly high value in order to check
        // whther the system can see the difference between relatively new
        // and outdated entries
        Configure::write('System.autoupdateage', 7200);
        $this->WikiPage = ClassRegistry::init('WikiPage');
    }
    
    /**
     * Tests if a dataset can be retrieved from fixture settings.
     */
    public function testGetPageExisting(){
        $result = $this->WikiPage->getPage("Kreisverband_Konstanz");
        $this->assertEqual($result['WikiPage']['updatedat'], date('Y-m-d H:00:00', time()));
        $this->assertEqual($result['WikiPage']['content'], '<div id="einmalig">Dies ist ein Test.</div>');
    }
    
    /**
     * Tests if a dataset can be retrieved from fixture settings and updated from the Wiki.
     */
    public function testGetPageExistingUpdated(){
        $result = $this->WikiPage->getPage("BW:Kreisverband_Konstanz");
        $this->assertNotEqual($result['WikiPage']['content'], '<div id="einmalig">Dies ist ein Test.</div>');
    }
    
    /**
     * Tests if a dataset can be retrieved from fixture settings and updated from the Wiki.
     */
    public function testGetPageExistingNotAvailable(){
        $result = $this->WikiPage->getPage("nix:da");
        $this->assertEqual($result, false);
    }
}
?>