<?php
if (file_exists('config-local.php')) {
    require_once 'config-local.php';
} else {
    define('ACCESS_KEY_ID', '0000000000000');
    define('SECRET_ACCESS_KEY', '0000000000000');
    define('ASSOC_ID', '000000');
}
?>
