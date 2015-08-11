<?php
require 'classes/email.class.php';
require 'classes/database.class.php';
require 'classes/LiqPay.php';
$params = require 'params.php';
if (isset($_GET['data'])) {
    $data = json_decode($_GET['data']);
    $amount = $data->totalPrice;
//    $amount = 0.01;
    try {
        $db = new Database();
        $db->beginTransaction();
        $response = $db->insertClient($data->textMessage, $data->phoneNumber, $amount);
        if (!$response['state']) {
            throw new Exception('Client not saved');
        }

        if ($data->paymentMethod == 'onlinePayment') { // online paid
            session_start();
            $_SESSION['data'] = $_GET['data'];
            $publicKey = $params['liqpay']['publicKey'];
            $privateKey = $params['liqpay']['privateKey'];
            $lp = new LiqPay($publicKey, $privateKey);
            $url = $lp->cnb_form(array(
                'version' => '3',
                'amount' => $amount,
                'currency' => 'UAH',
                'description' => 'Оплата заказа',
                'server_url' => "{$params['main']['host']}scripts/server.php",
                'result_url' => "{$params['main']['host']}index.html#/success",
//                'server_url' => "http://image2015.hol.es/image.php",
//                'result_url' => "{$params['main']['host']}index.html#/success",
                'order_id' => $response['id'],
//                'sandbox' => true,
            ));
            header("Location: {$url}");
        } else {
            $email = new Email();
            if ($email->sendEmail($data)) {
                $db->setAsPaid($response['id']);
                header("Location: ../index.html#/success");
            } else {
                header("Location: ../index.html#/failure");
            }
        }
        $db->commit();
    } catch(PDOException $e) {
        $db->rollback();
    }
} else {
    throw new InvalidArgumentException('Data not isset');
}
