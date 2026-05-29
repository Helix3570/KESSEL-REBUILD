<?php

header('Content-Type: application/json');

$domain = $_GET['domain'] ?? '';

if (!$domain) {

    echo json_encode([
        'error' => 'No domain supplied'
    ]);

    exit;
}

$domain = strtolower(trim($domain));

$tld = pathinfo($domain, PATHINFO_EXTENSION);

$rdapServers = [

    'com'  => 'https://rdap.verisign.com/com/v1/domain/',
    'net'  => 'https://rdap.verisign.com/net/v1/domain/',
    'org'  => 'https://rdap.publicinterestregistry.org/rdap/org/domain/',
    'info' => 'https://rdap.identitydigital.services/rdap/domain/',
    'biz'  => 'https://rdap.identitydigital.services/rdap/domain/'

];

if (!isset($rdapServers[$tld])) {

    echo json_encode([
        'domain' => $domain,
        'available' => false,
        'message' => 'Unsupported TLD'
    ]);

    exit;
}

$url =
    $rdapServers[$tld] .
    urlencode($domain);

$headers =
    @get_headers($url);

$available = false;

if ($headers) {

    if (
        strpos($headers[0], '404') !== false
    ) {
        $available = true;
    }
}

echo json_encode([
    'domain' => $domain,
    'available' => $available
]);