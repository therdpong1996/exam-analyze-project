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

$stm = $_DB->prepare("DELETE FROM subject_owner WHERE subject_id = :id AND uid = :uid");
$stm->bindParam(":id", $_POST['subject']);
$stm->bindParam(":uid", $_POST['uid']);
$stm->execute();

echo json_encode(['state'=>1]);
exit;