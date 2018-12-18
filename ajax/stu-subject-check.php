<?php
    header('Content-type: application/json');
    session_start();
    if(!isset($_SESSION['auth']) and !isset($_SESSION['username']) and $_SESSION['role'] < 3){
        echo json_encode(['state'=>false,'msg'=>'No permission']); exit;
    }
    require_once '../control/init.php';

    $code = $_POST['code'];
    $stm = $_DB->prepare("SELECT * FROM subjects JOIN users ON subjects.subject_owner = users.uid WHERE subjects.subject_invite_code = :code LIMIT 1");
    $stm->bindParam(':code', $code, PDO::PARAM_STR);
    $stm->execute();
    $row = $stm->fetch(PDO::FETCH_ASSOC);

    $stm2 = $_DB->prepare("SELECT uid FROM subject_owner WHERE subject_id = :id");
    $stm2->bindParam(":id", $row['subject_id']);
    $stm2->execute();
    while ($urow = $stm2->fetch(PDO::FETCH_ASSOC)){
        $stm3 = $_DB->prepare("SELECT full_name FROM users WHERE uid = :uid");
        $stm3->bindParam(":uid", $urow['uid']);
        $stm3->execute();
        $user = $stm3->fetch(PDO::FETCH_ASSOC);
        $row['full_name'] .= $user['full_name'].', ';
    }
    $row['user'] = substr($row['full_name'], -1,2);
    __(json_encode($row));
    exit;

