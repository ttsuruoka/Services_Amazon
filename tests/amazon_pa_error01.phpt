--TEST--
Services_Amazon: AWS.InvalidEnumeratedParameter
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

$amazon = new Services_Amazon(ACCESS_KEY_ID, SECRET_ACCESS_KEY);
$amazon->setVersion('2009-03-31');

// Set an invalid SearchIndex
$result = $amazon->ItemSearch('FooBar', array('Keywords' => 'PHP'));

if (PEAR::isError($result)) {
    $error = $amazon->getError();
    echo $error['Code'];
}

?>
--EXPECT--
AWS.InvalidEnumeratedParameter
