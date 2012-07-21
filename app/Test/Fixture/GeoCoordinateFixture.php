<?php //CPYRGHT
/**
 * api.piratenpartei-bw.de
 * 
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @author  Thomas Heidrich
 * @copyright Copyright (c) 2012 Thomas Heidrich and other authors
 */
?><?php
class GeoCoordinateFixture extends CakeTestFixture {
    
    /**
     * imports the table definition from regular database
     */
    public $import = 'GeoCoordinate';
    
    /**
     * some initial database records
     */
    public $records;
    
    /**
     * Fills up the database records beforehand.
     */
    public function __construct(){
        
        $this->records = array();
        
        parent::__construct();
    }
 }
 
?>