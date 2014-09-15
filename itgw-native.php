<?php
header('Content-type: application/json');

$native_data = json_decode(@file_get_contents("https://itunes.apple.com/lookup?id=404010395"),true);
        $app['artist']['id'] = $native_data['results']['artistId'];
        var_Dump($native_data);
$request = http_build_query($_GET);
print file_get_contents("https://itunes.apple.com/lookup?$request");
?>