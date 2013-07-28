<?php //CPYRGHT
/**
 * api.piratenpartei-bw.de
 * 
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @author  Thomas Heidrich
 * @copyright Copyright (c) 2012 Thomas Heidrich and other authors
 */
?><?php
App::uses('Stammtisch', 'Model');

class StammtischTestCase extends CakeTestCase {
    public $fixtures = array(
        'app.stammtisch'
        ,'app.wikiPage'
        ,'app.wikiElement'
        ,'app.geoCoordinate'
    );
    
    public function setup(){
        parent::setUp();
        // set autoupdate to a low value in order to force the
        // page update from wiki to happen
        Configure::write('System.autoupdateage', 1);
        $this->Stammtisch = ClassRegistry::init('TestStammtisch');
    }
    
    /**
     * Tests if a the stammtisches are getting updated correctly
     */
    public function testRetrieveStammtische(){
        $this->Stammtisch->updateStammtische();
        
        $datasetCount = $this->Stammtisch->find('count');
        $this->assertTrue($datasetCount > 0);
        
        $dataset = $this->Stammtisch->find('first');
        $this->assertTrue(!empty($dataset['Stammtisch']['data']));
    }
    
    /**
     * Checks whether HTML-anchors are getting processed correctly. 
     */
    public function testExtractHref(){
        $result = $this->Stammtisch->callMethod('extractHref', array('<a href="/blabla">foobar</a>'));
        $this->assertEqual($result, '/blabla');
        $result = $this->Stammtisch->callMethod('extractHref', array('<a hre=/blablafoobar</a>'));
        $this->assertEqual($result, '');
        $result = $this->Stammtisch->callMethod('extractHref', array('<a/blablafoobar</a>'));
        $this->assertEqual($result, '');
    }
}

/**
 * Maps calls to non-public methods.
 */
class TestStammtisch extends Stammtisch{
    
    // remap model name to original model
    public $alias = 'Stammtisch';
    
    public function callMethod($name, $arguments){
        return call_user_func_array(array($this, $name), $arguments);
    }
}
?>
