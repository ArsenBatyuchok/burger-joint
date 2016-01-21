<?php
require 'classes/LiqPay.php';
$params = require 'params.php';
$publicKey = $params['liqpay']['publicKey'];
$privateKey = $params['liqpay']['privateKey'];
$id = $_GET['id'];
$liqpay = new LiqPay($publicKey, $privateKey);
$res = $liqpay->api("payment/status", array(
    'version'       => '3',
    'order_id'      => $id,
));
if ($res->result != 'error') {
    header("Location: /index.html#/pending");
} else {
    header("Location: /index.html#/failure");
}

?>