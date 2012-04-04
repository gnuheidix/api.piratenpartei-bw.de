<?php
App::uses('WikiElement', 'Model');

class WikiElementTestCase extends CakeTestCase {
    public $fixtures = array(
        'app.wikiElement'
        ,'app.wikiImage'
        ,'app.wikiPage'
    );
    
    public function setup(){
        parent::setUp();
        $this->WikiElement = ClassRegistry::init('WikiElement');
    }
    
    /**
     * Tests if a dataset can be retrieved from fixture settings.
     */
    public function testGetElementExisting(){
        $result = $this->WikiElement->getElement("Kreisverband_Konstanz", "einmalig");
        $this->assertEqual($result['WikiElement']['updatedat'], date('Y-m-d H:00:00', time()));
        $this->assertEqual($result['WikiElement']['content'], 'Dies ist ein Test.');
    }
    
    /**
     * Tests if a dataset can be retrieved from fixture settings and updated from the Wiki.
     *
    public function testGetPageExistingUpdated(){
        $result = $this->WikiPage->getPage("BW:Kreisverband_Konstanz");
        $this->assertNotEqual($result['WikiPage']['content'], '<div id="einmalig">Dies ist ein Test.</div>');
    }
    
    /**
     * Tests if a dataset can be retrieved from fixture settings and updated from the Wiki.
     *
    public function testGetPageExistingNotAvailable(){
        $result = $this->WikiPage->getPage("nix:da");
        $this->assertEqual($result, false);
    }*/
}
?>