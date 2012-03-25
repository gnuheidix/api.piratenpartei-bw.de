<p>Diese Funktion dient hauptsächlich dazu, um Inhalte des Piratenwikis zu extrahieren. Sämtliche Inhalte werden auch bei einem Ausfall des Piratenwikis zur Verfügung stehen und regelmäßig aktualisiert.</p>
<h2>Funktionen, für nur einen Teil einer Wikiseite</h2>
<p>Die folgenden Funktionen erwarten nach dem letzten <strong>/</strong> die ID des Elements der Wikiseite, welches zurückzuliefern ist. Im Wiki muss der zu extrahierende Teil mit einer auf der Seite einmaligen ID versehen werden. Dies lässt sich beispielsweise mit <em>&lt;div id="einmalig"&gt; ...zu extrahierender Inhalt... &lt;/div&gt;</em> erreichen.</p>
<h3>gethtml</h3>
<p>Diese Funktion ist vorzugsweise durch HTML-iframe oder ähnlichen Funktionen einzusetzen, da sie <strong>eine vollständige HTML-Seite</strong> zurückgibt.</p>
<ul>
    <li><a href="/wiki/gethtml/Kreisverband_Konstanz/Termine/pkn_intro">/wiki/gethtml/Kreisverband_Konstanz/Termine/pkn_intro</a></li>
    <li><a href="/wiki/gethtml/Kreisverband_Konstanz/Termine/pkn_main">/wiki/gethtml/Kreisverband_Konstanz/Termine/pkn_main</a></li>
</ul>
<h4>Beispieleinbindung</h4>
<iframe src="/wiki/gethtml/Kreisverband_Konstanz/Termine/pkn_intro"></iframe>
<iframe src="/wiki/gethtml/Kreisverband_Konstanz/Termine/pkn_main"></iframe>
<h3>get</h3>
<p>Diese Funktion ist vorzugsweise durch PHP-file-get-contents, JavaScript-XHR oder ähnlichen Funktionen einzusetzen, da sie <strong>nur den extrahierten Teil</strong> ohne HTML-Header, Body oder ähnliches zurückgibt.</p>
<ul>
    <li><a href="/wiki/get/Kreisverband_Konstanz/Termine/pkn_intro">/wiki/get/Kreisverband_Konstanz/Termine/pkn_intro</a></li>
    <li><a href="/wiki/get/Kreisverband_Konstanz/Termine/pkn_main">/wiki/get/Kreisverband_Konstanz/Termine/pkn_main</a></li>
</ul>
<h2>Funktionen, für eine gesamte Wikiseite</h2>
<p>Bei den folgenden Funktionen muss lediglich der Titel der auszuliefernden Wikiseite angegeben werden.</p>
<h3>getpagehtml</h3>
<p>Diese Funktion ist vorzugsweise durch HTML-iframe oder ähnlichen Funktionen einzusetzen, da sie <strong>eine vollständige HTML-Seite</strong> zurückgibt.</p>
<ul>
    <li><a href="/wiki/getpagehtml/Kreisverband_Konstanz/Termine">/wiki/getpagehtml/Kreisverband_Konstanz/Termine</a></li>
</ul>
<h4>Beispieleinbindung</h4>
<iframe src="/wiki/getpagehtml/Kreisverband_Konstanz/Termine"></iframe>
<h3>getpage</h3>
<p>Diese Funktion ist vorzugsweise durch PHP-file-get-contents, JavaScript-XHR oder ähnlichen Funktionen einzusetzen, da sie <strong>nur den extrahierten Teil</strong> ohne HTML-Header, Body oder ähnliches zurückgibt.</p>
<ul>
    <li><a href="/wiki/getpage/Kreisverband_Konstanz/Termine">/wiki/getpage/Kreisverband_Konstanz/Termine</a></li>
</ul>