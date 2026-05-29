<?php

header('Content-Type: application/json');

$name = $_GET['name'] ?? '';

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

function checkDomain($domain){

    $url =
    'https://rdap.org/domain/' .
    urlencode($domain);

    $ch = curl_init($url);

    curl_setopt(
        $ch,
        CURLOPT_RETURNTRANSFER,
        true
    );

    curl_setopt(
        $ch,
        CURLOPT_TIMEOUT,
        10
    );

    curl_exec($ch);

    $status =
    curl_getinfo(
        $ch,
        CURLINFO_HTTP_CODE
    );

    curl_close($ch);

    return $status == 404;
}