<?php
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
    public $uses = array('WikiPage');
    
    /**
     * Helpers in use
     * @var array
     */
    public $helpers = array('Html');
    
    /**
     * Parserparameter
     * $rowBegin Tabellenzeilenbeginn
     * $rowEnd Tabellenzeilenende
     * $colSep Tabellenspaltentrenner
     */
    private $rowBegin = "<tr>\n<td>";
    private $rowEnd = "</td></tr>";
    private $colSep = "</td><td>";
    
    /**
     * Informationselemente
     * Es sind alle Spaltenbezeichner der Tabelle anzugeben. Diese werden sich
     * in der Ausgabe wiederfinden.
     */
    private $cols = array(
        'Link'
        , 'Typ'
        , 'Ort'
        , 'Datum'
        , 'Datumsformat'
        , 'Zeit'
        , 'Lokal'
        , 'LokalWebsite'
        , 'Straße'
        , 'PLZ'
        , 'Telefon'
        , 'Frequenz'
        , 'lat'
        , 'lon'
    );
    
    /**
     * Wikiseitentitel, wo die Stammtischinformationen bezogen werden.
     * @var string
     */
    private $sourcePageTitle = 'BW:Arbeitsgruppen/Web/Stammtischübersicht/DPL';
    
    // ############## PUBLICLY ACCESSIBLE METHODS ################
    /**
     * Displays a static manual page
     */
    public function index() {
        // see /app/View/Stammtisch/index.ctp
    }
    
    /**
     * Displays a static manual page
     */
    public function karte(){
        $this->layout = 'barebone';
        // see /app/View/Stammtisch/karte.ctp
    }
    /**
     * Delivers a page from another website without the bloaty
     * HTML stuff around it.
     *
    public function getpage(){
        $this->layout = 'ajax';
        $this->view = 'get';
        $content = ':(';
        
        $title = $this->parseGetParams($this->params);
        if(!empty($title)){
            
            // lookup the WikiPage or fetch it
            $this->WikiPage->recursive = -1;
            $wikipage = $this->WikiPage->findByTitle($title);
            if(empty($wikipage)){
                $wikipage = $this->updateWikiPage($title);
            }
            
            if(empty($wikipage['WikiPage'])){
                $content = 'Die Wikiseite '.$title.' wurde nicht gefunden. :(';
            }else{
                $content = $wikipage['WikiPage']['content'];
            }
        }else{
            $content = 'Der Aufruf schlug aufgrund fehlerhafter Eingaben fehl.';
        }
        $this->set('content', $content);
    }
    */
    // ############## CONVENIENCE METHODS ################
    /**
     * 
     */
    protected function updateStammtische(){
        // Init
        $blocksParsed = 0;
        $parsedData = array();
        $sepLen = strlen($colSep);
        $wikipage = $this->WikiPage->findByTitle($this->sourcePageTitle);
        
        // Tabelle zeilenweise durchgehen
        while($html !== FALSE){
            $beginBlock = strpos($html, $rowBegin);
            $endBlock = strpos($html, $rowEnd);
            if(   $beginBlock !== FALSE
                    && $endBlock !== FALSE
                    && $beginBlock < $endBlock){
                
                // Zeile extrahieren
                $block =    $colSep
                . substr($html
                        , $beginBlock + strlen($rowBegin)
                        , $endBlock
                );
                if(substr_count($block, $colSep) === count($cols)){
                    $blockData = array();
                    
                    // Daten extrahieren
                    foreach($cols as $col){
                        $endPos = strpos($block, $colSep, $sepLen);
                        $data = substr($block
                                , $sepLen
                                , $endPos - $sepLen - 1
                        );
                        $blockData[$col] = trim($data);
                        $block = substr($block, $endPos);
                    }
                    
                    $parsedData[] = $blockData;
                    ++$blocksParsed;
                }else{
                    trigger_error("\n\nFehler beim parsen der Inhalte von Block: ".$block, E_USER_NOTICE);
                }
        
                $html = substr($html, $endBlock + strlen($rowEnd));
            }else{
                $html = FALSE;
            }
        }
        // Daten schreiben, wenn vorhanden
        if(!empty($parsedData)){
            $file = fopen($destination, 'w');
            if($file !== FALSE){
                fwrite($file, "var stammtische = eval(".json_encode($parsedData).");");
                fclose($file);
            }else{
                trigger_error("\n\nDie aus dem Wiki gewonnen Daten konnten nicht nach "
                        .$destination." geschrieben werden :-("
                        , E_USER_NOTICE
                );
            }
        }else{
            trigger_error("\n\nDer Wiki-Ausleseprozess lieferte keine Daten :-(", E_USER_NOTICE);
        }
    }
}
