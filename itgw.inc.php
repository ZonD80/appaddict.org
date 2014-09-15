<?php

if (!defined('INIT'))
    die('Direct access not allowed');

function try_itunes_proxy($url, $proxies, $proxies_failed = array()) {
    global $API;

    $proxies = array_diff($proxies, $proxies_failed);

    if (!$proxies)
        return false;

    $n = array_rand($proxies);
    $return = curl_request("{$proxies[$n]}/$url");
    //if ($_COOKIE['test']) var_dump($return);
    if (!$return) {
        $proxies_failed[] = $proxies[$n];
        return try_itunes_proxy($url, $proxies, $proxies_failed);
    } else {
        return $return;
    }
}

function get_itunes_info($trackid, $type = 'app', $store = 'us') {
    // proxy https://itunes.apple.com

    if (!$trackid) {
        return false;
    }
    if (!$store)
        $store = 'us';
    if (!$type)
        $type = 'app';
    $proxies = array('http://127.0.0.1:81', 'http://94.23.169.52:81', 'http://188.165.24.108');
    //if ($_COOKIE['test']) var_dump("{$store}/{$type}/id{$trackid}/?l=en");
    $data = try_itunes_proxy("{$store}/{$type}/id{$trackid}/?l=en", $proxies);

    if (!$data) {

        return false;
    }

    require_once 'classes' . DS . 'qp' . DS . 'qp.php';

    $data = @qp($data, '', array('ignore_parser_warnings' => TRUE));
    
    $app['type'] = $type;
    $app['trackid'] = $trackid;
    $app['store'] = $store;
    $app['name'] = $data->find('#title.intro div.left h1')->text();

    if (!$app['name'])
        return false;

    $gamecenter = $data->find('.gc-badge')->text();
    $app['gamecenter'] = ($gamecenter ? 1 : 0);
    $app['artist']['name'] = str_replace(array('by ', 'By '), '', $data->find('#content div.padder div#title.intro div.left h2')->text());
    $app['artist']['id'] = preg_match('/id([0-9]+)/', $data->find('#title .right .view-more')->attr('href'), $matches);
    $app['artist']['id'] = $matches[1];
    
    if ($type != 'book') {
        if ($store == 'en')
            $store = 'us';
        $native_data = json_decode(curl_request("https://itunes.apple.com/lookup?id=$trackid&country=$store&lang=en_us"), true);
        //var_dump($native_data);
        if (!$app['artist']['id'])
            $app['artist']['id'] = $native_data['results'][0]['artistId'];
        $app['bundleid'] = $native_data['results'][0]['bundleId'];
    }

    $app['artist']['support'] = $data->find('html body.software div#main div#desktopContentBlockId.platform-content-block div#content div.padder div.center-stack div.app-links a.see-all')->eq(0)->attr('href');
    $app['artist']['website'] = $data->find('html body.software div#main div#desktopContentBlockId.platform-content-block div#content div.padder div.center-stack div.app-links a.see-all')->eq(1)->attr('href');
    $app['description'] = strip_tags($data->find('.center-stack p')->eq(0)->html(), '<br>');
    if ($type == 'app')
        $app['whatsnew'] = trim(strip_tags($data->find('.center-stack p')->eq(1)->html(), '<br>'));
    $appdata['screenshots']['osx'] = $data->find('.screenshots img');
    foreach ($appdata['screenshots']['osx'] as $sc) {
        $app['screenshots']['osx'][] = array('src' => $sc->attr('src'), 'class' => $sc->attr('class'));
    }
    $appdata['screenshots']['iphone'] = $data->find('.iphone-screen-shots img');
    foreach ($appdata['screenshots']['iphone'] as $sc) {
        $app['screenshots']['iphone'][] = array('src' => $sc->attr('src'), 'class' => $sc->attr('class'));
    }
    $appdata['screenshots']['ipad'] = $data->find('.ipad-screen-shots img');
    foreach ($appdata['screenshots']['ipad'] as $sc) {
        $app['screenshots']['ipad'][] = array('src' => $sc->attr('src'), 'class' => $sc->attr('class'));
    }

    if ($app['screenshots']['iphone'] || $app['screenshots']['ipad'])
        unset($app['screenshots']['osx']);

    $app['image'] = $data->find('.product .artwork img')->attr('src-swap');

    $app['price'] = $data->find('#left-stack div.lockup ul.list li div.price')->text();
    $genre = $data->find('#left-stack div.lockup ul.list li.genre a');
    $app['genre']['name'] = $genre->text();
    $app['genre']['id'] = preg_match('/id([0-9]+)/', $genre->attr('href'), $matches);
    $app['genre']['id'] = $matches[1];
    $app['rating']['text'] = $data->find('.app-rating a')->text();
    $app['rating']['description'] = $data->find('.app-rating li')->text();

    if ($type == 'app') {
        $app['compatibility'] = db_compatibility($data->find('.product p')->text());
        $app['requirements'] = str_replace('Compatibility: ', '', $data->find('.application p')->text());
        $app['ratings']['current'] = $data->find('.customer-ratings div')->eq(1)->attr('aria-label');
        $app['published'] = preg_replace('#(Updated|Released)\: #si', '', $data->find('.product li')->eq(2)->text());
        $app['version'] = str_replace('Version: ', '', $data->find('.product li')->eq(3)->text());
        $app['size'] = str_replace('Size: ', '', $data->find('.product li')->eq(4)->text());
        $app['languages'] = preg_replace('#Language(s|)\: #si', '', $data->find('.product li')->eq(5)->text());
        $app['publisher'] = $data->find('.product li')->eq(7)->text();
        $app['seller'] = $app['artist']['name'];
        //$app['seller'] =
        $app['ratings']['all'] = $data->find('.customer-ratings div')->eq(4)->attr('aria-label');
        $inapps = $data->find('.in-app-purchases li');
        foreach ($inapps as $ia) {
            $app['inapps'][] = array('name' => $ia->find('span')->eq(0)->text(), 'price' => $ia->find('span')->eq(1)->text());
        }
    } elseif ($type == 'book') {
        $app['compatibility'] = '0';
        $app['requirements'] = $data->find('.ebook-requirements .availability-message')->eq(1)->text();
        $app['ratings']['current'] = $data->find('.customer-ratings .rating')->attr('aria-label');
        $app['published'] = str_replace('Published:', '', $data->find('.product li.genre')->next()->text());
        $app['publisher'] = str_replace('Publisher: ', '', $data->find('.product li.genre')->next()->next()->text());
        $app['seller'] = str_replace('Seller: ', '', $data->find('.product li.genre')->next()->next()->next()->text());
        $app['printlength'] = str_replace('Print Length: ', '', $data->find('.product li.genre')->next()->next()->next()->next()->text());

        $app['languages'] = preg_replace('#Language(s|)\: #si', '', $data->find('.product li.genre')->next()->next()->next()->next()->next()->text());
    }

    $reviews = $data->find('.customer-review');

    foreach ($reviews as $r) {
        $stars = preg_match('/[0-9]/', $r->find('h5 div')->attr('aria-label'), $matches);
        $app['reviews'][] = array('title' => $r->find('.customerReviewTitle')->text(),
            'rating' => $matches[0],
            'author' => (preg_replace('/\s\s+/', ' ', $r->find('.user-info')->text())),
            'text' => trim(strip_tags($r->find('p')->html(), '<br>')));
    }

    // fix for duplicating whatsnew

    if (isset($app['reviews'][0]) && ($app['whatsnew'] == $app['reviews'][0]['text']))
        unset($app['whatsnew']);

    $appdata['relatedapps'] = $data->find('.more-by>ul>li');

    foreach ($appdata['relatedapps'] as $ra) {
        $ra = $ra->find('>div');
        $link = $ra->find('a.artwork-link')->attr('href');
        $link = parse_itunes_url($link);
        $a['store'] = $link['store'];
        $a['trackid'] = $link['trackid'];
        $a['type'] = $link['type'];
        $a['name'] = $ra->attr('aria-label');
        $a['artist']['name'] = $ra->attr('preview-artist');
        $a['image'] = $ra->find('a div img')->attr('src-swap');
        $app['relatedapps'][] = $a;
        unset($a);
    }

    if ($type == 'app') {
        $container = 'application';
    } else
        $container = 'ebook';
    $alsobought = $data->find('.center-stack .lockup.small.' . $container);
    foreach ($alsobought as $ra) {
        $link = $ra->find('a.artwork-link')->attr('href');
        $link = parse_itunes_url($link);
        $a['trackid'] = $link['trackid'];
        $a['store'] = $link['store'];
        $a['type'] = $link['type'];
        $a['name'] = $ra->attr('aria-label');
        $a['artist']['name'] = $ra->attr('preview-artist');
        $a['image'] = $ra->find('a div img')->attr('src-swap');
        preg_match('/id([0-9]+)/', $ra->find('div ul li:eq(2) a')->attr('href'), $matches);
        $a['type'] = $type;
        if ($type == 'app') {
            $a['genre']['name'] = $ra->find('div ul li:eq(2) a')->text();
            $a['genre']['id'] = $matches[1];
        } else {
            $a['artist']['id'] = $matches[1];
        }
        $app['alsobought'][] = $a;
        unset($a);
    }
    $app['last_parse_itunes'] = $app;
    return $app;
}

?>