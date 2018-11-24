<?php
    header('Content-type: application/json');
    session_start();
    if(!isset($_SESSION['auth']) and !isset($_SESSION['username']) and $_SESSION['role'] < 3){
        echo json_encode(['state'=>false,'msg'=>'No permission']); exit;
    }

    require_once '../control/init.php';

    $uid = $_POST['uid'];
    $session = $_POST['session'];
    $time_update = time();

    $stm = $_DB->prepare("SELECT * FROM time_remaining WHERE uid = :uid AND session = :session");
    $stm->bindParam(':session', $session, PDO::PARAM_INT);
    $stm->bindParam(':uid', $uid, PDO::PARAM_INT);
    $stm->execute();
    $row = $stm->fetch(PDO::FETCH_ASSOC);

    $time_remaining = $row['time_remaining']-($time_update - $row['time_update1']);

    $stm = $_DB->prepare("UPDATE time_remaining SET time_update1 = :updatet1, time_remaining = :time_re WHERE uid = :uid AND session = :session");
    $stm->bindParam(':time_re', $time_remaining, PDO::PARAM_INT);
    $stm->bindParam(':uid', $uid, PDO::PARAM_INT);
    $stm->bindParam(':updatet1', $time_update, PDO::PARAM_INT);
    $stm->bindParam(':session', $session, PDO::PARAM_INT);
    $stm->execute();