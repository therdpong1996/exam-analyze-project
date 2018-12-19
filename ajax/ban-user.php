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

    $stm2 = $_DB->prepare("SELECT role_title FROM users_role_title WHERE role_id = 9");
    $stm2->execute();
    $row = $stm2->fetch(PDO::FETCH_ASSOC);
    $text = $row['role_title'];
}else{
    $stm = $_DB->prepare("UPDATE users SET role = role_prev WHERE uid = :uid");
    $stm->bindParam(":uid", $uid);
    $stm->execute();

    $stm2 = $_DB->prepare("SELECT role_prev FROM users WHERE uid = :uid");
    $stm2->bindParam(":uid", $uid);
    $stm2->execute();
    $row1 = $stm2->fetch(PDO::FETCH_ASSOC);

    $stm3 = $_DB->prepare("SELECT role_title FROM users_role_title WHERE role_id = :id");
    $stm3->bindParam(":uid", $row1['role_prev']);
    $stm3->execute();
    $row = $stm3->fetch(PDO::FETCH_ASSOC);
    $text = $row['role_title'];
}

echo json_encode(['state'=>1, 'role_title'=>$text]); exit;