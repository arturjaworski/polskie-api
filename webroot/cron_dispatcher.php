<?php
    set_time_limit(60*10);
    define('CRON_DISPATCHER', true);
    $_GET['url'] = $argv[1];
    require_once('index.php');
?>
