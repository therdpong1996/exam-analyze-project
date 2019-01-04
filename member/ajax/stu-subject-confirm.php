<?php
    header('Content-type: application/json');
    session_start();
    if(!isset($_SESSION['auth']) and !isset($_SESSION['username']) and $_SESSION['role'] < 3){
        echo json_encode(['state'=>false,'msg'=>'No permission']); exit;
    }
    require_once '../control/init.php';

    $uid = $_POST['uid'];
    $id = $_POST['subject_id'];

    $stm = $_DB->prepare("SELECT * FROM student_subject WHERE uid = :uid AND subject_id = :subject_id LIMIT 1");
    $stm->bindParam(':uid', $uid, PDO::PARAM_STR);
    $stm->bindParam(':subject_id', $id, PDO::PARAM_STR);
    $stm->execute();
    $row = $stm->fetch(PDO::FETCH_ASSOC);
    if ($row['uid']) {
        __(json_encode(['state'=>1])); exit;
    }else{
        $stm = $_DB->prepare("INSERT INTO student_subject(subject_id,uid) VALUES (:id, :uid)");
        $stm->bindParam(':uid', $uid, PDO::PARAM_STR);
        $stm->bindParam(':id', $id, PDO::PARAM_STR);
        $stm->execute();
        __(json_encode(['state'=>1])); exit;
    }

