<?php //CPYRGHT
/**
 * api.piratenpartei-bw.de
 * 
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @author  Thomas Heidrich
 * @copyright Copyright (c) 2012 Thomas Heidrich and other authors
 */
?><?php
    $this->Html->css(
        array('/js/stammtisch/leaflet/leaflet','stammtisch/stammtisch')
        ,'stylesheet'
        , array('inline' => false )
    );
    $this->Html->script(
        array('stammtisch/leaflet/leaflet','stammtisch/bw', 'stammtisch/data')
        , array('inline' => false )
    );
?>
<div id="map"></div>
<?php 
    echo $this->Html->scriptBlock("
var cloudmadeUrl = 'http://{s}.mqcdn.com/tiles/1.0.0/osm/{z}/{x}/{y}.png';
var subDomains = ['otile1','otile2','otile3','otile4'];
var cloudmadeAttrib = 'Data, imagery and map information provided by <a href=\"http://open.mapquest.co.uk\" target=\"_blank\">MapQuest</a>, <a href=\"http://www.openstreetmap.org/\" target=\"_blank\">OpenStreetMap</a> and contributors, <a href=\"http://creativecommons.org/licenses/by-sa/2.0/\" target=\"_blank\">CC-BY-SA</a>';
var cloudmade = new L.TileLayer(
    cloudmadeUrl
    ,{
        maxZoom: ".$max_zoom."
        ,minZoom: ".$min_zoom."
        ,attribution: cloudmadeAttrib
        ,subdomains: subDomains
    }
);
var map = new L.Map(
    'map'
    ,{
        center: new L.LatLng(
            ".$pos_lat."
            ,".$pos_lon."
        )
    ,zoom: ".$default_zoom."
    }
);
var bawue = new L.Polygon(
    BaWueCoordinates
    ,{
        color:'#F80'
        ,fillColor:'#F80'
        ,smoothFactor:5
    }
);
    ");
    echo $this->Html->script(array('stammtisch/loadmap'));
?>