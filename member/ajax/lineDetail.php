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

    $stm = $_DB->prepare('SELECT qa_id,qa_question FROM q_and_a WHERE qa_order = :order AND qa_exam = :exam LIMIT 1');
    $stm->bindParam(':order', $_POST['id'], PDO::PARAM_INT);
    $stm->bindParam(':exam', $_POST['exam'], PDO::PARAM_INT);
    $stm->execute();
    $row = $stm->fetch(PDO::FETCH_ASSOC);

    echo json_encode($row);