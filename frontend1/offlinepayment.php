<?php
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
$amount = isset($_GET['price']) ? $_GET['price'] : "30000";
$productName = isset($_GET['productName']) ? $_GET['productName'] : "Default Product";
$orderId = time() ."";
$redirectUrl = "http://localhost/php_k5_training_minhtn-develop/php_k5_training_minhtn-develop/frontend1/checkout.php";
$ipnUrl = "https://webhook.site/b3088a6a-2d17-4f8d-a383-71389a6c600b";
$extraData = "";
// Set orderInfo based on the retrieved productName
$orderInfo = "Thanh toán qua MoMo - $productName";
// Remaining code remains unchanged
$partnerCode = $partnerCode;
$accessKey = $accessKey;
$secretKey = $secretKey;
$orderId = time();
$amount = $amount;
$redirectUrl = $redirectUrl;
$extraData = $extraData;
$requestId = time() . "";
$requestType = "captureWallet";
$rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
$signature = hash_hmac("sha256", $rawHash, $secretKey);
$data = array(
'partnerCode' => $partnerCode,
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
'signature' => $signature
);
$result = execPostRequest($endpoint, json_encode($data));
$jsonResult = json_decode($result, true);
if (!empty($_SESSION['cart'])) {
$_SESSION['cart']['payment_method'] = 'cheque';
}
header('Location: ' . $jsonResult['payUrl']);
?>