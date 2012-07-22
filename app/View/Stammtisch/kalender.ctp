<?php //CPYRGHT
/**
 * api.piratenpartei-bw.de
 * 
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @author  Thomas Heidrich
 * @copyright Copyright (c) 2012 Thomas Heidrich and other authors
 */
?>
<div id='calendar'></div>
<?php 
    $this->Html->css(
        array('/js/stammtisch/fullcalendar/fullcalendar')
        ,'stylesheet'
        , array('inline' => false )
    );
    $this->Html->css(
        array('/js/stammtisch/fullcalendar/fullcalendar.print')
        ,'stylesheet'
        , array(
            'inline' => false
           ,'media' => 'print'
        )
    );
    $this->Html->script(
        array(
            'jquery/jquery-1.7.1.min'
            ,'stammtisch/fullcalendar/fullcalendar.min'
        )
        , array('inline' => false )
    );
    
    $calInit = '
        jQuery(document).ready(function() {
            jQuery("#calendar").fullCalendar({
                firstDay: 1
                ,header: {
                    left: "prev,next today"
                    ,center: "title"
                    ,right: "month,basicWeek,basicDay"
                }
                ,defaultView: "'.$defaultview.'"
                ,dayNamesShort: ["So", "Mo", "Di", "Mi", "Do", "Fr", "Sa"]
                ,dayNames: ["Sonntag", "Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag", "Samstag"]
                ,monthNamesShort: ["Jan", "Feb", "Mär", "Apr", "Mai", "Jun", "Jul", "Aug", "Sep", "Okt", "Nov", "Dez"]
                ,monthNames: ["Januar", "Februar", "März", "April", "Mai", "Juni", "Juli", "August", "September", "Oktober", "November", "Dezember"]
                ,buttonText: {
                    prev: "&nbsp;&#9668;&nbsp;"
                    ,next: "&nbsp;&#9658;&nbsp;"
                    ,prevYear: "&nbsp;&lt;&lt;&nbsp;"
                    ,nextYear: "&nbsp;&gt;&gt;&nbsp;"
                    ,today: "heute"
                    ,month: "Monat"
                    ,week: "Woche"
                    ,day: "Tag"
                }
                ,columnFormat: {
                    month: "ddd"
                    ,week: "ddd, dd.MM."
                    ,day: "dddd"
                }
                ,titleFormat: {
                    month: "MMMM yyyy"
                    ,week: "dd. [MMM][ yyyy]{ - dd. MMM yyyy}"
                    ,day: "dd. MMMM yyyy"
                }
                ,events: [
    ';
    
    foreach($events as $event){
        $calInit .= "{\n";
        $calInit .= 'title: "'.$event['Stammtisch']['data']['typ'].' '.$event['Stammtisch']['data']['ort'].'"'."\n";
        $calInit .= ',url: "'.$event['Stammtisch']['url'].'"'."\n";
        $calInit .= ',start: "'.$event['Stammtisch']['data']['datum'].'"';
        $calInit .= "},\n";
    }
    
    $calInit .= '
                ]
            });
        });
    ';
    echo $this->Html->scriptBlock($calInit);
?>