<?php

require 'vendor/autoload.php';

use Square\SquareClient;
use Square\LocationsApi;
use Square\Exceptions\ApiException;
use Square\Http\ApiResponse;
use Square\Models\ListLocationsResponse;
use Square\Environment;


$client = new SquareClient([
    'accessToken' => 'EAAAEAHxQKihcfAfAQN7UzP1ohEFoA9Yf0O9GXuv-zxZvnBb3IfB7XWK4bv4Q9Eu',
    'environment' => Environment::SANDBOX,
]);

try {
    $locationsApi = $client->getLocationsApi();
    $apiResponse = $locationsApi->listLocations();

    if ($apiResponse->isSuccess()) {
        $listLocationsResponse = $apiResponse->getResult();
        $locationsList = $listLocationsResponse->getLocations();
        foreach ($locationsList as $location) {
        print_r($location);
        }
    } else {
        print_r($apiResponse->getErrors());
    }
} catch (ApiException $e) {
    print_r("Recieved error while calling Square: " . $e->getMessage());
} 

$beginTime = '';
$endTime = '';
$sortOrder = '';
$cursor = '';
$locationId = '';
$total = 10;
$last4 = '';
$cardBrand = '';
$limit = 0;

$paymentsApi = $client->getPaymentsApi();

//$apiResponse = $paymentsApi->listPayments($beginTime, $endTime, $sortOrder, $cursor, $locationId, $total, $last4, $cardBrand, $limit);
$apiResponse = $paymentsApi->listPayments();


if ($apiResponse->isSuccess()) {
    $listPaymentsResponse = $apiResponse->getResult();
} else {
    $errors = $apiResponse->getErrors();
}

// Get more response info...
// $statusCode = $apiResponse->getStatusCode();
// $headers = $apiResponse->getHeaders();