<?php
    header('Content-Type: application/json');

    $apiKey = '6f83461279da4b1e89b110e0cc474ae9';
    $baseUrl = 'https://newsapi.org/v2/everything?q=';

    $query = isset($_GET['query']) ? $_GET['query'] : '';

    $url = $baseUrl . urlencode($query) . '&apiKey=' . $apiKey;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $userAgent = 'NewsAggregator/1.0 PHP cURL/7.4';
    curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);

    $response = curl_exec($ch);

    curl_close($ch);
    echo $response;
?>
