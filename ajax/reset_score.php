<?php
    header('Content-type: application/json');
    session_start();
    if (!isset($_SESSION['auth']) and !isset($_SESSION['username']) and $_SESSION['role'] > 2) {
        echo json_encode(['state' => false, 'msg' => 'No permission']);
        exit;
    }

    require_once '../control/init.php';

    $uid = $_POST['uid'];
    $sc_id = $_POST['sc_id'];
    $session_id = $_POST['session_id'];

    $stm1 = $_DB->prepare('DELETE FROM answer_data WHERE uid = :uid AND session = :session');
    $stm1->bindParam(':uid', $uid);
    $stm1->bindParam(':session', $session_id);
    $stm1->execute();

    $stm2 = $_DB->prepare('DELETE FROM time_remaining WHERE uid = :uid AND session = :session');
    $stm2->bindParam(':uid', $uid);
    $stm2->bindParam(':session', $session_id);
    $stm2->execute();

    $stm3 = $_DB->prepare('DELETE FROM session_core WHERE uid = :uid AND session = :session');
    $stm3->bindParam(':uid', $uid);
    $stm3->bindParam(':session', $session_id);
    $stm3->execute();

    echo json_encode(['state' => true, 'msg' => 'รีเซ็ตคะแนนเรียบร้อย']);
    exit;
