<?php
    header('Content-type: application/json');
    session_start();
    if(!isset($_SESSION['auth']) and !isset($_SESSION['username']) and $_SESSION['role'] > 2){
        echo json_encode(['state'=>false,'msg'=>'No permission']); exit;
    }

    require_once '../control/init.php';

    if($_SESSION['role'] != 2){
        echo json_encode(['state'=>false,'msg'=>'No permission']); exit;
    }
    $arr = [];
    $i = 1;
    foreach ($_POST['qa_id'] as $id) {
        $stm = $_DB->prepare("UPDATE q_and_a SET qa_order = :i WHERE qa_id = :id");
        $stm->bindParam(':i', $i, PDO::PARAM_INT);
        $stm->bindParam(':id', $id, PDO::PARAM_INT);
        $stm->execute();
        $var = [$id=>$i];
        array_push($arr, $var);
        $i++;
    }

    echo json_encode($arr);
    exit;
