<?php

header('Content-type: application/json');
session_start();
if (!isset($_SESSION['auth']) and !isset($_SESSION['username']) and $_SESSION['role'] > 1) {
    echo json_encode(['state' => false, 'msg' => 'No permission']);
    exit;
}

require_once '../control/init.php';

if ($_SESSION['role'] != 1) {
    echo json_encode(['state' => false, 'msg' => 'No permission']);
    exit;
}

$uid = $_POST['uid'];
$action = $_POST['action'];

if($action == 'ban'){
    $stm = $_DB->prepare("UPDATE users SET role = 9 WHERE uid = :uid");
    $stm->bindParam(":uid", $uid);
    $stm->execute();
}else{
    $stm = $_DB->prepare("UPDATE users SET role = role_prev WHERE uid = :uid");
    $stm->bindParam(":uid", $uid);
    $stm->execute();
}

echo json_encode(['state'=>1]); exit;