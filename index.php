<?php

require_once 'init.php';

$section = 'apps';

$API->TPL->assign('headeradd', '<link rel="stylesheet" type="text/css" href="./css/appslist.css"> ');

$compatibility = $API->getval('compatibility', 'int');

$API->TPL->assign('compatibility', $compatibility);


$type = htmlspecialchars($API->getval('type'));

if (!in_array($type, array('app', 'book', '')))
    $type = '';

$API->TPL->assign('type', $type);
// fucking selector, first check by compatibility, second by type of content. every turn of compatiblity we must check type
if (!$compatibility || $compatibility > 4 || $compatibility < 0) {
    $compatibility_sql = false;
    if ($type == 'book') {
        $compatibility_sql = "$section.type='book'";
        $genres_for_search_addition = "genres.type='books'";
    } elseif ($type == 'app') {
        $genres_for_search_addition = "genres.type !='books'";
        $compatibility_sql = "$section.type='app'";
    }
} elseif ($compatibility == 1) {

    if (!$type) {
        $compatibility_sql = "compatibility<>4";
        $genres_for_search_addition = "genres.type!='mas'";
    }
    elseif ($type == 'book') {
        $compatibility_sql = "($section.type='book' AND compatibility<>4)";
        $genres_for_search_addition = "genres.type='books'";
    } elseif ($type == 'app') {
        $genres_for_search_addition = "genres.type NOT IN('mas','books')";
        $compatibility_sql = "($section.type='app' AND compatibility<>4)";
    }
} elseif ($compatibility == 2) {

    if (!$type) {
        $genres_for_search_addition = "genres.type!='mas'";
        $compatibility_sql = "compatibility NOT IN(3,4)";
    }
    elseif ($type == 'book') {
        $compatibility_sql = "($section.type='book' AND compatibility NOT IN(3,4))";
        $genres_for_search_addition = "genres.type='books'";
    } elseif ($type == 'app') {
        $genres_for_search_addition = "genres.type NOT IN('mas','books')";
        $compatibility_sql = "($section.type='app' AND compatibility NOT IN(3,4))";
    }
} elseif ($compatibility == 3) {
    
    if (!$type) {
        $genres_for_search_addition = "genres.type!='mas'";
        $compatibility_sql = "compatibility NOT IN(2,4)";
    }
    elseif ($type == 'book') {
        $compatibility_sql = "($section.type='book' AND compatibility NOT IN(2,4))";
        $genres_for_search_addition = "genres.type='books'";
    } elseif ($type == 'app') {
        $genres_for_search_addition = "genres.type NOT IN('mas','books')";
        $compatibility_sql = "($section.type='app' AND compatibility NOT IN(2,4))";
    }
} elseif ($compatibility == 4) {
    $genres_for_search_addition = "genres.type IN('mas','books')";
    if ($type == 'book')
        $compatibility_sql = "$section.type='book'";
    elseif ($type == 'app') {
        $compatibility_sql = "(compatibility=4 AND $section.type='app')";
    }
    else
        $compatibility_sql = "($section.type='book' OR compatibility=4)";
}
// selector end

if ($compatibility_sql)
    $query[] = $compatibility_sql;


$genre = $API->getval('genre', 'int');
$API->TPL->assign('genre', $genre);

if ($genre) {
    $query[] = "genre_id = $genre";
}
$price = $API->getval('price', 'int');
$API->TPL->assign('price', $price);

if ($price == 1) {
    $query['price'] = "price<>'Free'";
} elseif ($price == 2) {
    $query['price'] = "price='Free'";
}

if ($query)
    $where = "WHERE " . implode(' AND ', $query);
else
    $where = '';

// genres selector generation

$genres_cache_query = "SELECT id,name, type, (SELECT COUNT(*) FROM $section WHERE genre_id=genres.id" . ($compatibility_sql ? " AND $compatibility_sql" : '') . ") AS numapps FROM genres" . ($genres_for_search_addition ? " WHERE $genres_for_search_addition" : '') . " ORDER BY numapps DESC";

$genres_cache_name = "genres-" . md5($genres_cache_query);

$genres_cache = $API->CACHE->get('lists_caches', $genres_cache_name);

if ($genres_cache === false) {

    $genres = $API->DB->query_return($genres_cache_query);
    $API->CACHE->set('lists_caches', $genres_cache_name, $genres);
}
else
    $genres = $genres_cache;
// translating genres

$to_genres = array('mas' => 'Mac', 'ios' => 'iOS', 'books' => $API->LANG->_('Books'));
if ($genres) {
    foreach ($genres as $k => $g) {
        $genres[$k]['name'] = "{$API->LANG->_($g['name'])} ({$to_genres[$g['type']]})";
    }
}
$API->TPL->assign('genres', $genres);
// end genres, begin apps

/*$appscount_cache_name = 'count-index-' . md5($where);

$appscount_cache = $API->CACHE->get('lists_caches', $appscount_cache_name);

if ($appscount_cache === false) {
    $appscount = $API->DB->get_row_count($section, $where);
    $API->CACHE->set('lists_caches', $appscount_cache_name, $appscount);
}
else
    $appscount = $appscount_cache;*/

list($limit, $pagercode) = $API->generate_pagination($appscount, array('index', 'compatibility', $compatibility, 'price', $price, 'type', $type, 'genre', $genre));


$API->TPL->assign('pagercode', $pagercode);

$cache_query = "SELECT trackid, price, compatibility, artist_id,image, $section.name, $section.genre_id, genres.name AS gname, genres.type AS gtype, artists.name AS pname FROM $section LEFT JOIN genres ON $section.genre_id=genres.id LEFT JOIN artists ON $section.artist_id=artists.id $where ORDER BY added DESC $limit";

$cache_name = 'data-index-' . md5($cache_query);

$cache = $API->CACHE->get('lists_caches', $cache_name);

if ($cache === false) {
    $apps = $API->DB->query_return($cache_query);
    $API->CACHE->set('lists_caches', $cache_name, $apps);
}
else
    $apps = $cache;
$API->TPL->assign('apps', $apps);

$API->TPL->assign('navclass', 'apple');
$API->TPL->assign('pagetitle', $API->LANG->_('Cracked iOS (iPhone, iPad) and Mac App Store (OS X) Apps and Books for Free'));
$API->TPL->assign('footername', $API->LANG->_('New Content'));
$API->TPL->display('index.tpl');
?>