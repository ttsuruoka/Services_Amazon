<?php
//
// Example of usage for Services_AmazonECS4
//
// This example uses the following functions:
// - CartAdd
// - CartClear
// - CartCreate
// - CartGet
// - CartModify
//

require_once 'config.php';
require_once 'PEAR.php';
require_once 'Services/AmazonECS4.php';

function report_error($msg)
{
    echo "<p><i>{$msg}</i><p></body></html>";
    exit();
}

function existsCart()
{
    return isset($_COOKIE['CartId']) ? true : false;
}

$php_self = htmlspecialchars($_SERVER['PHP_SELF']);

$amazon = new Services_AmazonECS4(ACCESS_KEY_ID, ASSOC_ID);

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
case 'add':
    $item = array('ASIN' => $_GET['ASIN'],
                  'Quantity' => $_GET['Quantity']);
    if (!existsCart()) {
        $result = $amazon->CartCreate($item);
        if (PEAR::isError($result)) {
            report_error($result->message);
        }
        setcookie('CartId', $result['CartId'], time() + 60*60*24);
        setcookie('HMAC', $result['HMAC'], time() + 60*60*24);
    } else {
        $result = $amazon->CartAdd($_COOKIE['CartId'], $_COOKIE['HMAC'], $item);
        if (PEAR::isError($result)) {
            report_error($result->message);
        }
    }
    break;

case 'modify':
    if (!existsCart()) {
        report_error('Invalid action');
    }
    $item = array('CartItemId' => $_GET['CartItemId']);
    if (isset($_GET['SaveForLater'])) {
        $item += array('Action' => 'SaveForLater');
    } else if (isset($_GET['MoveToCart'])) {
        $item += array('Action' => 'MoveToCart');
    } else {
        $item += array('Quantity' => $_GET['Quantity']);
    }
    $result = $amazon->CartModify($_COOKIE['CartId'], $_COOKIE['HMAC'], $item);
    if (PEAR::isError($result)) {
        die($result->message);
    }
    
    break;

case 'clear':
    if (!existsCart()) {
        report_error('Invalid action');
    }
    $result = $amazon->CartClear($_COOKIE['CartId'], $_COOKIE['HMAC']);
    break;

default:
    if (existsCart()) {
        $result = $amazon->CartGet($_COOKIE['CartId'], $_COOKIE['HMAC']);
        if (PEAR::isError($result)) {
            setcookie('CartId', null, 0);
            setcookie('HMAC', null, 0);
            report_error($result->message);
        }
    }
    break;
}

echo <<< EOT
<html>
<head>
    <meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
    <title>Services_AmazonECS4 example - Cart Operations</title>
</head>
<body>
<h1>Services_AmazonECS4 example - Cart Operations</h1>
<p>
<a href="http://docs.amazonwebservices.com/AWSEcommerceService/2006-03-08/PgUsingShoppingCartArticle.html" target="_blank">Using the Amazon E-Commerce Service Shopping Cart</a>
</p>
<form action="{$php_self}" method="get">
<table border="0">
<tr>
    <td>ASIN <input type="text" name="ASIN" size="20" /></td>
    <td>Quantity <input type="text" name="Quantity" size="3" value="1" /></td>
    <td><input type="submit" value="Add to cart" /></td>
</tr>
</table>
<input type="hidden" name="action" value="add" />
</form>
EOT;

// CartItems
echo <<< EOT
CartItems :<br />
<table border="1">
<tr><th>ASIN</th><th>Title</th><th>Price</th><th>Qty</th><th>Save</th><th></th></tr>
EOT;
$items = array();
if (isset($result['CartItems'])) {
    if (isset($result['CartItems']['CartItem']['CartItemId'])) {
        $items = array($result['CartItems']['CartItem']);
    } else {
        $items = $result['CartItems']['CartItem'];
    }
}
foreach ($items as $v) {
echo <<< EOT
    <tr>
        <form action="{$php_self}" method="get">
        <td>{$v['ASIN']}</td>
        <td>{$v['Title']}</td>
        <td>{$v['Price']['FormattedPrice']}</td>
        <td>
            <input type="text" size="3" name="Quantity" value="{$v['Quantity']}" />
            <input type="hidden" name="CartItemId" value="{$v['CartItemId']}" />
        </td>
        <td>
            <input type="checkbox" name="SaveForLater" value="save" />
        </td>
        <td>
            <input type="submit" value="Update" />
        </td>
        <input type="hidden" name="action" value="modify" />
        </form>
    </tr>
EOT;
}
echo <<< EOT
</table><br />
EOT;

// Saved Items
echo <<< EOT
Saved Items to buy later :<br />
<table border="1">
<tr><th>ASIN</th><th>Title</th><th>Price</th><th>Qty</th><th>Move</th><th></th></tr>
EOT;
$items = array();
if (isset($result['SavedForLaterItems'])) {
    if (isset($result['SavedForLaterItems']['SavedForLaterItem']['CartItemId'])) {
        $items = array($result['SavedForLaterItems']['SavedForLaterItem']);
    } else {
        $items = $result['SavedForLaterItems']['SavedForLaterItem'];
    }
}
foreach ($items as $v) {
echo <<< EOT
    <tr>
        <form action="{$php_self}" method="get">
        <td>{$v['ASIN']}</td>
        <td>{$v['Title']}</td>
        <td>{$v['Price']['FormattedPrice']}</td>
        <td>
            <input type="text" size="3" name="Quantity" value="{$v['Quantity']}" />
            <input type="hidden" name="CartItemId" value="{$v['CartItemId']}" />
        </td>
        <td>
            <input type="checkbox" name="MoveToCart" value="move" />
        </td>
        <td>
            <input type="submit" value="Update" />
        </td>
        <input type="hidden" name="action" value="modify" />
        </form>
    </tr>
EOT;
}
echo <<< EOT
</table><br />
EOT;

// Clear
if (isset($result['CartItems']) || isset($result['SavedForLaterItems'])) {
echo <<< EOT
<p><a href="{$php_self}?action=clear">Clear</a></p>
EOT;
}
    
// Purchase
if (isset($result['CartItems']) || isset($result['SavedForLaterItems'])) {
echo <<< EOT
<p><a href="{$result['PurchaseURL']}">Purchase</a></p>
EOT;
}

// Processing Time
echo '<p>Processing Time : ' . $amazon->getProcessingTime() . 'sec</p>';

// Result
echo '<p>Result :</p>';
if (isset($result)) {
    echo '<pre>';
    var_dump($result);
    echo '</pre>';
}

echo <<< EOT
</body>
</html>
EOT;

?>
