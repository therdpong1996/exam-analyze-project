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

$stm = $_DB->prepare("SELECT adap_id,std_number,time_update,users.full_name FROM adaptive_table JOIN users ON adaptive_table.uid = users.uid WHERE adaptive_table.exam_id = :examid");
$stm->bindParam("examid", $_POST['examid']);
$stm->execute();
while ($row = $stm->fetch(PDO::FETCH_ASSOC)){ ?>

    <button class="btn btn-info btn-sm btn-block mb-2" type="button" onclick="addAdaptid(<?php __($row['adap_id']); ?>)">Import ข้อมูลการวิเคราะห์ของ <?php __($row['full_name']); ?> มีผู้ทดสอบจำนวน <?php __($row['std_number']); ?> คน อัพเดทล่าสุดเมื่อ <?php __($row['time_update']); ?></button>

<?php } ?>

    <button class="btn btn-warning btn-sm btn-block mb-3" type="button" onclick="addAdaptid(0)">ไม่ Import ข้อมูลการวิเคราะห์</button>