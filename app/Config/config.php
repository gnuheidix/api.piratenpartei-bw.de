<?php

/**
 * The URL of the wiki. Just the name of the page has to be added
 * for proper retrival.
 */
$config['System'] = array(
    'baseurl' => '/'
);

/**
 * The URL of the wiki. Just the name of the page has to be added
 * for proper retrival.
 */
$config['WikiPage'] = array(
    'basepageurl' => 'http://wiki.piratenpartei.de/wiki//index.php?action=render&title='
);

$config['Stammtisch'] = array(
    /**
     * The title of the page containing the stammtisch information to extract
     * by StammtischModel
     */
    'sourcepagetitle' => 'BW:Arbeitsgruppen/Web/Stammtischübersicht/DPL'
    /**
     * The destination within the filesystem where to put the extracted data.
     */
    ,'destination' => APP.WEBROOT_DIR.DS.'js'.DS.'stammtisch'.DS.'data.js'
    /**
     * Several parser parameters used by StammtischModel
     */
    ,'rowbegin' => "<tr>\n<td>"
    ,'rowend' => "</td></tr>"
    ,'colsep' => "</td><td>"
    ,'cols' => array(
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
    )
);


?>