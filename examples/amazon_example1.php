<?php
//
// Example of usage for Services_Amazon
//

require_once 'config.php';
require_once 'PEAR.php';
require_once 'Services/Amazon.php';

function safestripslashes($value)
{
    return get_magic_quotes_gpc() ? stripslashes($value) : $value;
}

function report_error($msg)
{
    echo "<p><i>{$msg}</i><p></body></html>";
    exit();
}

// Display previous/next links
function disp_links($pages, $page)
{
    if ($pages > 1) {
        echo '<tr><td colspan="2">';
        if ($page > 1) {
            echo '<a href="' . htmlspecialchars($_SERVER['PHP_SELF'] .
                 '?mode=' . urlencode(safestripslashes($_GET['mode'])) .
                 '&keyword=' . urlencode(safestripslashes($_GET['keyword'])) .
                 '&page=' . ($page - 1)) .  '">&laquo; Previous Page</a> ';
        }
        echo 'Page ' . $page . ' of ' . $pages . ' ';
        if($page < $pages) {
            echo '<a href="' . htmlspecialchars($_SERVER['PHP_SELF'] .
                 '?mode=' . urlencode(safestripslashes($_GET['mode'])) .
                 '&keyword=' . urlencode(safestripslashes($_GET['keyword'])) .
                 '&page=' . ($page + 1)) .  '">Next Page &raquo;</a>';
        }
        echo '</td></tr>';
    }
}

if(Services_Amazon::getApiVersion() != 1) {
    echo 'This script was written to work with Services_Amazon 1 API';
    exit();
}

$php_self = htmlspecialchars($_SERVER['PHP_SELF']);
echo <<<EOT
<html>
<head>
    <meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
    <title>Services_Amazon example</title>
</head>
<body>
<form action="{$php_self}" method="get">
<table border="0">
<tr>
    <td>
    <select name="mode">
EOT;

$modes = Services_Amazon::getModes();
// $modes = array('baby' => 'Baby',
//                'books' => 'Books',
//                'classical' => 'Classical Music',
//                ....
foreach($modes as $k => $v) {
    echo '<option value="' . $k . ($k == $_GET['mode'] ? '" selected="selected"' : '') . '">' . htmlspecialchars($v) . '</option>';
}
echo '</select></td>';

$keyword = isset($_GET['keyword']) ? htmlspecialchars(safestripslashes($_GET['keyword'])) : '';
echo <<< EOT
    <td><input type="text" name="keyword" value="{$keyword}" /></td>
    <td><input type="hidden" name="page" value="1" /></td>
    <td><input type="submit" value="Search" /></td>
</tr>
</table>
</form>
EOT;

if (!$_GET) {
    echo '</body></html>';
    exit();
}

// Validate
if (empty($_GET['keyword'])) {
    report_error('Must search for something');
}
if (!is_numeric($_GET['page']) || $_GET['page'] < 1) {
    report_error('Invalid page number');
}
if (!isset($modes[$_GET['mode']])) {
    report_error('Invalid mode');
}

$amazon = &new Services_Amazon(ACCESS_KEY_ID, ASSOC_ID);

$products = $amazon->searchKeyword($_GET['keyword'], $_GET['mode'], $_GET['page']);

if (PEAR::isError($products)) {
    report_error($products->message);
}


echo '<table border="0">';

$pages = $products['pages'];
$page  = $products['page'];

disp_links($pages, $page);

// Display products
for($i = 0; $i < 10; $i++) {
    if (!isset($products[$i])) break;
    $product = $products[$i];
    $creator = '';
    if(is_array($product['authors'])) {
        $creator = 'by ' . implode(', ', $product['authors']);
    } elseif(is_array($product['artists'])) {
        $creator = 'by ' . implode(', ', $product['artists']);
    }
    
    $price = '';
    if($product['listprice'] != $product['ourprice']) {
        $price = '<strike>' . $product['listprice'] . '</strike> ' . $product['ourprice'];
    } else {
        $price = $product['listprice'];
    }

    echo <<<EOT
<tr>
    <td valign="top"><a href="{$product['url']}"><img src="{$product['imagesmall']}" border="0" alt="" /></a></td>
    <td valign="top">
    <b>{$product['name']}</b> $creator<br />
    Category: {$product['type']}<br />
    Release Date: {$product['release']}<br />
    Price: $price<br />
    Manufacturer: {$product['manufacturer']}<br />
    ASIN: {$product['asin']}
    </td>
</tr>
EOT;
}

disp_links($pages, $page);

echo '</table></body></html>';

?>
