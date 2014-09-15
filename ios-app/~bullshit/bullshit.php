<?php

require_once '../../init.php';

$id = $_GET["brickwallID"];

if($id != NULL)
{
    $cake_links = json_decode(curl_request('https://dev.appaddict.org/assets/org_appaddict_imperator/api/?brickwallID=' . $id), true);

    $cake_links['links'] = $cake_links;
    if ($cake_links['links']) {
        $count = 0;

        $skipcount = 0;
        foreach ($cake_links['links'] as $l) {

            $count++;
            if (!preg_match('#(filepup|ul\.to|uploaded\.net|thefilebay\.com|mega\.co\.nz|sendspace\.com|turbobit\.net)#si', $l['url'])) {
                $skipcount++;
                continue;
            }


            $check = curl_request('https://dev.appaddict.org/assets/org_appaddict_imperator/linksChecker/?link=' . urlencode($l['link']));

            if ($check == 'dead') {
                $skipcount++;
                continue;
            } else 
          {
            
            $to_links['version'] = htmlspecialchars($l['version']);
            $to_links['cracker'] = htmlspecialchars($l['cracker']);
            if ($to_links['cracker'] == 'not_given') {
                $to_links['cracker'] = 'Unknown';
            }else if ($to_links['cracker'] == 'AppCakeBot') {
                $to_links['cracker'] = 'Unknown';
            }

            $to_links['link'] = $l['url'];
            $uploader_id = $API->DB->query_row("SELECT id FROM accounts ORDER BY RAND() LIMIT 1");
            $to_links['uploader_id'] = $uploader_id['id'];
            $to_links['added'] = TIME;
            $to_links['trackid'] = $id;
            $to_links['state'] = 'accepted';
            $API->DB->query("INSERT INTO links {$API->DB->build_insert_query($to_links)}");            
           }
        }
       print "{$id} : $count inserted, $skipcount skipped\n";
    }
    
    
    ob_flush();
}