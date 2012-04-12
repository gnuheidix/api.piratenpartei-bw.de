/**
 * api.piratenpartei-bw.de
 * 
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @author  Thomas Heidrich, Adrian Kummerländer
 * @copyright Copyright (c) 2012 Thomas Heidrich and other authors
 */

var MyIcon = L.Icon.extend({
    iconUrl: '/img/stammtisch/pin.png',
    iconSize: new L.Point(57, 34),
    iconAnchor: new L.Point(0, 33),
    popupAnchor: new L.Point(18, -33)
});

map.scrollWheelZoom.disable();
map.addLayer(cloudmade);
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
