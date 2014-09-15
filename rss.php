<?php

header('Content-Type: application/xml');
require_once 'init.php';



//$appscount = $API->DB->get_row_count("apps");


$apps = $API->DB->query_return("SELECT trackid, added, last_parse_itunes ,image, apps.name, genres.name AS gname, artists.name AS pname FROM apps LEFT JOIN genres ON apps.genre_id=genres.id LEFT JOIN artists ON apps.artist_id=artists.id ORDER BY added DESC LIMIT 50");

if ($apps) {

    foreach ($apps as $a) {
        $itdata = json_decode($a['last_parse_itunes'], true);
        $to_rss[] = array('title' => $a['name'] . " by " . $a['pname'] . " ({$a['gname']})",
            'pubDate' => $a['added'],
            'link' => $API->SEO->make_link('view', 'trackid', $a['trackid']),
            'description' => $itdata['description'],
            'image' => $a['image'],
                'trackid'=>$a['trackid']);
    }
    require_once 'classes' . DS . 'rss.class.php';

    $rss = new rss_generator('appaddict.org '.$API->LANG->_('RSS feed'));

    $rss->link = $CONFIG['defaultbaseurl'];
    $rss->description = $API->LANG->_('RSS Feed showing new content');
    $output = $rss->get($to_rss);
}

print $output;
?>