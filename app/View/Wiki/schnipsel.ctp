<?php //CPYRGHT
/**
 * api.piratenpartei-bw.de
 * 
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @author  Thomas Heidrich
 * @copyright Copyright (c) 2012 Thomas Heidrich and other authors
 */
?><h2>Codebeispiele</h2>
<p>Die folgenden Codebeispiele könnten bei der Einbindung in die eigene Site behilflich sein.</p>
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
    $baseUrl = 'http://api.piraten-bw.de/wiki/';
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
    return $result ? $result : 'Die Informationen von &lt;a href="http://wiki.piratenpartei.de/'.$wikipage.'">'.$wikipage.'</a> stehen zur Zeit hier leider nicht zur Verfügung.';
}

// example calls
// echo apiGetContent('BW:Vorstand');
// echo apiGetContent('BW:Vorstand', 'pbw_protokolle');
</pre>
