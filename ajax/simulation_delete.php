<?php
    header('Content-type: application/json');
    session_start();
    if (!isset($_SESSION['auth']) and !isset($_SESSION['username']) and $_SESSION['role'] > 2) {
        echo json_encode(['state' => false, 'msg' => 'No permission']);
        exit;
    }

    if ($_SESSION['role'] != 2) {
        echo json_encode(['state' => false, 'msg' => 'No permission']);
        exit;
    }

    require_once '../control/init.php';

    unset($_COOKIE['sim_number']);
    setcookie('sim_number', null, -1, '/');

    $uid = $_SESSION['uid'];
    $session_id = 0;
    $adaptable = $_POST['adaptable'];

    $token = md5('computerizedadaptivetesting' . $session_id);

    $post_data = 'session='.$session_id.'&userid='.$uid.'&adapt='.$adaptable.'&token='.$token;
    $url = $_G['webservice'] . 'resetscore/';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
