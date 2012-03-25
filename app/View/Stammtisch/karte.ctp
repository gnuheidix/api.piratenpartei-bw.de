<?php
    $this->Html->css(
        array('/js/stammtisch/leaflet/leaflet','stammtisch/stammtisch')
        ,'stylesheet'
        , array('inline' => false )
    );
    $this->Html->script(
        array('stammtisch/leaflet/leaflet','stammtisch/bw')
        , array('inline' => false )
    );
    echo $this->Html->scriptBlock('
    
    ');

?>
<div id="map"></div>
<?php 
    echo $this->Html->script(array('stammtisch/loadmap'));
?>