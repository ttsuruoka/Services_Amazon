--TEST--
Services_AmazonECS4: req#10687: set array for SimilarityLookup
--SKIPIF--
<?php
if (date('Ymd') >= 20090815) {
    print "Skip Amazon Associates Web Service 4.0 was deprecated on August 15, 2009";
}
if (!file_exists('config-local.php')) {
    print "Skip Missing config-local.php!";
}
?>
--FILE--
<?php
require_once 'config.php';
require_once 'Services/AmazonECS4.php';

$amazon = new Services_AmazonECS4(ACCESS_KEY_ID);

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
