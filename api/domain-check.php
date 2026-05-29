<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

$name = $_GET['domain'] ?? '';

if(!$name){
    echo json_encode([]);
    exit;
}

$tlds = [

    'co.uk',
    'uk',
    'org.uk',
    'me.uk',
    'ltd.uk',
    'plc.uk',

    'com',
    'net',
    'org',
    'info',
    'biz'

];

$results = [];

foreach($tlds as $tld){

    $domain = $name . '.' . $tld;

    $available = checkDomain($domain);

    $results[] = [

        'domain' => $domain,
        'available' => $available

    ];

}

echo json_encode($results);

function checkDomain($domain)
{
    $url = "https://rdap.org/domain/" . urlencode($domain);

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    $response = curl_exec($ch);

    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    if ($status == 404) {
        return true;
    }

    if ($status == 200 && strpos($response, '"ldhName"') !== false) {
        return false;
    }
echo "<pre>";
echo "Domain: " . $domain . "\n";
echo "HTTP Status: " . $status . "\n";
echo "Response:\n";
echo substr($response,0,500);
echo "</pre>";
exit;
    return false;
}