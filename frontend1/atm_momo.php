<?php
header('Content-type: text/html; charset=utf-8');


function execPostRequest($url, $data)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data))
    );
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    //execute post
    $result = curl_exec($ch);
    //close connection
    curl_close($ch);
    return $result;
}


$endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";

$partnerCode = 'MOMOBKUN20180529';
$accessKey = 'klm05TvNBzhg7h7j';
$secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
$orderInfo = "Thanh toán qua MoMo";
$amount = isset($_GET['amount']) ? $_GET['amount'] : 0;
$orderId = time() . "";
$redirectUrl = "https://webhook.site/b3088a6a-2d17-4f8d-a383-71389a6c600b";
$ipnUrl = "https://webhook.site/b3088a6a-2d17-4f8d-a383-71389a6c600b";
$extraData = "";

// Generate the request data

$requestId = time() . "";
$requestType = "payWithATM";
$extraData = isset($_POST["extraData"]) ? $_POST["extraData"] : "";

$rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
$signature = hash_hmac("sha256", $rawHash, $secretKey);
$data = array('partnerCode' => $partnerCode,
    'partnerName' => "Test",
    "storeId" => "MomoTestStore",
    'requestId' => $requestId,
    'amount' => $amount,
    'orderId' => $orderId,
    'orderInfo' => $orderInfo,
    'redirectUrl' => $redirectUrl,
    'ipnUrl' => $ipnUrl,
    'lang' => 'vi',
    'extraData' => $extraData,
    'requestType' => $requestType,
    'signature' => $signature);

$result = execPostRequest($endpoint, json_encode($data));
// Decode the JSON response
$jsonResult = json_decode($result, true);

// Check if the API response indicates a successful request
if ($jsonResult['resultCode'] == 0) {
    // Redirect to the MoMo payment URL if 'payUrl' exists in the response
    if (isset($jsonResult['payUrl'])) {
        header('Location: ' . $jsonResult['payUrl']);
        exit(); // Terminate the script after redirect
    } else {
        // Handle the case where 'payUrl' is not present in the API response
        echo "Error: 'payUrl' not found in MoMo API response.";
        // Optionally, you can log or perform additional error handling here.
    }
} else {
    // Handle the case where the MoMo API request is rejected
    echo "MoMo API Error: " . $jsonResult['message'];
    // Display a user-friendly error message based on the rejection reason
    if (strpos($jsonResult['message'], 'số tiền giao dịch nhỏ hơn số tiền tối thiểu') !== false) {
        echo "Please enter an amount greater than or equal to 10,000 VND.";
    } elseif (strpos($jsonResult['message'], 'lớn hơn số tiền tối đa') !== false) {
        echo "Please enter an amount less than or equal to 50,000,000 VND.";
    } else {
        // Handle other rejection reasons as needed
        echo "An error occurred during the MoMo API request.";
    }
    // Optionally, you can log or perform additional error handling here.
}