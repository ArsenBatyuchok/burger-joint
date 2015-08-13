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
//    $dataPost = 'eyJwYXltZW50X2lkIjo2NTIyMTg0NywidHJhbnNhY3Rpb25faWQiOjY1MjIxODQ3LCJzdGF0dXMiOiJ3YWl0X2FjY2VwdCIsInZlcnNpb24iOjMsInR5cGUiOiJidXkiLCJwdWJsaWNfa2V5IjoiaTM0MTEwODQ3MTUwIiwiYWNxX2lkIjo0MTQ5NjMsIm9yZGVyX2lkIjoiMjMiLCJsaXFwYXlfb3JkZXJfaWQiOiI2NzQwNTg1dTE0Mzg3NjAxMzk3NDMxOTAiLCJkZXNjcmlwdGlvbiI6ItCe0L/Qu9Cw0YLQsCDQt9Cw0LrQsNC30LAiLCJzZW5kZXJfcGhvbmUiOiIzODA2MzA2Njk2NTEiLCJhbW91bnQiOjAuMDEsImN1cnJlbmN5IjoiVUFIIiwic2VuZGVyX2NvbW1pc3Npb24iOjAuMCwicmVjZWl2ZXJfY29tbWlzc2lvbiI6MC4wLCJhZ2VudF9jb21taXNzaW9uIjowLjAsImFtb3VudF9kZWJpdCI6MC4wMSwiYW1vdW50X2NyZWRpdCI6MC4wMSwiY29tbWlzc2lvbl9kZWJpdCI6MC4wLCJjb21taXNzaW9uX2NyZWRpdCI6MC4wLCJjdXJyZW5jeV9kZWJpdCI6IlVBSCIsImN1cnJlbmN5X2NyZWRpdCI6IlVBSCIsInNlbmRlcl9ib251cyI6MC4wLCJhbW91bnRfYm9udXMiOjAuMH0=';
    $dataPost = json_decode(base64_decode( $_POST['data']));
//    $dataPost = json_decode(base64_decode( $dataPost));
    session_start();
    $data = json_decode($_SESSION['data']);
    unset($_SESSION['data']);
    $db = new Database();
    $client = $db->findClientById($dataPost->order_id);
    if (!$client || ($client['amount'] != $dataPost->amount)) {
        $sms->sendSMS('BurgerJoint', $data->phoneNumber, 'Ваше замовлення скасовано. Для детальної iнформацiї звертайтесь за телефоном +38 (067) 134 58 34');
        $email->sendEmail('Данi не спiвпадають'.$email->postDataToString($dataPost), false);
    } else {
        if (!$email->sendEmail($data)) {
            sleep(10);
            if (!$email->sendEmail($data)) {
                sleep(10);
                if (!$email->sendEmail($data)) {
                    $sms->sendSMS('BurgerJoint', '380671345834', 'Помилка вiдправки листа. Звернiться до администраторiв');
                }
            }
        }
        $sms->sendSMS('BurgerJoint', $data->phoneNumber, 'Дякуємо. Ваше замовлення прийнято. З будьякими питаннями звертайтесь за телефоном +38 (067) 134 58 34');
        $db->setAsPaid($dataPost->order_id);
    }
}