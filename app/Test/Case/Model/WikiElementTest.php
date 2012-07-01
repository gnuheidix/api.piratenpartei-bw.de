<?php //CPYRGHT
/**
 * api.piratenpartei-bw.de
 * 
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @author  Thomas Heidrich, Adrian Kummerländer
 * @copyright Copyright (c) 2012 Thomas Heidrich and other authors
 */
?><?php
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
     * WikiElement can be retrieved from Wiki and our own cache?
     */
    public function testGetElement(){
        $resultLoaded = $this->WikiElement->getElement("Kreisverband_Konstanz", "pkn_intro");
        $this->assertTrue(!empty($resultLoaded['WikiElement']['content']));
        sleep(2);
        $resultCached = $this->WikiElement->getElement("Kreisverband_Konstanz", "pkn_intro");
        $this->assertEqual($resultLoaded['WikiElement']['content'], $resultCached['WikiElement']['content']);
        $this->assertEqual($resultLoaded['WikiElement']['updatedat'], $resultCached['WikiElement']['updatedat']);
        
        $this->WikiElement->id = $resultLoaded['WikiElement']['id'];
        $requestedTime = $this->WikiElement->field('requested');
        $this->assertNotEqual($resultLoaded['WikiElement']['requested'], $requestedTime);
    }
    
    /**
     * Test resultset if a not existing page + element gets requested.
     */
    public function testGetPageAndElementNotAvailable(){
        $result = $this->WikiElement->getElement("nix:da", "garnix");
        $this->assertEqual($result, false);
    }
    
    
    /**
     * Test resultset if a not existing element gets requested.
     */
    public function testGetElementNotAvailable(){
        $result = $this->WikiElement->getElement("Kreisverband_Konstanz", "garnix");
        $this->assertEqual($result, false);
    }
}
?>