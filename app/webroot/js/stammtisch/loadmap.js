/**
 * api.piratenpartei-bw.de
 * 
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @author  Thomas Heidrich, Adrian Kummerländer
 * @copyright Copyright (c) 2012 Thomas Heidrich and other authors
 */
var cloudmadeUrl = 'http://{s}.mqcdn.com/tiles/1.0.0/osm/{z}/{x}/{y}.png',
    subDomains = ['otile1','otile2','otile3','otile4'],
    cloudmadeAttrib = 'Data, imagery and map information provided by <a href="http://open.mapquest.co.uk" target="_blank">MapQuest</a>, <a href="http://www.openstreetmap.org/" target="_blank">OpenStreetMap</a> and contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/" target="_blank">CC-BY-SA</a>';
var cloudmade = new L.TileLayer(cloudmadeUrl, {maxZoom: 18, attribution: cloudmadeAttrib, subdomains: subDomains});
var map = new L.Map('map', {
    center: new L.LatLng(48.54, 9.04), 
    zoom: 8
});

var MyIcon = L.Icon.extend({
    iconUrl: '/img/stammtisch/pin.png',
    iconSize: new L.Point(57, 34),
    iconAnchor: new L.Point(0, 33),
    popupAnchor: new L.Point(18, -33)
});

map.addLayer(cloudmade);

var bawue = new L.Polygon(BaWueCoordinates, {color:'#F80',fillColor:'#F80',smoothFactor:5});
map.addLayer(bawue);

for(i = 0; i < stammtische.length; i++) {
    s = stammtische[i];
    text = "<h1>" + s.Typ + " " + s.Ort + "</h1>"
//         + "<strong>Kontakt:</strong>"
//         + "<a href=\"http://wiki.piratenpartei.de/Benutzer:" + s.nick + "\">"
//         + s.name
//         + "</a>
//         + "(<a href=\"mailto:" + s.email_local + "@" + s.email_domain + "\">✉</a>)<br />"
         + "<strong>Treffpunkt:</strong>"
         + "<address>"
         + s.Lokal + "<br />"
         + s.Straße + "<br />"
         + s.Ort
         + "</address><hr />"
         + "Weitere Informationen finden Sie jederzeit ";
    if(s.LokalWebsite != "")
         text += "auf " + s.LokalWebsite + " oder "
    text += "im Piratenwiki unter " + s.Link + ".";
    var greenIcon = new MyIcon();
    
    if(s.lat !== '' && s.lon !== ''){
        marker = new L.Marker(new L.LatLng(parseFloat(s.lat), parseFloat(s.lon)), {icon:greenIcon});
        marker.bindPopup(text);
        map.addLayer(marker);
    }
}
