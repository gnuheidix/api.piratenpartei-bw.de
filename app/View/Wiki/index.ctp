<p>Diese Hauptfunktion dient hauptsächlich dazu, um Inhalte des Piratenwikis zu extrahieren. Sämtliche Inhalte werden auch bei einem Ausfall des Piratenwikis zur Verfügung stehen und regelmäßig aktualisiert.</p>
<ul>
    <li><a href="<?php echo $baseurl; ?>wiki/statistik">Statistik</a></li>
</ul>
<h2>Teil einer Wikiseite</h2>
<p>Die folgenden Funktionen erwarten nach dem letzten <strong>/</strong> die ID des Elements der Wikiseite, welches zurückzuliefern ist. Im Wiki muss der zu extrahierende Teil mit einer auf der Seite einmaligen ID versehen werden. Dies lässt sich beispielsweise mit <em>&lt;div id="einmalig"&gt; ...zu extrahierender Inhalt... &lt;/div&gt;</em> erreichen.</p>
<h3>gethtml</h3>
<p>Diese Funktion ist vorzugsweise durch HTML-iframe oder ähnlichen Funktionen einzusetzen, da sie <strong>eine vollständige HTML-Seite</strong> zurückgibt.</p>
<ul>
    <li><a href="<?php echo $baseurl; ?>wiki/gethtml/Kreisverband_Konstanz/Termine/pkn_intro">/wiki/gethtml/Kreisverband_Konstanz/Termine/pkn_intro</a></li>
    <li><a href="<?php echo $baseurl; ?>wiki/gethtml/Kreisverband_Konstanz/Termine/pkn_main">/wiki/gethtml/Kreisverband_Konstanz/Termine/pkn_main</a></li>
</ul>
<h4>Beispieleinbindung</h4>
<iframe src="<?php echo $baseurl; ?>wiki/gethtml/Kreisverband_Konstanz/Termine/pkn_intro"></iframe>
<iframe src="<?php echo $baseurl; ?>wiki/gethtml/Kreisverband_Konstanz/Termine/pkn_main"></iframe>
<h3>get</h3>
<p>Diese Funktion ist vorzugsweise durch PHP-file-get-contents, JavaScript-XHR oder ähnlichen Funktionen einzusetzen, da sie <strong>nur den extrahierten Teil</strong> ohne HTML-Header, Body oder ähnliches zurückgibt.</p>
<ul>
    <li><a href="<?php echo $baseurl; ?>wiki/get/Kreisverband_Konstanz/Termine/pkn_intro">/wiki/get/Kreisverband_Konstanz/Termine/pkn_intro</a></li>
    <li><a href="<?php echo $baseurl; ?>wiki/get/Kreisverband_Konstanz/Termine/pkn_main">/wiki/get/Kreisverband_Konstanz/Termine/pkn_main</a></li>
</ul>
<h2>Gesamte Wikiseite</h2>
<p>Bei den folgenden Funktionen muss lediglich der Titel der auszuliefernden Wikiseite angegeben werden.</p>
<h3>getpagehtml</h3>
<p>Diese Funktion ist vorzugsweise durch HTML-iframe oder ähnlichen Funktionen einzusetzen, da sie <strong>eine vollständige HTML-Seite</strong> zurückgibt.</p>
<ul>
    <li><a href="<?php echo $baseurl; ?>wiki/getpagehtml/Kreisverband_Konstanz/Termine">/wiki/getpagehtml/Kreisverband_Konstanz/Termine</a></li>
</ul>
<h4>Beispieleinbindung</h4>
<iframe src="<?php echo $baseurl; ?>wiki/getpagehtml/Kreisverband_Konstanz/Termine"></iframe>
<h3>getpage</h3>
<p>Diese Funktion ist vorzugsweise durch PHP-file-get-contents, JavaScript-XHR oder ähnlichen Funktionen einzusetzen, da sie <strong>nur den extrahierten Teil</strong> ohne HTML-Header, Body oder ähnliches zurückgibt.</p>
<ul>
    <li><a href="<?php echo $baseurl; ?>wiki/getpage/Kreisverband_Konstanz/Termine">/wiki/getpage/Kreisverband_Konstanz/Termine</a></li>
</ul>
<h3>getpagejson</h3>
<p>Diese Funktion ist vorzugsweise durch JavaScript-JSONP einzusetzen.</p>
<h4>Beispielcode</h4>
<pre>
&lt;script type="text/javascript" src="http://<?php echo $_SERVER['SERVER_NAME'].$baseurl; ?>wiki/getpagejson/Kreisverband_Konstanz/Termine/jsonp"&gt;&lt;/script&gt;
&lt;input type="button" onclick="alert(jsonp);"/&gt;
</pre>
<script type="text/javascript" src="<?php echo $baseurl; ?>wiki/getpagejson/Kreisverband_Konstanz/Termine/jsonp"></script>
<input type="button" onclick="alert(jsonp);" value="Inhalt anzeigen" />