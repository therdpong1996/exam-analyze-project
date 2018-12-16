<?php
    header('Content-type: application/json');
    session_start();
    if(!isset($_SESSION['auth']) and !isset($_SESSION['username']) and $_SESSION['role'] < 3){
        echo json_encode(['state'=>false,'msg'=>'No permission']); exit;
    }
    require_once '../control/init.php';

    $code = $_POST['code'];

    $stm = $_DB->prepare("SELECT * FROM subjects JOIN users ON subjects.subject_owner = users.uid WHERE subjects.subject_invite_code = :code LIMIT 1");
    $stm->bindParam(':code', $code, PDO::PARAM_STR);
    $stm->execute();
    
    $row = $stm->fetch(PDO::FETCH_ASSOC);
    $row['user'] = $row['full_name'];
    __(json_encode($row));
    exit;

