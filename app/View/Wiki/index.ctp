<?php //CPYRGHT
/**
 * api.piratenpartei-bw.de
 * 
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @author  Thomas Heidrich
 * @copyright Copyright (c) 2012 Thomas Heidrich and other authors
 */
?><p>Diese Hauptfunktion dient hauptsächlich dazu, um Inhalte des Piratenwikis zu extrahieren. Sämtliche Inhalte werden auch bei einem Ausfall des Piratenwikis zur Verfügung stehen und regelmäßig aktualisiert.</p>
<ul>
    <li><a href="<?php echo $baseurl; ?>wiki/statistik">Statistik</a></li>
</ul>
<h2>Benutzung</h2>
<p>Die folgenden Codebeispiele könnten bei der Einbindung in die eigene Site behilflich sein.</p>
<h3>HTML iframe</h3>
<pre>
&lt;iframe src="<?php echo FULL_BASE_URL.$baseurl; ?>wiki/gethtml/BW:Vorstand/pbw_protokolle">&lt;/iframe>
&lt;iframe src="<?php echo FULL_BASE_URL.$baseurl; ?>wiki/getpagehtml/BW:Vorstand">&lt;/iframe>
</pre>
<ul>
    <li><a href="<?php echo FULL_BASE_URL.$baseurl; ?>wiki/gethtml/BW:Vorstand/pbw_protokolle"><?php echo FULL_BASE_URL.$baseurl; ?>wiki/gethtml/BW:Vorstand/pbw_protokolle</a> (Inhalt des Divs mit HTML-Boilerplate)</li>
    <li><a href="<?php echo FULL_BASE_URL.$baseurl; ?>wiki/getpagehtml/BW:Vorstand"><?php echo FULL_BASE_URL.$baseurl; ?>wiki/getpagehtml/BW:Vorstand</a> (Seiteninhalt mit HTML-Boilerplate)</li>
</ul>

<h3>JavaScript JSONP</h3>
<pre>
&lt;div id="wiki0815">&lt;/div>
&lt;script type="text/javascript" src="<?php echo FULL_BASE_URL.$baseurl; ?>wiki/getpagejson/BW:Vorstand/var0815">&lt;/script>
&lt;script type="text/javascript">
&lt;!--
    var errorVal = "Dieser Inhalt steht zur Zeit nicht zur Verfügung.";
    if(typeof(var0815) == "undefined"){
        var0815 = errorVal;
    }
    document.getElementById("wiki0815").innerHTML = var0815;
//-->
&lt;/script>
</pre>
<ul>
    <li><a href="<?php echo FULL_BASE_URL.$baseurl; ?>wiki/getpagejson/BW:Vorstand/var0815"><?php echo FULL_BASE_URL.$baseurl; ?>wiki/getpagejson/BW:Vorstand/var0815</a> (Seiteninhalt als JSON enkodiert und spezifischer Variable zugewiesen)</li>
</ul>

<h3>PHP</h3>
<pre>
&lt;?php

/**
* Retrieves wiki content from api.piraten-bw.de
* @param string $wikipage the name of the wikipage to fetch
* @param string $elementId the id of the div whose content shall be extracted
* @param int $maxlen the max size of the retrieved content in bytes
* @returns string The retrieved content or a friendly error message
*/
function apiGetContent($wikipage, $elementId = '', $maxlen = 40000){
    $baseUrl = '<?php echo FULL_BASE_URL.$baseurl; ?>';
    $opts = array(
        'http' => array(
            'timeout' => 4
        )
    );
    $context = stream_context_create($opts);
    
    if(empty($elementId)){
        $url = $baseUrl . 'getpage/' . $wikipage;
    }else{
        $url = $baseUrl . 'get/' . $wikipage . '/' . $elementId;
    }
    
    $result = file_get_contents($url, false, $context, -1, $maxlen);
    return $result ? $result : 'Die Informationen von &lt;a href="http://wiki.piratenpartei.de/'.$wikipage.'">'.$wikipage.'&lt;/a> stehen zur Zeit hier leider nicht zur Verfügung.';
}

// example calls
// echo apiGetContent('BW:Vorstand');
// echo apiGetContent('BW:Vorstand', 'pbw_protokolle');
</pre>
<ul>
    <li><a href="<?php echo FULL_BASE_URL.$baseurl; ?>wiki/gethtml/BW:Vorstand/pbw_protokolle"><?php echo FULL_BASE_URL.$baseurl; ?>wiki/get/BW:Vorstand/pbw_protokolle</a> (Inhalt des Divs ohne HTML-Boilerplate)</li>
    <li><a href="<?php echo FULL_BASE_URL.$baseurl; ?>wiki/getpagehtml/BW:Vorstand"><?php echo FULL_BASE_URL.$baseurl; ?>wiki/getpage/BW:Vorstand</a> (Seiteninhalt ohne HTML-Boilerplate)</li>
</ul>
