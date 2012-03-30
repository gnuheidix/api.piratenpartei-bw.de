<?php

APP::import('Model', 'WikiPage');
class Stammtisch extends AppModel{
    public $name = 'Stammtisch';
    
    public $useTable = false;
    
    /**
     * Updates all Stammtisch locations into a file
     */
    public function updateStammtische(){
        // Init from configuration
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
            // check age of generated file
            if(time() - filemtime($destination) > $maxage){
                // update generated file
                $html = $wikiPage['WikiPage']['content'];
            }else{
                // stop now, the file is young enough
                return;
            }
        }
        
        // Tabelle zeilenweise durchgehen
        while($html !== FALSE){
            $beginBlock = strpos($html, $rowBegin);
            $endBlock = strpos($html, $rowEnd);
            if(   $beginBlock !== FALSE
                    && $endBlock !== FALSE
                    && $beginBlock < $endBlock){
                
                // Zeile extrahieren
                $block = $colSep;
                $block .= substr($html
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
                trigger_error($destination." ohne Schreibrechte :-(", E_USER_NOTICE);
            }
        }
    }
}
?>
