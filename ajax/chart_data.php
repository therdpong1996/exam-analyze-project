<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-type: application/json');
    session_start();
    if (!isset($_SESSION['auth']) and !isset($_SESSION['username']) and $_SESSION['role'] > 2) {
        echo json_encode(['state' => false, 'msg' => 'No permission']);
        exit;
    }

    require_once '../control/init.php';

    $stm = $_DB->prepare("SELECT session_model,session_report FROM sessions WHERE session_id = :id LIMIT 1");
    $stm->bindParam(":id", $_POST['session_id']);
    $stm->execute();
    $row = $stm->fetch(PDO::FETCH_ASSOC);

    $row['session_model'] = str_replace(['data":"', ']"}'], ['data":', ']}'], $row['session_model']);
    $data['model'] = json_decode($row['session_model'], true);
    $data['report'] = json_decode($row['session_report'], true);

    echo json_encode($data);
    exit;