<?php
if (file_exists('config-local.php')) {
    require_once 'config-local.php';
} else {
    define('ACCESS_KEY_ID', 'XXXXXXXXXXX');
    define('ASSOC_ID', 'XXXXX');
}
?>
