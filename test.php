<?php

// API base URL
$baseUrl = 'https://your-api-domain.com/api';

// Client credentials
$clientId = 'your-client-id';
$clientSecret = 'your-client-secret';

// Function to get an authentication token
function getAuthToken($baseUrl, $clientId, $clientSecret) {
    $url = $baseUrl . '/auth/token';

    $data = [
        'clientId' => $clientId,
        'clientSecret' => $clientSecret,
        'expireSeconds' => 3600
    ];

    $options = [
        'http' => [
            'header' => "Content-Type: application/json\r\n",
            'method' => 'POST',
            'content' => json_encode($data)
        ]
    ];

    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    if ($result === FALSE) {
        die('Error fetching auth token');
    }

    $response = json_decode($result, true);
    return $response['token'] ?? null;
}

// Function to get shipments
function getShipments($baseUrl, $token, $params = []) {
    $url = $baseUrl . '/shippingService/api/getShipments?' . http_build_query($params);

    $options = [
        'http' => [
            'header' => "Authorization: Bearer $token\r\n",
            'method' => 'GET'
        ]
    ];

    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    if ($result === FALSE) {
        die('Error fetching shipments');
    }

    return json_decode($result, true);
}

// Get the authentication token
$token = getAuthToken($baseUrl, $clientId, $clientSecret);

if ($token) {
    // Fetch shipments
    $shipments = getShipments($baseUrl, $token, [
        'dateFrom' => '2024-01-01',
        'dateTo' => '2024-12-31'
    ]);

    // Display the results
    echo "Shipments:\n";
    print_r($shipments);
} else {
    echo "Failed to retrieve token.";
}

?>
