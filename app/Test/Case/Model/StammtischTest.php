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
        ,'app.wikiImage'
        ,'app.geoCoordinate'
    );
    
    public function setup(){
        parent::setUp();
        // set autoupdate to a low value in order to force the
        // page update from wiki to happen
        Configure::write('System.autoupdateage', 1);
        $this->Stammtisch = ClassRegistry::init('Stammtisch');
    }
    
    /**
     * Tests if a the stammtisches are getting updated correctly
     */
    public function testRetrieveStammtische(){
        $result = $this->Stammtisch->updateStammtische();
        $datasetCount = $this->Stammtisch->find('count');
        $this->assertTrue($datasetCount > 0);
        $dataset = $this->Stammtisch->find('first');
        $this->assertTrue(!empty($dataset['Stammtisch']['data']));
    }
}
?>