--TEST--
Services_Amazon: req#5869: Provide access to the raw XML
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

$result = $amazon->ItemLookup('B000B649X2');
if (PEAR::isError($result)) {
    die($result->message);
}
$raw = $amazon->getRawResult();

echo substr($raw, 0, 57);
?>
--EXPECT--
<?xml version="1.0" encoding="UTF-8"?><ItemLookupResponse
