<?php
require 'classes/email.class.php';
require 'classes/database.class.php';
require 'LiqPay.php';
$params = require 'params.php';

if (isset($_GET['data'])) {
    $data = json_decode($_GET['data']);
    if ($data->rememberOrder) {
        setcookie('Data', $_GET['data'], time()+31556926);
    } else {
        setcookie('Data', '', time()-3600);
    }
    $db = new Database();
    $response = $db->insertClient($data->textMessage, $data->phoneNumber);
    if (!$response['state']) {
        throw new Exception('Client not saved');
    }
//    $amount = $data->totalPrice;
    $amount = 0.01;

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
            'server_url' => 'burger.loc/scripts/server.php',
            'result_url' => 'burger.loc/scripts/server.php',
            'order_id' => $response['id'],
            'sandbox' => true,
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
} else {
   throw new InvalidArgumentException('Data not isset');
}

?>

