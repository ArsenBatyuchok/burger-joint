<?php
require 'classes/database.class.php';
require 'classes/email.class.php';
$params = require 'params.php';
if (isset ($_POST['data'])) {
    $dataPost = json_decode(base64_decode($_POST['data']));
    session_start();
    $data = json_decode($_SESSION['data']);
    $db = new Database();
    $client = $db->findClientById($dataPost->order_id);
    if (!$client) {
        die('error');
    }
    if ($client['amount'] != $dataPost->amount) {
        die('error amount');
    }
    $email = new Email();
    if (!$email->sendEmail($data)) {
        die('error send message');
    } else {
        $db->setAsPaid($dataPost->order_id);
    }
}
