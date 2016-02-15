<?php

require 'classes/email.class.php';
require 'classes/database.class.php';
require 'classes/LiqPay.php';
require 'classes/smsclient.class.php';
$params = require 'params.php';
$request = json_decode(file_get_contents("php://input"));
if (isset($request)) {
    if (empty($request->ordered)) {
        echo 'http://'.$_SERVER['HTTP_HOST'];die;
    }

    $amount = $request->totalPrice->sum;
    try {
        $db = new Database();
        $db->beginTransaction();
        $response = $db->insertClient($request->textMessage, $request->phoneNumber, $amount, json_encode($request));
        if (!$response['state']) {
            die('Server error. Please contact to administrator.');
        }
        if ($request->paymentMethod == 'onlinePayment') { // online paid
            $publicKey = $params['liqpay']['publicKey'];
            $privateKey = $params['liqpay']['privateKey'];
            $lp = new LiqPay($publicKey, $privateKey);
            $url = $lp->cnb_form(array(
                'version' => '3',
                'amount' => $amount,
//                'amount' => 1,
                'currency' => 'UAH',
                'description' => 'payment for order '.$response['id'].' for burgerjoint.com.ua',
                'server_url' => "http://{$_SERVER['HTTP_HOST']}/scripts/server.php",
                'result_url' => "http://{$_SERVER['HTTP_HOST']}/scripts/result.php?id={$response['id']}",
                'order_id' => $response['id'],
            ));
        } else {
            $sms = new SmsClient($params['SmsUkraine']['login'], $params['SmsUkraine']['password']);
            $email = new Email();
            if ($email->sendEmail($request, true, $response['id'])) {
                $db->setAsPaid($response['id']);
                $sms->sendSMS('BurgerJoint', $params['adminNumber'], 'Нове замовлення ' . $response['id']);
                $url = 'http://' . $_SERVER['HTTP_HOST'] . '/index.html#/success';
            } else {
                $url = 'http://' .$_SERVER['HTTP_HOST'] .'/index.html#/failure';
            }
        }
        $db->commit();
        echo $url; die;
    } catch(Exception $e) {
        $email->sendEmail('Сталася помилка - '.$e->getMessage(), false, $response['id']);
        $db->rollback();
        echo 'index.html#/failure'; die;
    }
} else {
    throw new InvalidArgumentException('Data not isset');
}
