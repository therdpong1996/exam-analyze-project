<?php
header('Content-type: application/json');
session_start();
require_once '../control/init.php';

$username = $_POST['username'];
$password = hash('sha256', $_POST['password']);

$stm = $_DB->prepare("SELECT uid,username,role_title,role FROM users JOIN users_role_title ON users.role = users_role_title.role_id WHERE (users.username = :username OR users.stu_id = :username) AND users.password = :password LIMIT 1");
$stm->bindParam(':username', $username, PDO::PARAM_STR);
$stm->bindParam(':password', $password, PDO::PARAM_STR);
$stm->execute();
$row = $stm->fetch(PDO::FETCH_ASSOC);

if ($row['role'] == 9) {
    echo json_encode(['state'=>false, 'msg'=>'ชื่อผู้ใช้นี้ถูกระงับการใช้งานชั่วคราว กรุณาติดต่อเจ้าหน้าที่']);
    exit;
}

if (!empty($row['uid'])) {
    $_SESSION['username'] = $row['username'];
    $_SESSION['auth'] = session_id();
    $_SESSION['role'] = $row['role'];
    $_SESSION['uid'] = $row['uid'];
    echo json_encode(['state'=>true, 'msg'=>'ระดับผู้ใช้งาน : '.$row['role_title']]);
}else{
    echo json_encode(['state'=>false, 'msg'=>'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง']);
}
exit;