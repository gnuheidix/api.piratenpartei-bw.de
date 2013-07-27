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
        $initialResult = $this->WikiElement->getElement("BW:Kreisverband_Konstanz/Termine", "pkn_intro");
        $this->assertTrue(!empty($initialResult['WikiElement']['content']));
        sleep(2);
        
        // caching works?
        $cachedResult = $this->WikiElement->getElement("BW:Kreisverband_Konstanz/Termine", "pkn_intro");
        $this->assertEqual($initialResult['WikiElement']['content'], $cachedResult['WikiElement']['content']);
        $this->assertEqual($initialResult['WikiElement']['updatedat'], $cachedResult['WikiElement']['updatedat']);
        
        // update request time works?
        $this->WikiElement->id = $initialResult['WikiElement']['id'];
        $requestedTime = $this->WikiElement->field('requested');
        $this->assertNotEqual($initialResult['WikiElement']['requested'], $requestedTime);
        
        // extract other element possible?
        $otherElement = $this->WikiElement->getElement("BW:Kreisverband_Konstanz/Termine", "pkn_logo");
        $this->assertTrue(!empty($otherElement['WikiElement']['content']));
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
