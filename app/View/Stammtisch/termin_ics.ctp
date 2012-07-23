<?php //CPYRGHT
/**
 * api.piratenpartei-bw.de
 * 
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @author  Thomas Heidrich
 * @copyright Copyright (c) 2012 Thomas Heidrich and other authors
 */
?><?php
if(!empty($event)){
    echo "BEGIN:VCALENDAR\n";
    echo "METHOD:PUBLISH\n";
    echo "UID:". md5(uniqid(mt_rand(), true)) ."@api.piratenpartei-bw.de\n";
    echo "VERSION:2.0\n";
    echo "PRODID:-//api.piratenpartei-bw.de//DE\n";
    echo "BEGIN:VEVENT\n";
    echo "DTSTAMP:" . gmdate('Ymd').'T'. gmdate('His') . "Z\n";
    echo "DTSTART:".gmdate('Ymd', $event['Stammtisch']["timestamp"])."T".gmdate('His', $event['Stammtisch']["timestamp"])."Z\n";
    echo "DTEND:".gmdate('Ymd', $event['Stammtisch']["timestamp"])."T".gmdate('His', $event['Stammtisch']["timestamp"])."Z\n";
    echo "SUMMARY:".$event['Stammtisch']['data']['typ'].' '.$event['Stammtisch']['data']['ort']."\n";
    echo "URL:".$event['Stammtisch']['url']."\n";
    echo "END:VEVENT\n";
    echo "END:VCALENDAR";
}
?>