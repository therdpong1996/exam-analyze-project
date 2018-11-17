<?php
    header('Content-type: application/json');
    session_start();
    require_once '../control/init.php';

    $username = $_POST['username'];
    $stm = $_DB->prepare("SELECT COUNT(uid) AS c FROM users WHERE username = :username LIMIT 1");
    $stm->bindParam(':username', $username, PDO::PARAM_STR);
    $stm->execute();

    $row = $stm->fetch(PDO::FETCH_ASSOC);

    if($row['c'] == 0){
        echo json_encode(['state'=>false]);
    }else{
        echo json_encode(['state'=>true]);
    }
    exit;

