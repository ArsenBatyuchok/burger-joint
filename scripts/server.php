<?php
require 'classes/database.class.php';
session_start();
$data = json_encode($_SESSION['data']);

if (isset($_POST['data'])) {
    $db = new Database();
    $db->insertServer($_POST['data'], $_POST['signature']);
} else {
    $db = new Database();
    $db->insertServer('error', 'signature');
}
var_dump($_POST);
$db = new Database();
$db->insertServer('none', 'signature');
