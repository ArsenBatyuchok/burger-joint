<?php
require 'classes/database.class.php';
require 'classes/email.class.php';
require 'classes/LiqPay.php';
require 'classes/smsclient.class.php';
$params = require 'params.php';
$email = new Email();
$sms = new SmsClient($params['SmsUkraine']['login'], $params['SmsUkraine']['password']);
$lp = new LiqPay($params['liqpay']['publicKey'], $params['liqpay']['privateKey']);

if (isset ($_POST['data'])) {
    $dataPost = json_decode(base64_decode( $_POST['data']));
    $db = new Database();
    $client = $db->findClientById($dataPost->order_id);
    $data = json_decode($client['jsonData']);
    if (!$client || ($client['amount'] != $dataPost->amount)) {
        $sms->sendSMS('BurgerJoint', $data->phoneNumber, 'Ваше замовлення скасовано. Для детальної iнформацiї звертайтесь за телефоном +38 (068) 235 50 29');
        $email->sendEmail('Данi не спiвпадають'.$email->postDataToString($dataPost), false);
    } else {
        if (!$email->sendEmail($data, true, $dataPost->order_id)) {
            sleep(10);
            if (!$email->sendEmail($data, true, $dataPost->order_id)) {
                sleep(10);
                if (!$email->sendEmail($data, true, $dataPost->order_id)) {
                    $sms->sendSMS('BurgerJoint', '380682355029', 'Помилка вiдправки листа. Телефон замовника '.$data->phoneNumber.'.');
                }
            }
        }
        $sms->sendSMS('BurgerJoint', $data->phoneNumber, 'Дякуємо. Ваше замовлення прийнято. З будь якими питаннями звертайтесь за телефоном +38 (068) 235 50 29');
        $db->setAsPaid($dataPost->order_id);
    }
}