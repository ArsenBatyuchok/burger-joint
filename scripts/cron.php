<?php
require 'classes/database.class.php';
require 'classes/LiqPay.php';
require 'classes/email.class.php';
require 'classes/smsclient.class.php';
$params = require 'params.php';
$publicKey = $params['liqpay']['publicKey'];
$privateKey = $params['liqpay']['privateKey'];
$liqpay = new LiqPay($publicKey, $privateKey);
$db = new Database();
$email = new Email();
$allInActiveOrder = $db->getInactiveOrders();
$sms = new SmsClient($params['SmsUkraine']['login'], $params['SmsUkraine']['password']);
foreach ($allInActiveOrder as $order) {
    $res = $liqpay->api("payment/status", array(
        'version'       => '3',
        'order_id'      => $order['clientId'],
    ));

    if ($res->result == 'error') {
        if ($db->sendErrorSms($order['clientId'])) {
            if ($db->setAsError($order['clientId'])) {
                $sms->sendSMS('BurgerJoint', $order['phoneNumber'], 'Ваше замовлення скасовано. Для детальної iнформацiї звертайтесь за телефоном +38 (068) 235 50 29');
            }
        }
    } elseif ($res->result == 'ok') {
        if ($db->setAsPaid($order['clientId'])) {
            $email->sendEmail(json_decode($order['jsonData']), true, $order['clientId']);
            $sms->sendSMS('BurgerJoint', $params['adminNumber'], 'Нове замовлення ' . $order['clientId']);
        }
    }
}
