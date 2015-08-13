<?php
require 'classes/email.class.php';
require 'classes/database.class.php';
require 'classes/LiqPay.php';
$params = require 'params.php';
if (isset($_GET['data'])) {
    $data = json_decode($_GET['data']);
    $amount = $data->totalPrice;
    try {
        $db = new Database();
        $db->beginTransaction();
        $response = $db->insertClient($data->textMessage, $data->phoneNumber, $amount, $_GET['data']);
        if (!$response['state']) {
            die('Server error. Please contact to administrator.');
        }
        if ($data->paymentMethod == 'onlinePayment') { // online paid
            $publicKey = $params['liqpay']['publicKey'];
            $privateKey = $params['liqpay']['privateKey'];
            $lp = new LiqPay($publicKey, $privateKey);
            $url = $lp->cnb_form(array(
                'version' => '3',
                'amount' => $amount,
                'currency' => 'UAH',
                'description' => 'payment for order '.$response['id'].' for burgerjoint.com.ua',
                'server_url' => "{$params['main']['host']}scripts/server.php",
                'result_url' => "{$params['main']['host']}index.html#/pending",
                'order_id' => $response['id'],
            ));
            header("Location: {$url}");
        } else {
            $email = new Email();
            if ($email->sendEmail($data, true, $response['id'])) {
                $db->setAsPaid($response['id']);
                header("Location: ../index.html#/success");
            } else {
                header("Location: ../index.html#/failure");
            }
        }
        $db->commit();
    } catch(PDOException $e) {
        $email->sendEmail('Сталася помилка - '.$e->getMessage(), false);
        $db->rollback();
    }
} else {
    throw new InvalidArgumentException('Data not isset');
}
