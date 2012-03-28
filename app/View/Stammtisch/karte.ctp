<?php
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
    echo $this->Html->script(array('stammtisch/loadmap'));
?>