<?php
print 'success';
require 'classes/email.class.php';
require 'classes/database.class.php';

if (isset($_POST['data'])) {
    $db = new Database();
    $db->insertServer($_POST['data'], $_POST['signature']);
} else {
    $db = new Database();
    $db->insertServer('error', 'signature');
}
$db = new Database();
$db->insertServer('none', 'signature');