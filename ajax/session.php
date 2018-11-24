<?php
    header('Content-type: application/json');
    session_start();
    if(!isset($_SESSION['auth']) and !isset($_SESSION['username']) and $_SESSION['role'] > 2){
        echo json_encode(['state'=>false,'msg'=>'No permission']); exit;
    }

    require_once '../control/init.php';

    if($_POST['action'] == 'add'){

        if($_SESSION['role'] != 2){
            echo json_encode(['state'=>false,'msg'=>'No permission']); exit;
        }

        $uid = $_POST['session_owner'];
        $exam = $_POST['session_exam'];
        $start = str_replace('/', '-', $_POST['session_start']).' '.$_POST['session_start_time'].':00';
        $end = str_replace('/', '-', $_POST['session_end']).' '.$_POST['session_end_time'].':00';
        $timeleft = $_POST['session_timeleft'];
        $password = $_POST['session_password'];
        $solve = (isset($_POST['session_solve'])?'1':'0');

        $stm = $_DB->prepare("INSERT INTO sessions (session_owner,session_exam,session_password,session_timeleft,session_start,session_end,session_solve) VALUES (:owner, :exam, :password, :timeleft, :start, :end, :solve)");
        $stm->bindParam(':owner', $uid, PDO::PARAM_INT);
        $stm->bindParam(':exam', $exam, PDO::PARAM_INT);
        $stm->bindParam(':start', $start, PDO::PARAM_STR);
        $stm->bindParam(':end', $end, PDO::PARAM_STR);
        $stm->bindParam(':timeleft', $timeleft, PDO::PARAM_STR);
        $stm->bindParam(':password', $password, PDO::PARAM_STR);
        $stm->bindParam(':solve', $solve, PDO::PARAM_INT);
        $stm->execute();
        $lastid = $_DB->lastInsertId();

        if($lastid){
            echo json_encode(['state'=>true, 'msg'=>'Session ได้ถูกเพิ่มแล้ว']);
        }else{
            echo json_encode(['state'=>false, 'msg'=>'Error MySQL Query']);
        }
        exit;

    }elseif($_POST['action'] == 'edit'){


        if($_SESSION['role'] != 2){
            echo json_encode(['state'=>false,'msg'=>'No permission']); exit;
        }

        if($_SESSION['uid'] != $_POST['session_owner']){
            echo json_encode(['state'=>false,'msg'=>'No permission']); exit;
        }

        $id = $_POST['session_id'];
        $exam = $_POST['session_exam'];
        $start = str_replace('/', '-', $_POST['session_start']).' '.$_POST['session_start_time'].':00';
        $end = str_replace('/', '-', $_POST['session_end']).' '.$_POST['session_end_time'].':00';
        $timeleft = $_POST['session_timeleft'];
        $password = $_POST['session_password'];
        $solve = (isset($_POST['session_solve'])?'1':'0');

        $stm = $_DB->prepare("UPDATE sessions SET session_exam = :exam, session_password = :password, session_timeleft = :timeleft, session_start = :start, session_end = :end, session_solve = :solve WHERE session_id = :session_id");
        $stm->bindParam(':exam', $exam, PDO::PARAM_INT);
        $stm->bindParam(':start', $start, PDO::PARAM_STR);
        $stm->bindParam(':end', $end, PDO::PARAM_STR);
        $stm->bindParam(':timeleft', $timeleft, PDO::PARAM_STR);
        $stm->bindParam(':password', $password, PDO::PARAM_STR);
        $stm->bindParam(':solve', $solve, PDO::PARAM_INT);
        $stm->bindParam(':session_id', $id, PDO::PARAM_INT);
        $stm->execute();

        echo json_encode(['state'=>true, 'msg'=>'แก้ไข Session แล้ว']);
        exit;

    }elseif($_POST['action'] == 'delete'){

        if($_SESSION['role'] != 2){
            echo json_encode(['state'=>false,'msg'=>'No permission']); exit;
        }

        $stm = $_DB->prepare("SELECT * FROM sessions WHERE session_id = :session_id LIMIT 1");
        $stm->bindParam(':session_id', $_POST['session_id'], PDO::PARAM_INT);
        $stm->execute();
        $row = $stm->fetch(PDO::FETCH_ASSOC);

        if($_SESSION['uid'] != $row['session_owner']){
            echo json_encode(['state'=>false,'msg'=>'No permission']); exit;
        }

        $session_id = $_POST['session_id'];

        $stm = $_DB->prepare("DELETE FROM sessions WHERE session_id = :session_id");
        $stm->bindParam(':session_id', $session_id, PDO::PARAM_INT);
        $stm->execute();

        echo json_encode(['state'=>true]);
        exit;

    }