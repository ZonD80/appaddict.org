<?php
/*
if (PHP_SAPI != 'cli') {
    die('Must be run in CLI mode');
}

require_once 'init.php';

$body = file_get_contents('https://regmyudid.com/EMAILS/regmyudid_premium_email_aa.html');

$emails = $API->DB->query_return("SELECT email FROM accounts ORDER BY id ASC"); //file('emails.txt');

$c = 0;
foreach ($emails as $email) {
    $email = $email['email'];
    $c++;
    $API->send_mail($email, 'AppAddict & RegMyUDID', 'noreply@appaddict.org', 'RegMyUDID Premium and AppAddict for free', $body);

    print "$c       $email sent\n";
    ob_flush();
    //break;
}
print "done\n";*/
?>