<?php

APP::import('Model', 'WikiPage');
class Stammtisch extends AppModel{
    public $name = 'Stammtisch';
    
    public $useTable = false;
    
    /**
     * Wikiseitentitel, wo die Stammtischinformationen bezogen werden.
     * @var string
     */
    protected $sourcePageTitle = 'BW:Arbeitsgruppen/Web/Stammtischübersicht/DPL';
    
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
     *
     */
    public function updateStammtische(){
        // Init
        $destination = APP.WEBROOT_DIR.DS.'js'.DS.'stammtisch'.DS.'data.js';
        $blocksParsed = 0;
        $parsedData = array();
        $sepLen = strlen($this->colSep);
        
        $wikiPageObj = new WikiPage();
        $wikiPage = $wikiPageObj->getPage($this->sourcePageTitle);
        if(!empty($wikiPage)){
            $html = $wikiPage['WikiPage']['content'];
        }
        // Tabelle zeilenweise durchgehen
        while($html !== FALSE){
            $beginBlock = strpos($html, $this->rowBegin);
            $endBlock = strpos($html, $this->rowEnd);
            if(   $beginBlock !== FALSE
                    && $endBlock !== FALSE
                    && $beginBlock < $endBlock){
                
                // Zeile extrahieren
                $block = $this->colSep;
                $block .= substr($html
                        , $beginBlock + strlen($this->rowBegin)
                        , $endBlock
                );
                if(substr_count($block, $this->colSep) === count($this->cols)){
                    $blockData = array();
                    
                    // Daten extrahieren
                    foreach($this->cols as $col){
                        $endPos = strpos($block, $this->colSep, $sepLen);
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
              //      trigger_error("\n\nFehler beim parsen der Inhalte von Block: ".$block, E_USER_NOTICE);
                    
                }
                
                $html = substr($html, $endBlock + strlen($this->rowEnd));
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
?>
