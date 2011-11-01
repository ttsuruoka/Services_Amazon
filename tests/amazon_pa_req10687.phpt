--TEST--
Services_Amazon: req#10687: set array for SimilarityLookup
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

$item_ids = array('1590595521', '0596006810');
 
$result = $amazon->SimilarityLookup($item_ids);
if (PEAR::isError($result)) {
    die($result->message);
}

sleep(1);

$item_ids = '1590595521,0596006810';
 
$result = $amazon->SimilarityLookup($item_ids);
if (PEAR::isError($result)) {
    die($result->message);
}

sleep(1);

$result = $amazon->ItemLookup($item_ids);
if (PEAR::isError($result)) {
    die($result->message);
}

?>
--EXPECT--
