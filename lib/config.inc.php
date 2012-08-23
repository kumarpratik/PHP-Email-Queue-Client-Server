<?php

if (isset($_SERVER['argc'])) {
    define('CLI', true);
} else {
    define('CLI', false);
}


define("DB_DATABASE","php_mail_queue");
define("DB_USERNAME","php_mail_queue");
define("DB_PASSWORD","pa55w0rd");
define("DB_HOSTNAME","localhost");

include_once "Zend/Db.php";

$db = Zend_Db::factory('Mysqli', array(
    'host'     => DB_HOSTNAME,
    'username' => DB_USERNAME,
    'password' => DB_PASSWORD,
    'dbname'   => DB_DATABASE
));
