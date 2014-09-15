<?php

//die('gateway disabled');

require_once 'init.php';

//AppStore/2.0 iOS/7.1 model/%@ build/11B554a (5; dt:94)
function get_itunes_info2($trackid, $type = 'app', $store = 'us', $no_recursion = false) {
// proxy https://itunes.apple.com

    if (!$store)
        $store = 'us';
    if (!$type)
        $type = 'app';
    $proxies = array('http://127.0.0.1:81', 'http://188.165.24.108', 'http://94.23.169.52:81');
//if ($_COOKIE['test']) var_dump("{$store}/{$type}/id{$trackid}/?l=en");
    $data = curl_request("https://itunes.apple.com/{$store}/{$type}/id{$trackid}/?l=en", 'AppStore/2.0 iOS/7.1 model/iPad4,1 build/11B554a (5; dt:94)'); //try_itunes_proxy("{$store}/{$type}/id{$trackid}/?l=en", $proxies);
//var_Dump($data);

    $app['type'] = $type;
    $app['trackid'] = $trackid;
    $app['store'] = $store;

    if ($type == 'book') {
        preg_match("#its.serverData=(.*?)</script>#si", $data, $matches);
        $data = json_decode($matches[1], true);
        $data = $data['pageData']['productData'];
        $data['_reviews_']['current'] = json_decode(curl_request($data['reviews-url'], 'AppStore/2.0 iOS/7.1 model/iPad4,1 build/11B554a (5; dt:94)'), true);
    } elseif ($type == 'app') {
        $data = json_decode($data, true);
        $data['_reviews']['current'] = json_decode(curl_request($data['reviewsUrlsData']['currentVersionUrl'], 'AppStore/2.0 iOS/7.1 model/iPad4,1 build/11B554a (5; dt:94)'), true);
        $data['_reviews']['all'] = json_decode(curl_request($data['reviewsUrlsData']['allVersionsUrl'], 'AppStore/2.0 iOS/7.1 model/iPad4,1 build/11B554a (5; dt:94)'), true);
        if ($data['customersAlsoBought']['childrenIds']) {
            foreach ($data['customersAlsoBought']['childrenIds'] as $cid) {
                if (!$no_recursion) {
                    $data['_alsobought'][$cid] = get_itunes_info2($cid, $type, $store);
                }
            }
        }
    }


    if (!$data) {

        return false;
    }
    return $data;
}

$trackid = $API->getval('trackid', 'int');

$store = $API->getval('store');
$type = $API->getval('type');

require_once 'itgw.inc.php';
if (!$store)
    $store = 'us';
if (!$type)
    $type = 'app';
print '<pre>';
var_dump(get_itunes_info2($trackid, $type, $store));
?>