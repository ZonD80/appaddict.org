<?php

if (!defined('INIT'))
    die('Direct access not allowed');

function get_itunes_info_native($trackid, $store = 'us') {
    if (!$store)
        $store = 'us';
    $return = curl_request("https://itunes.apple.com/lookup?id=$trackid&country=$store&lang=en_us");

    return json_decode($return, true);
}

?>