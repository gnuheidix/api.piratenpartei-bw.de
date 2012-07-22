<?php //CPYRGHT
/**
 * api.piratenpartei-bw.de
 * 
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @author  Thomas Heidrich
 * @copyright Copyright (c) 2012 Thomas Heidrich and other authors
 */
?><?php

App::import('Controller', 'App');

class AppControllerTest extends ControllerTestCase{
    public $fixtures = array();
    
    public $controller = null;
    
    // Here we instantiate our helper
    public function setUp() {
        parent::setUp();
        $this->controller = new TestAppController();
    }
    
    public function testSanitizeBoolean(){
        $this->assertEqual($this->controller->callMethod('sanitizeBooleanParam', array('bla', false)), false);
        $this->assertEqual($this->controller->callMethod('sanitizeBooleanParam', array('bla', 'nein')), 'nein');
        
        $this->controller->params['named']['testparam'] = '0';
        $this->assertEqual($this->controller->callMethod('sanitizeBooleanParam', array('testparam', false)), 'false');
        $this->controller->params['named']['testparam'] = 0;
        $this->assertEqual($this->controller->callMethod('sanitizeBooleanParam', array('testparam', false)), false);
        $this->controller->params['named']['testparam'] = 0;
        $this->assertEqual($this->controller->callMethod('sanitizeBooleanParam', array('testparam')), 'true');
        $this->controller->params['named']['testparam'] = 'blubber';
        $this->assertEqual($this->controller->callMethod('sanitizeBooleanParam', array('testparam')), 'true');
    }
    
    public function testSanitizeInt(){
        $this->assertEqual($this->controller->callMethod('sanitizeIntParam', array('bla', 0, 10, 4)), 4);
        $this->assertEqual($this->controller->callMethod('sanitizeIntParam', array('bla', 1, 2, 2)), 2);
        
        $this->controller->params['named']['testparam'] = '5';
        $this->assertEqual($this->controller->callMethod('sanitizeIntParam', array('testparam', 0, 10, 0)), 5);
        $this->controller->params['named']['testparam'] = '3';
        $this->assertEqual($this->controller->callMethod('sanitizeIntParam', array('testparam', 0, 10)), 3);
        $this->controller->params['named']['testparam'] = '3';
        $this->assertEqual($this->controller->callMethod('sanitizeIntParam', array('testparam', 3, 10)), 3);
        $this->controller->params['named']['testparam'] = '3';
        $this->assertEqual($this->controller->callMethod('sanitizeIntParam', array('testparam', 0, 3)), 3);
        $this->controller->params['named']['testparam'] = '3';
        $this->assertEqual($this->controller->callMethod('sanitizeIntParam', array('testparam', 0, 2)), 0);
        $this->controller->params['named']['testparam'] = '3';
        $this->assertEqual($this->controller->callMethod('sanitizeIntParam', array('testparam', 4, 8)), 0);
        $this->controller->params['named']['testparam'] = '4';
        $this->assertEqual($this->controller->callMethod('sanitizeIntParam', array('testparam', 0, 10)), 4);
        $this->controller->params['named']['testparam'] = 'blubber';
        $this->assertEqual($this->controller->callMethod('sanitizeIntParam', array('testparam', 0, 10)), 0);
    }
    
    public function testSanitizeFloat(){
        $this->assertEqual($this->controller->callMethod('sanitizeFloatParam', array('bla', 0, 10, 4)), 4);
        $this->assertEqual($this->controller->callMethod('sanitizeFloatParam', array('bla', 1, 2, 2)), 2);
        
        $this->controller->params['named']['testparam'] = '5';
        $this->assertEqual($this->controller->callMethod('sanitizeFloatParam', array('testparam', 0, 10, 0)), 5);
        $this->controller->params['named']['testparam'] = '3.1407';
        $this->assertEqual($this->controller->callMethod('sanitizeFloatParam', array('testparam', 0, 10)), 3.1407);
        $this->controller->params['named']['testparam'] = '3';
        $this->assertEqual($this->controller->callMethod('sanitizeFloatParam', array('testparam', 3, 10)), 3);
        $this->controller->params['named']['testparam'] = '3';
        $this->assertEqual($this->controller->callMethod('sanitizeFloatParam', array('testparam', 0, 3)), 3);
        $this->controller->params['named']['testparam'] = '3.3';
        $this->assertEqual($this->controller->callMethod('sanitizeFloatParam', array('testparam', 0, 3.2)), 0);
        $this->controller->params['named']['testparam'] = '3.54';
        $this->assertEqual($this->controller->callMethod('sanitizeFloatParam', array('testparam', 3.55, 8)), 0);
        $this->controller->params['named']['testparam'] = '4.5';
        $this->assertEqual($this->controller->callMethod('sanitizeFloatParam', array('testparam', 0, 10)), 4.5);
        $this->controller->params['named']['testparam'] = 'blubber';
        $this->assertEqual($this->controller->callMethod('sanitizeFloatParam', array('testparam', 0, 10)), 0);
    }
    
    public function testSanitizeString(){
        $this->assertEqual($this->controller->callMethod('sanitizeStringParam', array('bla', array('d' => 'f'), '4')), '4');
        $this->assertEqual($this->controller->callMethod('sanitizeStringParam', array('bla', array('f' => 'f'), '2')), '2');
        $this->assertEqual($this->controller->callMethod('sanitizeStringParam', array('bla', array('f' => 'f'))), '');
        
        $this->controller->params['named']['testparam'] = '5';
        $this->assertEqual($this->controller->callMethod('sanitizeStringParam', array('testparam', array('5' => '5'))), '5');
        $this->controller->params['named']['testparam'] = 'bäng';
        $this->assertEqual($this->controller->callMethod('sanitizeStringParam', array('testparam', array('bäng' => 'bam'), 10)), 'bam');
        $this->controller->params['named']['testparam'] = 'ddddd';
        $this->assertEqual($this->controller->callMethod('sanitizeStringParam', array('testparam', array('dd' => 'd', 'e' => 'e'))), '');
        $this->controller->params['named']['testparam'] = 'ddd';
        $this->assertEqual($this->controller->callMethod('sanitizeStringParam', array('testparam', array('d' => 'ddd'), 'dd')), 'dd');
    }
    
    public function testBeforeRender(){
        $this->assertEqual($this->controller->viewVars, array());
        
        $expected = Configure::read('System.baseurl');
        $this->controller->beforeRender();
        $this->assertEqual($this->controller->viewVars, array('baseurl' => $expected));
    }
}

/**
 * Maps calls to non-public methods.
 */
class TestAppController extends AppController{
    
    public $params = array();
    
    public function callMethod($name, $arguments){
        return call_user_func_array(array($this, $name), $arguments);
    }
}
?>