<?php
// Declare the class
class GoogleUrlApi {

    // Constructor
    function GoogleURLAPI($key,$apiURL = 'https://www.googleapis.com/urlshortener/v1/url') {
        // Keep the API Url
        $this->apiURL = $apiURL.'?key='.$key;
    }

    // Shorten a URL
    function shorten($url) {
        // Send information along
        $response = $this->send($url);
        // Return the result
        return isset($response['id']) ? str_replace('http://','https://',$response['id']) : false;
    }

    // Expand a URL
    function expand($url) {
        // Send information along
        $response = $this->send($url,false);
        // Return the result
        return isset($response['longUrl']) ? $response['longUrl'] : false;
    }

    // Send information to Google
    function send($url,$shorten = true) {
        // Create cURL
        $ch = curl_init();
        // If we're shortening a URL...
        if($shorten) {
            curl_setopt($ch,CURLOPT_URL,$this->apiURL);
            curl_setopt($ch,CURLOPT_POST,1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 3);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
            curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode(array("longUrl"=>$url)));
            curl_setopt($ch,CURLOPT_HTTPHEADER,array("Content-Type: application/json"));
        }
        else {
            curl_setopt($ch,CURLOPT_URL,$this->apiURL.'&shortUrl='.$url);
        }
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        // Execute the post
        $result = curl_exec($ch);
        // Close the connection
        curl_close($ch);
        // Return the result
        return json_decode($result,true);
    }
}
?>