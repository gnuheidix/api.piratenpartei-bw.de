<?php //CPYRGHT
/**
 * api.piratenpartei-bw.de
 * 
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @author  Thomas Heidrich
 * @copyright Copyright (c) 2012 Thomas Heidrich and other authors
 */
?><h2>Statistik</h2>
<p>Dies ist eine Zusammenfassung aller im Speicher befindlichen Seiten.</p>
<ul>
    <li><?php echo $page_count; ?> Wikiseiten</li>
    <li><?php echo $element_count; ?> Wikiseitenelemente</li>
</ul>
<h3>Detailaufschlüsselung</h3>
<?php 
if(!empty($wiki_pages)){
    echo '<ul>';
    foreach($wiki_pages as $wikiPage){
        echo '<li><a href="'.$baseurl.'wiki/getpagehtml/'.$wikiPage['WikiPage']['title'].'" title=" nachgefragt '.$wikiPage['WikiPage']['requested'].' und aktualisiert '.$wikiPage['WikiPage']['updatedat'].'">'.$wikiPage['WikiPage']['title'].'</a> - <a href="'.$baseurl.'wiki/reloadpage/'.$wikiPage['WikiPage']['title'].'">erneuern</a>';
        if(!empty($wikiPage['WikiElement'])){
            echo "<ul>";
            foreach($wikiPage['WikiElement'] as $wikiElement){
                echo '<li><a href="'.$baseurl.'wiki/gethtml/'.$wikiPage['WikiPage']['title'].'/'.$wikiElement['element_id'].'" title=" nachgefragt '.$wikiElement['requested'].' und aktualisiert '.$wikiElement['updatedat'].'">'.$wikiElement['element_id'].'</a></li>';
            }
            echo "</ul>";
        }
        echo '</li>';
    }
    echo '</ul>';
}
?>