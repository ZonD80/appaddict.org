<?php

if (PHP_SAPI != 'cli') {
    die('must be run from cli');
}

require_once 'init.php';

$trackids = $API->DB->query_return("SELECT trackid FROM apps ORDER BY added DESC");

$total = count($trackids);
print "Round 1 - starting to add links to {$total} apps..\n";
ob_flush();
$current = 0;
foreach ($trackids as $tid) {
    $current++;
    $percentage = round($current / $total, 5) * 100;

    $cake_links = json_decode(curl_request('https://dev.appaddict.org/assets/org_appaddict_imperator/api/?brickwallID=' . $tid['trackid']), true);
    //var_dump($cake_links);
    // dirty zorro fix
    $cake_links['links'] = $cake_links;
    if ($cake_links['links']) {
        $count = 0;

        $skipcount = 0;
        foreach ($cake_links['links'] as $l) {

            $count++;
            if (!preg_match('#(filepup|ul\.to|uploaded\.net|thefilebay\.com|mega\.co\.nz)#si', $l['url'])) {
                $skipcount++;
                continue;
            }
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
            $added = $API->DB->query_row("SELECT added FROM links ORDER BY RAND() LIMIT 1");
            $to_links['added'] = $added['added'];
            $to_links['trackid'] = $tid['trackid'];
            $to_links['state'] = 'accepted';
            //die(var_dump($to_links));
            $API->DB->query("INSERT INTO links {$API->DB->build_insert_query($to_links)}");
        }
    }

    print "{$percentage}% {$tid['trackid']} : $count inserted, $skipcount skipped\n";
    ob_flush();
}

print "ROUND 2 - links checking...\n\n";
$links = $API->DB->query_return("SELECT id,link FROM links ORDER BY id DESC");
$total = count($links);
print "$total total links...\n";

ob_flush();

$current = 0;
foreach ($links as $l) {
    $current++;

    $percentage = round($current / $total, 5) * 100;

    if (!preg_match('#(filepup|ul\.to|uploaded\.net|thefilebay\.com|mega\.co\.nz|turbobit\.net)#si', $l['link'])) {
        print "{$percentage}% {$l['link']} {$l['id']} skippping\n";
        ob_flush();
        continue;
    }
    $check = curl_request('https://dev.appaddict.org/assets/org_appaddict_imperator/linksChecker/?link=' . urlencode($l['link']));

    if ($check == 'dead') {
        $API->DB->query("UPDATE links SET state='dead' WHERE id={$l['id']}");
        print "$percentage %, {$l['link']} {$l['id']} is $check\n";
    } else {
        print "$percentage %, {$l['link']} {$l['id']} is $check\n";
    }
    ob_flush();
}

print "DONE \n\n\n";
