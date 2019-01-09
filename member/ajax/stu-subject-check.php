<?php
    header('Content-type: application/json');
    session_start();
    if(!isset($_SESSION['auth']) and !isset($_SESSION['username']) and $_SESSION['role'] < 3){
        echo json_encode(['state'=>false,'msg'=>'No permission']); exit;
    }
    require_once '../control/init.php';

    if (empty($_POST['code'])) {
        exit;
    }

    $code = $_POST['code'];
    $stm = $_DB->prepare("SELECT * FROM subjects WHERE subjects.subject_invite_code = :code LIMIT 1");
    $stm->bindParam(':code', $code, PDO::PARAM_STR);
    $stm->execute();
    $row = $stm->fetch(PDO::FETCH_ASSOC);

    $stm2 = $_DB->prepare("SELECT uid FROM subject_owner WHERE subject_id = :id");
    $stm2->bindParam(":id", $row['subject_id']);
    $stm2->execute();
    $teach = [];
    while ($urow = $stm2->fetch(PDO::FETCH_ASSOC)){
        $stm3 = $_DB->prepare("SELECT full_name FROM users WHERE uid = :uid");
        $stm3->bindParam(":uid", $urow['uid']);
        $stm3->execute();
        $userow = $stm3->fetch(PDO::FETCH_ASSOC);
        array_push($teach, $userow['full_name']);
    }

    if ($row['subject_id']) {
        $row['user'] = implode(', ', $teach);
        __(json_encode($row));
    }
    exit;

