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
        $colSepSource = Configure::read('Stammtisch.colsepSource');
        $colSepDestination = Configure::read('Stammtisch.colsepDestination');
        $rowBeginSource = Configure::read('Stammtisch.rowbeginSource');
        $rowBeginDestination = Configure::read('Stammtisch.rowbeginDestination');
        $rowEndSource = Configure::read('Stammtisch.rowendSource');
        $rowEndDestination = Configure::read('Stammtisch.rowendDestination');
        $destination = Configure::read('Stammtisch.destination');
        
        $parsedData = array();
        $sepLen = strlen($colSepDestination);
        
        // retrieve content from WikiPage
        $wikiPageObj = ClassRegistry::init('WikiPage');
        $wikiPage = $wikiPageObj->getPage($pageTitle);
        $html = FALSE;
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
        
        // cleanup indicators
        $html = preg_replace($rowBeginSource, $rowBeginDestination, $html);
        $html = preg_replace($rowEndSource, $rowEndDestination, $html);
        $html = preg_replace($colSepSource, $colSepDestination, $html);
        
        // parse the HTML table
        while($html !== FALSE){
            $beginBlock = strpos($html, $rowBeginDestination);
            $endBlock = strpos($html, $rowEndDestination);
            if(   $beginBlock !== FALSE
                    && $endBlock !== FALSE
                    && $beginBlock < $endBlock){
                
                // find dataset
                $block = $colSepDestination;
                $block .= substr($html
                        , $beginBlock + strlen($rowBeginDestination)
                        , $endBlock
                );
                if(substr_count($block, $colSepDestination) === count($cols)){
                    $blockData = array();
                    
                    // extract dataset
                    foreach($cols as $col){
                        $endPos = strpos($block, $colSepDestination, $sepLen);
                        $data = substr($block
                                , $sepLen
                                , $endPos - $sepLen - 1
                        );
                        $blockData[$col] = trim($data);
                        $block = substr($block, $endPos);
                    }
                    
                    $parsedData[] = $blockData;
                }
                
                $html = substr($html, $endBlock + strlen($rowEndDestination));
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
            }
        }
    }
}
?>
