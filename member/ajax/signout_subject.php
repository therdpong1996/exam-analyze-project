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

    $subject = $_POST['subject_id'];

    $stm = $_DB->prepare("DELETE FROM subject_owner WHERE uid = :uid AND subject_id = :sid");
    $stm->bindParam(":uid", $_SESSION['uid']);
    $stm->bindParam(":sid", $subject);
    $stm->execute();

    echo json_encode(['state'=>1]);
    exit;

