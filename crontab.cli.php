<?php

require_once 'init.php';

// delete old sessions every 300 secs
if (($API->CONFIG['CRONTAB'] == 'cli' && PHP_SAPI == 'cli') || ($API->CONFIG['CRONTAB'] == 'native' && ($API->CONFIG['TIME'] % 300 == 0))) {
    $API->DB->query("DELETE FROM sessions WHERE started<" . ($API->CONFIG['TIME'] - (60 * 60)));
    $API->DB->query("DELETE FROM push WHERE added<" . ($API->CONFIG['TIME'] - (14 * 86400)));
    $API->CACHE->clearGroupCache('top100');
    // update apps clicks

    $apps = $API->DB->query_return("SELECT trackid FROM apps WHERE clicks_synced<" . (TIME - 86400));

    //$total = count($apps);
    //$count = 0;

    if ($apps) {
        $start_timestamp['day'] = strtotime("today");
        $start_timestamp['week'] = strtotime("today -1 week");
        $start_timestamp['month'] = strtotime("today -1 month");
        $start_timestamp['year'] = strtotime("today -1 year");
        $start_timestamp['all'] = 0;

        foreach ($apps as $a) {

            //$count++;
            //$percent = round($count / $total, 7) * 100;
            $clicks_data = $API->DB->query_return("SELECT added,clicks FROM download_stats_archive WHERE trackid={$a['trackid']}");

            if ($clicks_data) {
                foreach ($clicks_data as $cd) {
                    foreach ($start_timestamp as $period => $st) {
                        if ($cd['added'] >= $st) {
                            $to_db['clicks_' . $period] = $to_db['clicks_' . $period] + $cd['clicks'];
                        }
                    }
                }
                $to_db['clicks_synced'] = time();
                $API->DB->query("UPDATE LOW_PRIORITY apps SET {$API->DB->build_update_query($to_db)} WHERE trackid={$a['trackid']}");
                unset($to_db);
                //print "$percent %  {$a['trackid']} OK\n";
                //ob_flush();
            }
        }
    }
}