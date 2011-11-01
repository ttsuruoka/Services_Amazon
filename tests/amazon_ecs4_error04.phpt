--TEST--
Services_AmazonECS4: doBatch() errors
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
$amazon->setVersion('2006-03-08');

$shared = array();
$shared['SearchIndex'] = 'InvalidIndex';
$shared['ResponseGroup'] = 'ItemAttributes';
$param1 = array('ItemPage' => 1);
$param2 = array('ItemPage' => 2);
 
$result = $amazon->doBatch('ItemSearch', $shared, $param1, $param2);
if (PEAR::isError($result)) {
    foreach ($amazon->getErrors() as $error) {
        echo $error['Code'] . "\n";
    }
}

?>
--EXPECT--
AWS.InvalidEnumeratedParameter
AWS.MinimumParameterRequirement
AWS.InvalidEnumeratedParameter
AWS.MinimumParameterRequirement
