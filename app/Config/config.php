<?php

/**
 * Determines where this application is located
 * http://DOMAIN.TLD<baseurl>
 */
$config['System'] = array(
    'baseurl' => '/'
    /**
     * Age of a model in seconds before it gets automatically updated
     * during the request
     */
    ,'autoupdateage' => 3//7200
    /*
    ,'cronupdateage' => 3600
    */
);

$config['WikiPage'] = array(
    /**
     * The URL of the wiki where just the name of a page has to be added
     * for its proper retrival.
     */
    'basepageurl' => 'http://wiki.piratenpartei.de/wiki//index.php?action=render&title='
    /**
     * Time in seconds allowed for one request using file_get_contents.
     */
    ,'requesttimeout' => 5
    /**
     * The URL of the wiki where all relative links can be appended to.
     */
    ,'baseimageurl' => 'http://wiki.piratenpartei.de/'
);

$config['WikiImage'] = array(
        /**
         * The directory where to store cached images. (relative to webroot)
         */
        'basepath' => '/img/wikiimage/'
        /**
         * Determines whether local image caching is active or not.
         */
        ,'enabled' => true
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