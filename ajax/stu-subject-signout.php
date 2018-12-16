<?php
    header('Content-type: application/json');
    session_start();
    if(!isset($_SESSION['auth']) and !isset($_SESSION['username']) and $_SESSION['role'] < 3){
        echo json_encode(['state'=>false,'msg'=>'No permission']); exit;
    }
    require_once '../control/init.php';

    $uid = $_POST['uid'];
    $id = $_POST['subject_id'];

    $stm = $_DB->prepare("DELETE FROM student_subject WHERE uid = :uid AND subject_id = :subject_id LIMIT 1");
    $stm->bindParam(':uid', $uid, PDO::PARAM_STR);
    $stm->bindParam(':subject_id', $id, PDO::PARAM_STR);
    $stm->execute();
    
    __(json_encode(['state'=>1])); exit;
