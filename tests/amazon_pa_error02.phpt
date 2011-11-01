--TEST--
Services_Amazon: Invalid Access Key ID
--SKIPIF--
<?php
if (!file_exists('config-local.php')) {
    print "Skip Missing config-local.php!";
}
if (!function_exists('hash_hmac') && !function_exists('mhash')) {
    print "Skip hash_hmac/mhash is required";
}
?>
--FILE--
<?php
require_once 'config.php';
require_once 'Services/Amazon.php';

// Set an invalid Access Key ID
$amazon = new Services_Amazon('InvalidAccessKeyID', SECRET_ACCESS_KEY);
$amazon->setVersion('2009-03-31');

$result = $amazon->ItemSearch('Books', array('Keywords' => 'PHP'));

if (PEAR::isError($result)) {
    echo $result->getMessage();
}

?>
--EXPECT--
Amazon returned invalid HTTP response code 403
