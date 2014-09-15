<?php

require_once 'init.php';

//$API->error('maintenance mode');
//$API->TPL->assign('navclass','');
$API->TPL->assign('pagetitle', $API->LANG->_('Search'));
$API->TPL->assign('footername', $API->LANG->_('Search'));

//assign search style
$API->TPL->assign('headeradd', '<link rel="stylesheet" type="text/css" href="./itunes_files/search.css">  ');

$q = trim($API->getval('q'));
$genre = $API->getval('genre', 'int');
$dev = $API->getval('dev', 'int');

$compatibility = $API->getval('compatibility', 'int');

$API->TPL->assign('compatibility', $compatibility);

$type = htmlspecialchars($API->getval('type'));

if (!in_array($type, array('app', 'book', '')))
    $type = '';

$API->TPL->assign('type', $type);

if ($type) {
    $query[] = "apps.type = {$API->DB->sqlesc($type)}";
}

// fucking selector, first check by compatibility, second by type of content. every turn of compatiblity we must check type
if (!$compatibility || $compatibility > 4 || $compatibility < 0) {
    $compatibility_sql = false;
    if ($type == 'book') {
        $compatibility_sql = "apps.type='book'";
        $genres_for_search_addition = "genres.type='books'";
    } elseif ($type == 'app') {
        $compatibility_sql = "apps.type='app'";
        $genres_for_search_addition = "genres.type !='books'";
    }
} elseif ($compatibility == 1) {

    if (!$type) {
        $compatibility_sql = "compatibility<>4";
        $genres_for_search_addition = "genres.type!='mas'";
    } elseif ($type == 'book') {
        $compatibility_sql = "(apps.type='book' AND compatibility<>4)";
        $genres_for_search_addition = "genres.type='books'";
    } elseif ($type == 'app') {
        $genres_for_search_addition = "genres.type NOT IN('mas','books')";
        $compatibility_sql = "(apps.type='app' AND compatibility<>4)";
    }
} elseif ($compatibility == 2) {

    if (!$type) {
        $genres_for_search_addition = "genres.type!='mas'";
        $compatibility_sql = "compatibility NOT IN(3,4)";
    } elseif ($type == 'book') {
        $compatibility_sql = "(apps.type='book' AND compatibility NOT IN(3,4))";
        $genres_for_search_addition = "genres.type='books'";
    } elseif ($type == 'app') {
        $genres_for_search_addition = "genres.type NOT IN('mas','books')";
        $compatibility_sql = "(apps.type='app' AND compatibility NOT IN(3,4))";
    }
} elseif ($compatibility == 3) {

    if (!$type) {
        $genres_for_search_addition = "genres.type!='mas'";
        $compatibility_sql = "compatibility NOT IN(2,4)";
    } elseif ($type == 'book') {
        $compatibility_sql = "(apps.type='book' AND compatibility NOT IN(2,4))";
        $genres_for_search_addition = "genres.type='books'";
    } elseif ($type == 'app') {
        $genres_for_search_addition = "genres.type NOT IN('mas','books')";
        $compatibility_sql = "(apps.type='app' AND compatibility NOT IN(2,4))";
    }
} elseif ($compatibility == 4) {
    $genres_for_search_addition = "genres.type IN('mas','books')";
    if ($type == 'book')
        $compatibility_sql = "apps.type='book'";
    elseif ($type == 'app') {
        $compatibility_sql = "(compatibility=4 AND apps.type='app')";
    } else
        $compatibility_sql = "(apps.type='book' OR compatibility=4)";
}
// selector end

if ($compatibility_sql)
    $query[] = $compatibility_sql;

$API->TPL->assign('section', apps);

$price = $API->getval('price', 'int');
$API->TPL->assign('price', $price);

if ($price == 1) {
    $query['price'] = "price<>'Free'";
} elseif ($price == 2) {
    $query['price'] = "price='Free'";
}


if ($genre) {
    $query['genre'] = "genre_id = $genre";
}
if ($dev) {
    $query['artist'] = "artist_id = $dev";
}

$NO_APPS_CACHE = false;
if ($q) {

    if (strlen($q) < 2)
        $API->error($API->LANG->_('You can only search from %s symbols',2));

    $query[] = "(apps.name LIKE " . $API->DB->sqlwildcardesc($q) . ")";
    // searching by dev name

    if (!$query['artist']) {
        $devs_for_search = $API->DB->query_return("SELECT id FROM artists WHERE name LIKE " . $API->DB->sqlwildcardesc($q));

        if ($devs_for_search) {
            foreach ($devs_for_search as $d) {
                $devs_OR_query[] = "artist_id={$d['id']}";
            }
            $OR_query[] = "((" . implode(' OR ', $devs_OR_query) . ")" . ($compatibility_sql ? " AND $compatibility_sql" : '') . ($query['genre'] ? " AND {$query['genre']}" : '') . ($query['price'] ? " AND {$query['price']}" : '') . ")";
        }
    }

    if (!$query['genre']) {
        // searching by genre name
        $genres_for_search = $API->DB->query_return("SELECT id FROM genres WHERE name LIKE " . $API->DB->sqlwildcardesc($q) . ($genres_for_search_addition ? " AND $genres_for_search_addition" : ''));

        if ($genres_for_search) {
            foreach ($genres_for_search as $g) {
                $genres_OR_query[] = "genre_id={$g['id']}";
            }
            $OR_query[] = "((" . implode(' OR ', $genres_OR_query) . ")" . ($query['price'] ? " AND {$query['price']}" : '') . ")";
        }
    }
    $NO_APPS_CACHE = true;
}
$q = htmlspecialchars($q);
$apps = $genres = array();


$genres_cache_query = "SELECT id,name, type, (SELECT COUNT(*) FROM apps WHERE genre_id=genres.id" . ($compatibility_sql ? " AND $compatibility_sql" : '') . ") AS numapps FROM genres" . ($genres_for_search_addition ? " WHERE $genres_for_search_addition" : '') . " ORDER BY numapps DESC";
$genres_cache_name = "genres-" . md5($genres_cache_query);

$genres_cache = $API->CACHE->get('lists_caches', $genres_cache_name);

if ($genres_cache === false) {

    $genres = $API->DB->query_return($genres_cache_query);
    $API->CACHE->set('lists_caches', $genres_cache_name, $genres);
} else
    $genres = $genres_cache;


if ($query)
    $OR_query[] = "(" . implode(' AND ', $query) . ")";


$where = ($OR_query ? ' WHERE ' . implode(' OR ', $OR_query) : '');

/* $appscount_cache_name = 'count-search-' . apps . '-' . md5($where);

  $appscount_cache = $API->CACHE->get('lists_caches', $appscount_cache_name);

  if ($appscount_cache === false) {
  $appscount = $API->DB->get_row_count(apps, $where);
  $API->CACHE->set('lists_caches', $appscount_cache_name, $appscount);
  }
  else
  $appscount = $appscount_cache;


  if (!$appscount || !$genres)
  $API->TPL->assign('noresults', true); */

list($limit, $pagercode) = $API->generate_pagination($appscount, array('search', 'dev', $dev, 'genre', $genre, 'compatibility', $compatibility, 'q', $q, 'section', apps, 'type', $type, 'price', $price));

$API->TPL->assign('pagercode', $pagercode);

$cache_query = "SELECT trackid AS id, version, apps.type, price, added, artist_id,image, apps.name, genres.name AS gname, genres.type AS gtype, artists.name AS pname FROM apps LEFT JOIN genres ON apps.genre_id=genres.id LEFT JOIN artists ON apps.artist_id=artists.id " . $where . " ORDER BY added DESC $limit";

$cache_name = 'data-search-' . md5($cache_query);

if ($NO_APPS_CACHE)
    $cache = false;
else
    $cache = $API->CACHE->get('lists_caches', $cache_name);
$cache = false;
if ($cache === false) {
    $apps = $API->DB->query_return($cache_query);
    if (!$NO_APPS_CACHE)
        $API->CACHE->set('lists_caches', $cache_name, $apps);
} else
    $apps = $cache;

if (!$apps) {
    $API->TPL->assign('noresults', true);
}
$API->TPL->assign('q', $q);


$last = $API->DB->query_return("SELECT trackid AS id,image, apps.type, price, genres.name AS gname, genres.type AS gtype, apps.name FROM apps LEFT JOIN genres ON apps.genre_id=genres.id ORDER BY added DESC LIMIT 5");

$API->TPL->assign('genre', $genre);
$API->TPL->assign('dev', $dev);


$API->TPL->assign('apps', $apps);
$API->TPL->assign('last', $last);
$API->TPL->assign('genres', $genres);
$API->TPL->display('search.tpl');
?>