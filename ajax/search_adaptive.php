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

if (empty($_POST['examid'])) {
    exit;
}

$stm = $_DB->prepare("SELECT adap_id,std_number,time_update FROM adaptive_table WHERE exam_id = :examid LIMIT 1");
$stm->bindParam(":examid", $_POST['examid']);
$stm->execute();
$row = $stm->fetch(PDO::FETCH_ASSOC);
$nrow = $stm->fetchColumn();


if ($nrow >= 1) {
    echo json_encode(['state' => true, 'msg' => 'พอข้อมูลการวิเคราะห์ของข้อสอบชุดนี้ ผู้ทดสอบจำนวน '.$row['std_number'].' คน อัพเดทเมื่อ '.$row['time_update'].'<br>ต้องการ Import หรือไม่ ?<br><button class="btn btn-success" onclick="addAdaptid('.$row['adap_id'].')">Import</button> <button class="btn btn-danger" onclick="addAdaptid(0)">ไม่ Import</button>']);
    exit;
}else{
    echo json_encode(['state' => false, 'msg' => 'No result']);
    exit;
}