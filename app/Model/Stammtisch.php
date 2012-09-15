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
 */
class Stammtisch extends AppModel{
    public $name = 'Stammtisch';
    
    /**
     * @var string The file path where to save the JSON export.
     */
    protected $jsonFilePath;
    
    /**
     * Prevent having duplicate entries in database.
     * @var array Validation rules
     */
    public $validate = array(
        'data' => 'isUnique'
    );
    
    /**
     * @see AppModel
     */
    public function __construct($id = false, $table = null, $ds = null){
        parent::__construct($id, $table, $ds);
        
        $this->jsonFilePath = Configure::read('Stammtisch.destination');
    }
    
    /** 
     * Updates all Stammtisch locations into a file in case the
     * current data in that file is old enough. 
     */ 
    public function updateStammtische(){
        $maxage = Configure::read('System.autoupdateage');
        // check age and length of generated file
        if(is_writable($this->jsonFilePath)
            && (
                time() - filemtime($this->jsonFilePath) > $maxage
                || filesize($this->jsonFilePath) < 50
                || defined('CRON_DISPATCHER')
            )
        ){
            // try to prevent other processes to update
            touch($this->jsonFilePath);
            
            // initialize from configuration
            $pageTitle = Configure::read('Stammtisch.sourcepagetitle');
            $cols = Configure::read('Stammtisch.cols');
            $colSepSource = Configure::read('Stammtisch.colsepSource');
            $colSepDestination = Configure::read('Stammtisch.colsepDestination');
            $rowBeginSource = Configure::read('Stammtisch.rowbeginSource');
            $rowBeginDestination = Configure::read('Stammtisch.rowbeginDestination');
            $rowEndSource = Configure::read('Stammtisch.rowendSource');
            $rowEndDestination = Configure::read('Stammtisch.rowendDestination');
            
            $parsedData = array(); 
            $sepLen = strlen($colSepDestination);
            
            // retrieve content from WikiPage 
            $wikiPageObj = ClassRegistry::init('WikiPage');
            $wikiPage = $wikiPageObj->getPage($pageTitle);
            $html = FALSE;
            if(!empty($wikiPage['WikiPage']['content'])){
                // drop all \n and \r in order to avoid parser errors
                $html = $wikiPage['WikiPage']['content'];
                // cleanup indicators
                $html = preg_replace($rowBeginSource, $rowBeginDestination, $html);
                $html = preg_replace($rowEndSource, $rowEndDestination, $html);
                $html = preg_replace($colSepSource, $colSepDestination, $html);
            }
            // parse the HTML table 
            while($html !== FALSE){
                $beginBlock = strpos($html, $rowBeginDestination);
                $endBlock = strpos($html, $rowEndDestination);
                if($beginBlock !== FALSE
                   && $endBlock !== FALSE
                   && $beginBlock < $endBlock
                ){ 
                     
                    // find dataset 
                    $block = $colSepDestination; 
                    $block .= substr(
                        $html
                        ,$beginBlock + strlen($rowBeginDestination)
                        ,$endBlock
                    );
                    if(substr_count($block, $colSepDestination) === count($cols)){
                        $blockData = array(); 
                         
                        // extract dataset 
                        foreach($cols as $wikiKey => $datasetKey){ 
                            $endPos = strpos($block, $colSepDestination, $sepLen); 
                            $data = substr($block 
                                    , $sepLen 
                                    , $endPos - $sepLen - 1 
                            ); 
                            $blockData[$datasetKey] = trim($data); 
                            $block = substr($block, $endPos); 
                        } 
                         
                        $parsedData[] = $blockData; 
                    } 
                     
                    $html = substr($html, $endBlock + strlen($rowEndDestination)); 
                }else{ 
                    $html = false;
                } 
            }
            
            $this->saveDatasets($parsedData);
        }
    }
    
    /**
     * Triggers save procedures for parsed datasets
     * @param array $datasets The datasets to save.
     */
    protected function saveDatasets($datasets){
        $datasets = $this->geocodeAndSaveToDatabase($datasets);
        $this->saveToJSON($datasets);
    }
        
    /**
     * Writes a number of datasets to the database.
     * @param array $parsedData The datasets to save.
     */
    protected function geocodeAndSaveToDatabase($parsedData){
        if(is_array($parsedData)
            && !empty($parsedData)
        ){
            // delete all datasets having no date
            $this->deleteAll(
                array(
                    'Stammtisch.id >' => 0
               )
                ,true
              ,true
           );
            
            // add new database entries
            foreach($parsedData as $index => $dataset){
                // parse date, create a correct timestamp, geocode address if possible
                $dataset = $this->geocodeDataset($dataset);
                
                if(!empty($dataset)){
                    // extract render date timestamp if possible
                    $dateTime = strtotime($dataset['datum'].' '.$dataset['zeit']);
                    
                    // render data array to be written to database
                    $data = array();
                    if($dateTime){
                        $dateField = date('Y-m-d H:i:s', $dateTime);
                        $data['Stammtisch']['date'] = $dateField;
                        $dataset['termin'] = date('d.m.Y - H:i', $dateTime);
                    }else{
                        $data['Stammtisch']['date'] = null;
                    }
                    $data['Stammtisch']['plz'] = $dataset['plz'];
                    $data['Stammtisch']['data'] = json_encode($dataset);
                    
                    // update data array with sanitized data which will be written to the file system
                    $parsedData[$index] = $dataset;
                    
                    // write sanitized data to database
                    $data = $this->create($data);
                    if($this->save($data)){
                        $parsedData[$index]['id'] = $this->id;
                    }else{
                        unset($parsedData[$index]);
                    }
                }
            }
            
            return $parsedData;
        }
    }
    
    /**
     * Writes datasets to a JSON file.
     * @param array $datasets Datasets to save
     */
    protected function saveToJSON($datasets){
        // modify hyperlinks in order to open in parent frame
        foreach($datasets as $index => $dataset){
            $datasets[$index] = preg_replace('/<(a .+?)>/', '<$1 target="_parent" >', $dataset);
        }
        
        // write data to file system if possible
        $file = fopen($this->jsonFilePath, 'w');
        if($file !== FALSE){
            fwrite($file, "var stammtische = eval(".json_encode($datasets).");");
            fclose($file);
        }
    }
    
    /**
     * geocodes a parsed dataset from the wiki.
     * @param array $dataset The dataset to process.
     * @return array The sanitized dataset or false.
     */
    protected function geocodeDataset($dataset){
        if(!empty($dataset['datum'])
            && !empty($dataset['zeit'])
            && Validation::date($dataset['datum'], 'ymd')
        ){
            // sanitize and validate time by dropping
            // anything before and after the first xx:xx occurrence
            $timeMatches = array();
            preg_match('/\\d{1,2}:\\d\\d/', $dataset['zeit'], $timeMatches);
            if(!empty($timeMatches[0])
                && Validation::time($timeMatches[0])
            ){
                $dataset['zeit'] = $timeMatches[0];
            }else{
                return false;
            }
            
            // retrieve geo coordinates from external webservice if needed
            if(empty($dataset['lat'])
                && empty($dataset['lon'])
                && Configure::read('Stammtisch.geolocationQueryEnabled')
            ){
                $geoCoordModel = ClassRegistry::init('GeoCoordinate');
                $coordinates = $geoCoordModel->getCoordinates(
                    $dataset['strasse']
                    ,$dataset['plz']
                    ,$dataset['ort']
                );
                if(!empty($coordinates)){
                    $dataset['lat'] = $coordinates['GeoCoordinate']['lat'];
                    $dataset['lon'] = $coordinates['GeoCoordinate']['lon'];
                }
            }
        }else{
            $dataset = false;
        }
        return $dataset;
    }
    
    /**
     * (non-PHPdoc)
     * @see Model::afterFind()
     */
    public function afterFind($results, $primary = false){
        foreach($results as $key => $val){
            if(!empty($val['Stammtisch']['data'])){
                $results[$key]['Stammtisch']['data'] = json_decode($val['Stammtisch']['data'], true);
                $results[$key]['Stammtisch']['url'] = $this->extractHref($results[$key]['Stammtisch']['data']['link']);
            }
        }
        return $results;
    }
    
    /**
     * Extracts the href attribute value from a HTML a-element.
     * @param string $html The a-element.
     * @return string The url of the href attribute.
     */
    protected function extractHref($html){
        $retval = '';
        
        try{
            $dom = new DOMDocument();
            $dom->loadHTML($html);
            $nodes = $dom->getElementsByTagName('a');
            if($nodes->length > 0
                && $nodes->item(0)->hasAttribute('href')
            ){
                $retval = $nodes->item(0)->getAttribute('href');
            }
        }catch(exception $e){
            
        }
        
        return $retval;
    }
}
?>
