<?php
//
// Example of usage for Services_AmazonECS4
//

require_once 'config.php';
require_once 'PEAR.php';
require_once 'Services/AmazonECS4.php';

function safestripslashes($value)
{
    return get_magic_quotes_gpc() ? stripslashes($value) : $value;
}

function report_error($msg)
{
    echo "<p><i>{$msg}</i><p></body></html>";
    exit();
}

echo <<<EOT
<html>
<head>
    <meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
    <title>Services_AmazonECS4 example - ItemSearch Operation</title>
</head>
<body>
<h1>Services_AmazonECS4 example - ItemSearch Operation</h1>
<p>
<a href="http://developer.amazonwebservices.com/connect/entry.jspa?externalID=163&categoryID=19" target="_blank">Docs: Amazon E-Commerce Service (API Version 2006-03-08)</a><br />
</p>
EOT;

if ($_GET) {
    foreach ($_GET as $k => $v) {
        if (!is_array($v)) {
            $cleaned[$k] = htmlspecialchars(safestripslashes($v));
        }
    }
}

$php_self = htmlspecialchars($_SERVER['PHP_SELF']);
echo <<< EOT
<form action="{$php_self}" method="get">
<table border="0">
<tr>
    <td>
    Locale
    <select name="locale">
EOT;

$locales = array('US', 'UK', 'DE', 'JP', 'FR', 'CA');
foreach($locales as $v) {
    echo '<option value="' . $v . ($v == $_GET['locale'] ? '" selected="selected"' : '') . '">' . $v . '</option>';
}

echo <<< EOT
    </select>
    <td>
</tr>
<tr>
    <td>
    Search Index <select name="SearchIndex">
EOT;

// The list of all available search indexes can be found on the
// "Amazon ECS API Reference - Search Index Values"
// (http://www.amazon.com/gp/aws/sdk/main.html/?s=AWSEcommerceService&v=2005-07-26&p=ApiReference/SearchIndexValues).
$search_indexes = array('Apparel', 'Baby', 'Beauty', 'Books', 'Classical', 'DigitalMusic',
                        'DVD', 'Electronics', 'ForeignBooks', 'GourmetFood', 'HealthPersonalCare',
                        'HomeGarden', 'Jewelry', 'Kitchen', 'Magazines', 'Merchants',
                        'Miscellaneous', 'Music', 'MusicalInstruments', 'MusicTracks',
                        'OfficeProducts', 'OutdoorLiving', 'PCHardware', 'PetSupplies',
                        'Photo', 'Restaurants', 'Software', 'SoftwareVideoGames',
                        'SportingGoods', 'Tools', 'Toys', 'VHS', 'Video', 'VideoGames',
                        'Wireless', 'WirelessAccessories', 'Blended');
foreach($search_indexes as $v) {
    echo '<option value="' . $v . ($v == $_GET['SearchIndex'] ? '" selected="selected"' : '') . '">' . $v . '</option>';
}

echo <<< EOT
    </select>
    </td>
</tr>
<tr>
    <td>Keywords <input type="text" name="Keywords" value="{$cleaned['Keywords']}" /></td>
</tr>
<tr>
    <td>
    <table border="0">
    <tr>
        <td>Title<br/><input type="text" name="Title" value="{$cleaned['Title']}" /></td>
        <td>Artist<br/><input type="text" name="Artist" value="{$cleaned['Artist']}" /></td>
        <td>Author<br/><input type="text" name="Author" value="{$cleaned['Author']}" /></td>
        <td>Actor<br/><input type="text" name="Actor" value="{$cleaned['Actor']}" /></td>
    </tr>
    <tr>
        <td>Director<br/><input type="text" name="Director" value="{$cleaned['Director']}" /></td>
        <td>Manufacturer<br/><input type="text" name="Manufacturer" value="{$cleaned['Manufacturer']}" /></td>
        <td>MusicLabel<br/><input type="text" name="MusicLabel" value="{$cleaned['MusicLabel']}" /></td>
        <td>Composer<br/><input type="text" name="Composer" value="{$cleaned['Composer']}" /></td>
    </tr>
    <tr>
        <td>Publisher<br/><input type="text" name="Publisher" value="{$cleaned['Publisher']}" /></td>
        <td>Brand<br/><input type="text" name="Brand" value="{$cleaned['Brand']}" /></td>
        <td>Conductor<br/><input type="text" name="Conductor" value="{$cleaned['Conductor']}" /></td>
        <td>Orchestra<br/><input type="text" name="Orchestra" value="{$cleaned['Orchestra']}" /></td>
    </tr>
    </table>
    </td>
</tr>
<tr>
    <td>Power <input type="text" name="Power" value="{$cleaned['Power']}" size="80" /></td>
</tr>
<tr>
    <td>BrowseNode <input type="text" name="BrowseNode" value="{$cleaned['BrowseNode']}" /></td>
</tr>
<tr>
    <td>AudienceRating <input type="text" name="AudienceRating" value="{$cleaned['AudienceRating']}" /></td>
</tr>
<tr>
    <td>
    TextStream <br/>
    <textarea name="TextStream" cols="64" rows="4">{$cleaned['TextStream']}</textarea>
    </td>
</tr>
<tr>
    <td>ItemPage <input type="text" name="ItemPage" value="{$cleaned['ItemPage']}" /></td>
</tr>
<tr>
    <td>Sort <input type="text" name="Sort" value="{$cleaned['Sort']}" /></td>
</tr>
<tr>
    <td>MinimumPrice <input type="text" name="MinimumPrice" value="{$cleaned['MinimumPrice']}" /></td>
</tr>
<tr>
    <td>MaximumPrice <input type="text" name="MaximumPrice" value="{$cleaned['MaximumPrice']}" /></td>
</tr>
<tr>
    <td>
    Condition
    <select name="Condition">
EOT;

$conditions = array('', 'New', 'All', 'Used', 'Refurbished', 'Collectible');

foreach ($conditions as $v) {
    echo '<option value="' . $v . ($v == $_GET['Condition'] ? '" selected="selected"' : '') . '">' . $v . '</option>';
}

echo <<< EOT
    </select>
    </td>
</tr>
<tr>
    <td>
    ResponseGroup<br/>
EOT;

$response_groups = array('Request', 'ItemIds', 'Small', 'Medium', 'Large', 'OfferFull', 'Offers',
                         'OfferSummary', 'Variations', 'VariationMinimum', 'VariationSummary',
                         'ItemAttributes', 'Tracks', 'Accessories', 'EditorialReview',
                         'SalesRank', 'BrowseNodes', 'Images', 'Similarities', 'ListmaniaLists',
                         'SearchBins', 'Subjects');
if (isset($_GET['ResponseGroup'])) {
    $checked_groups = is_array($_GET['ResponseGroup']) ? $_GET['ResponseGroup'] : array($_GET['ResponseGroup']);
} else {
    $checked_groups = array();
}

foreach ($response_groups as $v) {
    echo '<input type="checkbox" name="ResponseGroup[]" value="' . $v . '" ' . (in_array($v, $checked_groups) ? 'checked="checked"' : '') . '/>' . $v . ' ';
}

echo <<< EOT
    </td>
</tr>
<tr>
    <td><input type="submit" value="Search" /></td>
</tr>
</table>
</form>

EOT;

// examples
echo <<< EOT
<table border="0">
<tr>
    <td>Examples:</td>
    <td><a href="{$_SERVER['PHP_SELF']}?SearchIndex=Books&Keywords=php&ResponseGroup%5B%5D=Request&ResponseGroup%5B%5D=Small">Keywords</a></td>
    <td><a href="{$_SERVER['PHP_SELF']}?SearchIndex=Books&Keywords=php&ItemPage=2&ResponseGroup%5B%5D=Request&ResponseGroup%5B%5D=Medium">ItemPage</a></td>
    <td><a href="{$_SERVER['PHP_SELF']}?SearchIndex=Blended&Keywords=teletubbies&ResponseGroup%5B%5D=Request&ResponseGroup%5B%5D=Small">Blended</a></td>
    <td><a href="{$_SERVER['PHP_SELF']}?SearchIndex=Books&Power=%28subject%3A+sushi+or+pizza%29+and+pubdate%3A+after+2000&ResponseGroup%5B%5D=Request&ResponseGroup%5B%5D=ItemAttributes">Power</a></td>
    <td><a href="{$_SERVER['PHP_SELF']}?SearchIndex=Electronics&BrowseNode=595046&ResponseGroup%5B%5D=Request&ResponseGroup%5B%5D=Small&ResponseGroup%5B%5D=ItemAttributes">BrowseNode</a></td>
    <td><a href="{$_SERVER['PHP_SELF']}?SearchIndex=Electronics&BrowseNode=301187&Sort=salesrank&ResponseGroup%5B%5D=Request&ResponseGroup%5B%5D=ItemAttributes">Sort</a></td>
    <td><a href="{$_SERVER['PHP_SELF']}?SearchIndex=Books&Keywords=programming&MinimumPrice=5000&MaximumPrice=10000&ResponseGroup%5B%5D=Request&ResponseGroup%5B%5D=Small&ResponseGroup%5B%5D=OfferSummary">Price</a></td>
    <td><a href="{$_SERVER['PHP_SELF']}?SearchIndex=DVD&AudienceRating=PG-13&ResponseGroup%5B%5D=Request&ResponseGroup%5B%5D=ItemAttributes">AudienceRating</a></td>
</tr>
</table>

<hr/>
EOT;

if (!$_GET) {
    echo '</body></html>';
    exit();
}

$amazon = new Services_AmazonECS4(ACCESS_KEY_ID, ASSOC_ID);

if (isset($_GET['locale'])) {
    $result = $amazon->setLocale($_GET['locale']);
    if (PEAR::isError($result)) {
        report_error('Invalid locale');
    }
}

// ItemSearch
$search_index = $_GET['SearchIndex'];
$options = array();
if (isset($_GET['ResponseGroup'])) {
    $options['ResponseGroup'] = is_array($_GET['ResponseGroup']) ? implode(',', $_GET['ResponseGroup']) : $_GET['ResponseGroup'];
}
$accepted_options = array('Keywords', 'Title', 'Artist', 'Author', 'Actor', 'Director',
                          'Manufacturer', 'MusicLabel', 'Composer', 'Publisher', 'Brand',
                          'Conductor', 'Orchestra', 'Power', 'BrowseNode', 'AudienceRating',
                          'TextStream', 'ItemPage', 'Sort', 'MinimumPrice', 'MaximumPrice', 'Condition');
foreach ($_GET as $k => $v) {
    if (!empty($v) && in_array($k, $accepted_options)) {
        $options[$k] = $v;
    }
}

$result = $amazon->ItemSearch($search_index, $options);

$lasturl = $amazon->getLastUrl();
echo '<p>REST request:<br/>';
echo '<a href="' . htmlspecialchars($lasturl) . '" target="_blank">' .
      preg_replace('/&amp;/', '<br/>&amp;', htmlspecialchars($lasturl)) . '</a></p>';

if (PEAR::isError($result)) {
    echo '<p>Error:<br/>';
    echo htmlspecialchars($result->message);
    echo '</p>';
} else {
    echo '<p>Processing Time: ' . $amazon->getProcessingTime() . 'sec</p>';
    echo '<p>Result:</p>';
    echo '<pre>';
    var_dump($result);
    echo '</pre>';
}

echo '</body></html>';

?>
