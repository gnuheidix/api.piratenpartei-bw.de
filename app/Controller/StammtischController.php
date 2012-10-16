<?php //CPYRGHT
/**
 * api.piratenpartei-bw.de
 * 
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @author  Thomas Heidrich, Adrian Kummerländer
 * @copyright Copyright (c) 2012 Thomas Heidrich and other authors
 */
?><?php
/**
 * OSM-Location extractor
 *
 * This file renders OSM locations into a nice looking map
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('AppController', 'Controller');

/**
 * Stammtisch location extractor controller
 */
class StammtischController extends AppController{
    
    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Stammtisch';
    
    /**
     * Models in use
     * @var array
     */
    public $uses = array('Stammtisch');
    
    /**
     * Helpers in use
     * @var array
     */
    public $helpers = array('Html');
    
    // ############## PUBLICLY ACCESSIBLE METHODS ################
    /**
     * Displays a static manual page
     */
    public function index(){
        $this->layout = 'default-trans';
    }
    
    /**
     * Displays a map with all stammtisches
     */
    public function karte(){
        $this->layout = 'barebone';
        $this->Stammtisch->updateStammtische();
        
        $minZoom = $this->sanitizeIntParam('minzoom', 3, 24, 6);
        $maxZoom = $this->sanitizeIntParam('maxzoom', 3, 24, 18);
        $defaultZoom = $this->sanitizeIntParam('defaultzoom', 3, 24, 8);
        $lat = $this->sanitizeFloatParam('lat', -90, 90, 48.54);
        $lon = $this->sanitizeFloatParam('lon', -180, 180, 9.04);
        $scrollZoom = $this->sanitizeBooleanParam('scrollzoom');
        $dragging = $this->sanitizeBooleanParam('dragging');
        
        $this->set('min_zoom', $minZoom);
        $this->set('max_zoom', $maxZoom);
        $this->set('default_zoom', $defaultZoom);
        $this->set('lat', $lat);
        $this->set('lon', $lon);
        $this->set('scroll_zoom', $scrollZoom);
        $this->set('dragging', $dragging);
    }
    
    /**
     * Renders an iCal file for a certain stammtisch appointment.
     * @param int $id The ID of the appointment to render.
     */
    public function termin_ics($id = 0){
        $this->layout = 'ajax';
        if(is_object($this->response)){
            $this->response->charset('utf-8');
            $this->response->disableCache();
            $this->response->download('termin.ics');
            $this->response->mustRevalidate();
            $this->response->type('ics');
        }
        $event = $this->Stammtisch->findById($id);
        if(!empty($event)){
            $event['Stammtisch']['timestamp'] = strtotime($event['Stammtisch']['date']);
        }
        $this->set('event', $event);
    }
    
    /**
     * Renders an iCal file for stammtisch appointments.
     */
    public function webcal(){
        $this->layout = 'ajax';
        if(is_object($this->response)){
            $this->response->charset('utf-8');
            $this->response->disableCache();
            $this->response->download('termin.ics');
            $this->response->mustRevalidate();
            $this->response->type('ics');
        }
        $this->Stammtisch->updateStammtische();
        $events = $this->fetchAppointments();
        $this->set('events', $events);
    }
    
    /**
     * Displays a calendar with all stammtisches
     */
    public function kalender(){
        $this->layout = 'barebone';
        $this->Stammtisch->updateStammtische();
        $events = $this->fetchAppointments();
        $this->set('events', $events);
        
        $defaultView = $this->sanitizeStringParam(
            'defaultview'
            ,array(
                'month' => 'month'
                ,'week' => 'basicWeek'
                ,'day' => 'basicDay'
            )
            ,'month'
        );
        $this->set('defaultview', $defaultView);
    }
    
    /**
     * Loads stammtisch appointments and filters them using the named
     * param plz.
     * @return array found appointments
     */
    protected function fetchAppointments(){
        $conditions = array(
            array(
                'not' => array(
                    'Stammtisch.date' => null
             )
            )
        );
        
        // add plz OR conditions in case they are valid
        $postcodeOR = array();
        if(!empty($this->params['named']['plz'])
            && Validation::custom(
                $this->params['named']['plz']
                ,'/^[0-9]{1,5}(,[0-9]{1,5}){0,20}$/'
            )
        ){
            $postcodes = split(',', $this->params['named']['plz']);
            foreach($postcodes as $postcode){
                if(strlen($postcode) < 5){
                    $postcode .= '%';
                }
                $postcodeOR['or'][] = array(
                    'Stammtisch.plz LIKE' => $postcode
                );
            }
        }
        $conditions[] = $postcodeOR;
        
        // do the search
        $events = $this->Stammtisch->find(
            'all'
            ,array(
                'conditions' => $conditions
                ,'recursive' => -1
            )
        );
        
        foreach ($events as $index => $event){
            $events[$index]['Stammtisch']['timestamp'] = strtotime($event['Stammtisch']['date']);
        }
        return $events;
    }
}
