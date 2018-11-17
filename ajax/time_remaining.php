<?php
    header('Content-type: application/json');
    session_start();
    if(!isset($_SESSION['auth']) and !isset($_SESSION['username']) and $_SESSION['role'] < 3){
        echo json_encode(['state'=>false,'msg'=>'No permission']); exit;
    }

    require_once '../control/init.php';

    $uid = $_POST['uid'];
    $session = $_POST['session'];
    $time = $_POST['time_ree'];

    $stm = $_DB->prepare("UPDATE time_remaining SET time_remaining = :time_re WHERE uid = :uid AND session = :session");
    $stm->bindParam(':time_re', $time, PDO::PARAM_INT);
    $stm->bindParam(':uid', $uid, PDO::PARAM_INT);
    $stm->bindParam(':session', $session, PDO::PARAM_INT);
    $stm->execute();