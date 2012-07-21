<?php //CPYRGHT
/**
 * api.piratenpartei-bw.de
 * 
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @author  Thomas Heidrich
 * @copyright Copyright (c) 2012 Thomas Heidrich and other authors
 */
?><?php
/**
 * Website content caching
 *
 * This file [reads, caches, delivers] content from other websites.
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
 * Runs housekeeping and update tasks
 */
class CronController extends AppController{
    
    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Cron';
    
    /**
     * Helpers in use
     * @var array
     */
    public $helpers = array();
    
    /**
     * Models in use
     * @var array
     */
    public $uses = array();
    
    /**
     * Trigger processes which are supposed to to run periodically without user intervation.
     * @param string $flag The logging output mode flag. (debug / querycount)
     */
    public function trigger($flag = ''){
        if(defined('CRON_DISPATCHER')){
            // avoid multiple cron processes
            if(!Cache::read('cron_lock')){
                Cache::write('cron_lock', true);
                Cache::write('cronjob_started', time());
            }else{
                exit(0);
            }
            $this->layout = 'ajax';
            $this->message($flag, "Starting...\n");
            
            // fire up the models
            $wikiPageObject = ClassRegistry::init('WikiPage');
            $wikiElementObject = ClassRegistry::init('WikiElement');
            $stammtischObject = ClassRegistry::init('Stammtisch');
            
            // query all wiki pages and element titles
            $wikiPageObject->Behaviors->attach('Containable');
            $wikiPageDatasets = $wikiPageObject->find(
                'all'
                ,array(
                    'fields' => array(
                        'WikiPage.*'
                    )
                    ,'contain' => array(
                        'WikiElement.element_id'
                    )
                )
            );
            
            // update pages and their elements
            foreach($wikiPageDatasets as $wikiPageDataset){
                $this->message($flag, 'Updating wiki page '.$wikiPageDataset['WikiPage']['title']."\n");
                $updatedWikiPageDataset = $wikiPageObject->updateWikiPage(
                    $wikiPageDataset['WikiPage']['title']
                );
                foreach($wikiPageDataset['WikiElement'] as $wikiElementDataset){
                    $this->message($flag, 'Updating wiki element '.$wikiPageDataset['WikiPage']['title']."\n");
                    $wikiElementObject->updateWikiElement(
                        $updatedWikiPageDataset
                        ,$wikiElementDataset['element_id']
                    );
                }
            }
            
            $this->message($flag, "Updating Stammmtische\n");
            $stammtischObject->updateStammtische();
            $this->message($flag, "Done\n");
            Cache::delete('cron_lock');
            Cache::write('cronjob_finished', time());
        }else{
            $this->Session->setFlash("Diese Funktion darf nur von der Kommandozeile aus aufgerufen werden.");
            $this->redirect('/');
            exit(1);
        }
        if($flag === 'debug'
            || $flag === 'querycount'
        ){
            $this->set('show_query_count', true);
        }
    }
    
    /**
     * Convenience function for publishing messages.
     * @param string $flag The message mode.
     * @param string $message The message to send.
     */
    private function message($flag, $message){
        if($flag === 'debug'){
            echo $message;
        }
    }
}
?>
