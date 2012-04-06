<?php //CPYRGHT
/**
 * api.piratenpartei-bw.de
 * 
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @author  Thomas Heidrich, Adrian Kummerländer
 * @copyright Copyright (c) 2012 Thomas Heidrich and other authors
 */
?><?php

// WikiPage is the data source of this model.
APP::import('Model', 'WikiPage');

/**
 * Parses a certain WikiPage of a special format and renders its
 * content into a JavaScript file.
 * @author gnuheidix
 */
class Stammtisch extends AppModel{
    public $name = 'Stammtisch';
    
    /**
     * The model doesn't need own tables.
     * @var string
     */
    public $useTable = false;
    
    /**
     * Updates all Stammtisch locations into a file in case the
     * current data in that file is old enough.
     */
    public function updateStammtische(){
        // initialize from configuration
        $maxage = Configure::read('System.autoupdateage');
        $pageTitle = Configure::read('Stammtisch.sourcepagetitle');
        $cols = Configure::read('Stammtisch.cols');
        $colSep = Configure::read('Stammtisch.colsep');
        $rowBegin = Configure::read('Stammtisch.rowbegin');
        $rowEnd = Configure::read('Stammtisch.rowend');
        $destination = Configure::read('Stammtisch.destination');
        
        $parsedData = array();
        $sepLen = strlen($colSep);
        
        // retrieve content from WikiPage
        $wikiPageObj = new WikiPage();
        $wikiPage = $wikiPageObj->getPage($pageTitle);
        if(!empty($wikiPage)){
            // check age and length of generated file
            if(   is_writable($destination)
               && (time() - filemtime($destination) > $maxage
                   || filesize($destination) === 0
                  )
              ){
                // drop all \n and \r in order to avoid parser errors
                $html = $wikiPage['WikiPage']['content'];
            }else{
                // stop now, the file is young enough
                return;
            }
        }
        
        // parse the HTML table
        while($html !== FALSE){
            $beginBlock = strpos($html, $rowBegin);
            $endBlock = strpos($html, $rowEnd);
            if(   $beginBlock !== FALSE
                    && $endBlock !== FALSE
                    && $beginBlock < $endBlock){
                
                // find dataset
                $block = $colSep;
                $block .= substr($html
                        , $beginBlock + strlen($rowBegin)
                        , $endBlock
                );
                if(substr_count($block, $colSep) === count($cols)){
                    $blockData = array();
                    
                    // extract dataset
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
                }
                
                $html = substr($html, $endBlock + strlen($rowEnd));
            }else{
                $html = FALSE;
            }
        }
        
        // write data to file if possible
        if(!empty($parsedData)){
            $file = fopen($destination, 'w');
            if($file !== FALSE){
                fwrite($file, "var stammtische = eval(".json_encode($parsedData).");");
                fclose($file);
            }else{
                trigger_error($destination." ohne Schreibrechte :-(", E_USER_NOTICE);
            }
        }
    }
}
?>
