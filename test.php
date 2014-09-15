<?php

require_once 'init.php';

if (PHP_SAPI!='cli') {
    die('yaya');
}

$t = array(1,2,3,4,5,6,7);

var_dump(array_chunk($t, 2));

