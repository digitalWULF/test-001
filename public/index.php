<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
define('START', microtime());
define('ROOT', $_SERVER['DOCUMENT_ROOT'] . '/..');
include ROOT . '/Engine/core.php';