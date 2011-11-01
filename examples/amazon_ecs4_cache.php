<?php
//
// Example of usage for Services_AmazonECS4
//

require_once 'config.php';
require_once 'PEAR.php';
require_once 'Services/AmazonECS4.php';
require_once 'Cache.php';

$amazon = new Services_AmazonECS4(ACCESS_KEY_ID);
$amazon->setCache('file', array('cache_dir' => 'cache/'));
$amazon->setCacheExpire(60); // 60 seconds = 1 min

$options = array();
$options['Keywords'] = 'php';
$options['ResponseGroup'] = 'Medium';
$result = $amazon->ItemSearch('Books', $options);

if (PEAR::isError($result)) {
    echo $result->message;
} else {
    echo $amazon->getProcessingTime() . 'seconds';
    echo '<pre>';
    var_dump($result);
    echo '</pre>';
}

?>
