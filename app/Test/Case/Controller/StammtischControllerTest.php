<?php //CPYRGHT
/**
 * api.piratenpartei-bw.de
 * 
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @author  Thomas Heidrich
 * @copyright Copyright (c) 2012 Thomas Heidrich and other authors
 */
?><?php

App::import('Controller', 'Stammtisch');

class StammtischControllerTest extends ControllerTestCase{
    public $fixtures = array(
        'app.stammtisch'
        ,'app.wikiPage'
        ,'app.wikiElement'
        ,'app.wikiImage'
        ,'app.geoCoordinate'
    );
    
    public $controller = null;
    
    // Here we instantiate our helper
    public function setUp() {
        parent::setUp();
        // set autoupdate to a low value in order to force the
        // page update from wiki to happen
        Configure::write('System.autoupdateage', 1);
    }
    
    function startTest($method) {
        $this->controller = new TestStammtischController();
        $this->controller->constructClasses();
    }
     
    function endTest($method) {
        unset($this->controller);
        ClassRegistry::flush();
    }
    
    public function testFetchAppointments(){
        $this->controller->params['named']['plz'] = '';
        $this->assertEqual($this->controller->callMethod('fetchAppointments', array()), array());
        $this->controller->params['named']['plz'] = '3,4444,44433';
        $this->assertEqual($this->controller->callMethod('fetchAppointments', array()), array());
        $this->controller->params['named']['plz'] = '5,a,b,c';
        $this->assertEqual($this->controller->callMethod('fetchAppointments', array()), array());
    }
    
    public function testCalendarWebcalAndICS(){
        $this->controller->webcal();
        $this->assertTrue(isset($this->controller->viewVars['events']));
        $this->assertTrue(count($this->controller->viewVars['events']) > 0);
        $foundId = $this->controller->viewVars['events'][0]['Stammtisch']['id'];
        $this->controller->viewVars = array();
        
        $this->controller->termin_ics($foundId);
        $this->assertTrue(!empty($this->controller->viewVars['event']['Stammtisch']));
    }
}

/**
 * Maps calls to non-public methods.
 */
class TestStammtischController extends StammtischController{
    
    public $params = array();
    
    public function callMethod($name, $arguments){
        return call_user_func_array(array($this, $name), $arguments);
    }
}
?>