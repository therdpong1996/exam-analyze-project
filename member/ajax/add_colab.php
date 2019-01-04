<?php

header('Content-type: application/json');
session_start();
if (!isset($_SESSION['auth']) and !isset($_SESSION['username']) and $_SESSION['role'] > 2) {
    echo json_encode(['state' => false, 'msg' => 'No permission']);
    exit;
}

require_once '../control/init.php';

if ($_SESSION['role'] != 2) {
    echo json_encode(['state' => false, 'msg' => 'No permission']);
    exit;
}

$stmc2 = $_DB->prepare("SELECT uid FROM subject_owner WHERE subject_id = :id AND uid = :uid");
$stmc2->bindParam(":id", $_POST['subject']);
$stmc2->bindParam(":uid", $_SESSION['uid']);
$stmc2->execute();
$rowc2 = $stmc2->fetch(PDO::FETCH_ASSOC);

if (empty($rowc2['uid'])) {
    echo json_encode(['state' => false, 'msg' => 'No permission']);
    exit;
}

$stmc3 = $_DB->prepare("SELECT uid FROM subject_owner WHERE subject_id = :id AND uid = :uid");
$stmc3->bindParam(":id", $_POST['subject']);
$stmc3->bindParam(":uid", $_POST['uid']);
$stmc3->execute();
$rowc3 = $stmc3->fetch(PDO::FETCH_ASSOC);

if (!empty($rowc3['uid'])) {
    echo json_encode(['state' => false, 'msg' => 'Already in subject']);
    exit;
}

$stm = $_DB->prepare("INSERT INTO subject_owner(subject_id, uid) VALUES (:sub, :uid)");
$stm->bindParam(":sub", $_POST['subject']);
$stm->bindParam(":uid", $_POST['uid']);
$stm->execute();

$ustm = $_DB->prepare("SELECT full_name FROM users WHERE uid = :uid");
$ustm->bindParam(":uid", $_POST['uid']);
$ustm->execute();
$user = $ustm->fetch(PDO::FETCH_ASSOC);


echo json_encode(['state'=>1, 'html'=>'<div class="row mb-2" id="user-colab-'.$_POST['uid'].'"><div class="col-10"><input type="text" disabled class="form-control form-control-sm" value="'.$user['full_name'].'"/></div><div class="col-2"><button id="del-col-'.$_POST['uid'].'" type="button" onclick="delete_colab('.$_POST['subject'].', '.$_POST['uid'].')" class="btn btn-sm btn-danger btn-block"><i class="fas fa-trash"></i></button></div></div>']);