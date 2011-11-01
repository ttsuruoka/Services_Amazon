--TEST--
Services_Amazon: _buildUrl
--SKIPIF--
<?php
if (!function_exists('hash_hmac') && !function_exists('mhash')) {
    print "Skip hash_hmac/mhash is required";
}
?>
--FILE--
<?php
require_once 'Services/Amazon.php';

$access_key_id = '00000000000000000000'; 
$secret_access_key = '00000000000000000000';

$amazon = new Services_Amazon($access_key_id, $secret_access_key);

$amazon->setBaseUrl('http://webservices.amazon.com/onca/xml');
$amazon->setVersion('2009-01-06');
$amazon->setTimestamp(gmmktime(12, 0, 0, 1, 1, 2009)); // for test

$params = array(
    'Operation' => 'ItemLookup',
    'ItemId' => '0679722769',
    'ResponseGroup' => 'ItemAttributes,Offers,Images,Reviews',

);
echo $amazon->_buildUrl($params);

?>
--EXPECT--
http://webservices.amazon.com/onca/xml?AWSAccessKeyId=00000000000000000000&ItemId=0679722769&Operation=ItemLookup&ResponseGroup=ItemAttributes%2COffers%2CImages%2CReviews&Service=AWSECommerceService&Timestamp=2009-01-01T12%3A00%3A00Z&Version=2009-01-06&Signature=OW%2FgiuEZ7lZEaFQ534yQasspLxiqEFTprG0PmNni%2FxU%3D
