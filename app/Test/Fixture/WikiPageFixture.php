<?php
class WikiPageFixture extends CakeTestFixture {
    
    /**
     * imports the table definition from regular database
     */
    public $import = 'WikiPage';
    
    /**
     * some initial database records 
     */
    public $records;
    
    /**
     * Fills up the database records beforehand.
     */
    public function __construct(){
        
        $this->records = array(
            array('id' => 1, 'title' => 'Kreisverband_Konstanz', 'content' => '<div id="einmalig">Dies ist ein Test.</div>', 'requested' => date('Y-m-d H:00:00', time()), 'updatedat' => date('Y-m-d H:00:00', time()), 'created' => '2007-03-18 10:39:23')
            ,array('id' => 2, 'title' => 'BW:Kreisverband_Konstanz', 'content' => '<div id="einmalig">Dies ist ein Test.</div>', 'requested' => '2007-03-18 10:39:23', 'updatedat' => '2007-03-18 10:39:23', 'created' => '2007-03-18 10:39:23')
        );
        
        parent::__construct();
    }
 }
 
?>