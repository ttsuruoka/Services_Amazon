--TEST--
Services_Amazon: doBatch() errors
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
