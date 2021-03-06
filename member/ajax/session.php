<?php
    header('Content-type: application/json');
    session_start();
    if (!isset($_SESSION['auth']) and !isset($_SESSION['username']) and $_SESSION['role'] > 2) {
        echo json_encode(['state' => false, 'msg' => 'No permission']);
        exit;
    }

    require_once '../control/init.php';

    if(!$_GS['init_session']){
        echo json_encode(['state' => false, 'msg' => 'Function under construction']);
        exit;
    }

    if ($_POST['action'] == 'add') {

        if ($_SESSION['role'] != 2) {
            echo json_encode(['state' => false, 'msg' => 'No permission']);
            exit;
        }

        $stmc1 = $_DB->prepare("SELECT examination_subject FROM examinations WHERE examination_id = :id");
        $stmc1->bindParam(":id", $_POST['session_exam']);
        $stmc1->execute();
        $rowc1 = $stmc1->fetch(PDO::FETCH_ASSOC);

        $stmc2 = $_DB->prepare("SELECT uid FROM subject_owner WHERE subject_id = :id AND uid = :uid");
        $stmc2->bindParam(":id", $rowc1['examination_subject']);
        $stmc2->bindParam(":uid", $_SESSION['uid']);
        $stmc2->execute();
        $rowc2 = $stmc2->fetch(PDO::FETCH_ASSOC);

        if (empty($rowc2['uid'])) {
            echo json_encode(['state' => false, 'msg' => 'No permission']);
            exit;
        }

        $active = (isset($_POST['session_active']) ? '1' : '0');
        $title = $_POST['session_title'];
        $exam = $_POST['session_exam'];
        $start = str_replace('/', '-', $_POST['session_start']).' '.$_POST['session_start_time'].':00';
        $end = str_replace('/', '-', $_POST['session_end']).' '.$_POST['session_end_time'].':00';
        $timeleft = $_POST['session_timeleft'];
        $password = $_POST['session_password'];
        $solve = (isset($_POST['session_solve']) ? '1' : '0');
        $number = (isset($_POST['session_adap_number']) ? $_POST['session_adap_number'] : '0');
        $import = $_POST['adaptimport'];
        $adap_active = (isset($_POST['session_adap']) ? $_POST['session_adap'] : '0');

        if($import != 0){
            $train = 1;
        }else{
            $train = 0;
        }

        $stm = $_DB->prepare('INSERT INTO sessions (session_active,session_exam,session_title,session_password,session_timeleft,session_start,session_end,session_solve,session_adap,session_adap_active,session_train,session_adap_number) VALUES (:active, :exam, :title, :password, :timeleft, :start, :end, :solve, :adap, :adap_active, :train, :number)');
        $stm->bindParam(':active', $active, PDO::PARAM_INT);
        $stm->bindParam(':exam', $exam, PDO::PARAM_INT);
        $stm->bindParam(':title', $title, PDO::PARAM_STR);
        $stm->bindParam(':start', $start, PDO::PARAM_STR);
        $stm->bindParam(':end', $end, PDO::PARAM_STR);
        $stm->bindParam(':timeleft', $timeleft, PDO::PARAM_STR);
        $stm->bindParam(':password', $password, PDO::PARAM_STR);
        $stm->bindParam(':solve', $solve, PDO::PARAM_INT);
        $stm->bindParam(':adap', $import, PDO::PARAM_INT);
        $stm->bindParam(':adap_active', $adap_active, PDO::PARAM_INT);
        $stm->bindParam(':train', $train, PDO::PARAM_INT);
        $stm->bindParam(':number', $number, PDO::PARAM_INT);
        $stm->execute();
        $lastid = $_DB->lastInsertId();

        addtotimeline('session', '2', $lastid, $rowc1['examination_subject']);

        if ($lastid) {
            echo json_encode(['state' => true, 'msg' => 'Session ได้ถูกเพิ่มแล้ว']);
        } else {
            echo json_encode(['state' => false, 'msg' => 'Error MySQL Query']);
        }
        exit;
    } elseif ($_POST['action'] == 'edit') {
        
        if ($_SESSION['role'] != 2) {
            echo json_encode(['state' => false, 'msg' => 'No permission']);
            exit;
        }

        $stmc1 = $_DB->prepare("SELECT examination_subject FROM examinations WHERE examination_id = :id");
        $stmc1->bindParam(":id", $_POST['session_exam']);
        $stmc1->execute();
        $rowc1 = $stmc1->fetch(PDO::FETCH_ASSOC);

        $stmc2 = $_DB->prepare("SELECT uid FROM subject_owner WHERE subject_id = :id AND uid = :uid");
        $stmc2->bindParam(":id", $rowc1['examination_subject']);
        $stmc2->bindParam(":uid", $_SESSION['uid']);
        $stmc2->execute();
        $rowc2 = $stmc2->fetch(PDO::FETCH_ASSOC);

        if (empty($rowc2['uid'])) {
            echo json_encode(['state' => false, 'msg' => 'No permission']);
            exit;
        }

        $id = $_POST['session_id'];
        $active = (isset($_POST['session_active']) ? '1' : '0');
        $title = $_POST['session_title'];
        $exam = $_POST['session_exam'];
        $start = str_replace('/', '-', $_POST['session_start']).' '.$_POST['session_start_time'].':00';
        $end = str_replace('/', '-', $_POST['session_end']).' '.$_POST['session_end_time'].':00';
        $timeleft = $_POST['session_timeleft'];
        $password = $_POST['session_password'];
        $solve = (isset($_POST['session_solve']) ? '1' : '0');
        $number = $_POST['session_adap_number'];
        $import = $_POST['adaptimport'];
        $adap_active = (isset($_POST['session_adap']) ? $_POST['session_adap'] : '0');
        if($import != 0){
            $train = 1;
        }else{
            $train = 0;
        }

        $stm = $_DB->prepare('UPDATE sessions SET session_active = :active, session_exam = :exam, session_title = :title, session_password = :password, session_timeleft = :timeleft, session_start = :start, session_end = :end, session_solve = :solve, session_adap = :adap, session_adap_active = :adap_active, session_adap_number = :number, session_train = :train WHERE session_id = :session_id');
        $stm->bindParam(':active', $active, PDO::PARAM_INT);
        $stm->bindParam(':exam', $exam, PDO::PARAM_INT);
        $stm->bindParam(':title', $title, PDO::PARAM_STR);
        $stm->bindParam(':start', $start, PDO::PARAM_STR);
        $stm->bindParam(':end', $end, PDO::PARAM_STR);
        $stm->bindParam(':timeleft', $timeleft, PDO::PARAM_STR);
        $stm->bindParam(':password', $password, PDO::PARAM_STR);
        $stm->bindParam(':solve', $solve, PDO::PARAM_INT);
        $stm->bindParam(':adap', $import, PDO::PARAM_INT);
        $stm->bindParam(':adap_active', $adap_active, PDO::PARAM_INT);
        $stm->bindParam(':number', $number, PDO::PARAM_INT);
        $stm->bindParam(':session_id', $id, PDO::PARAM_INT);
        $stm->bindParam(':train', $train, PDO::PARAM_INT);
        $stm->execute();

        echo json_encode(['state' => true, 'msg' => 'แก้ไข Session แล้ว']);
        exit;
        
    } elseif ($_POST['action'] == 'delete') {

        if ($_SESSION['role'] != 2) {
            echo json_encode(['state' => false, 'msg' => 'No permission']);
            exit;
        }

        $stm = $_DB->prepare('SELECT * FROM sessions WHERE session_id = :session_id LIMIT 1');
        $stm->bindParam(':session_id', $_POST['session_id'], PDO::PARAM_INT);
        $stm->execute();
        $row = $stm->fetch(PDO::FETCH_ASSOC);

        $stmc1 = $_DB->prepare("SELECT examination_subject FROM examinations WHERE examination_id = :id");
        $stmc1->bindParam(":id", $row['session_exam']);
        $stmc1->execute();
        $rowc1 = $stmc1->fetch(PDO::FETCH_ASSOC);

        $stmc2 = $_DB->prepare("SELECT uid FROM subject_owner WHERE subject_id = :id AND uid = :uid");
        $stmc2->bindParam(":id", $rowc1['examination_subject']);
        $stmc2->bindParam(":uid", $_SESSION['uid']);
        $stmc2->execute();
        $rowc2 = $stmc2->fetch(PDO::FETCH_ASSOC);

        if (empty($rowc2['uid'])) {
            echo json_encode(['state' => false, 'msg' => 'No permission']);
            exit;
        }

        $session_id = $_POST['session_id'];

        removefromtimeline('session', $session_id);

        $stm = $_DB->prepare('DELETE FROM sessions WHERE session_id = :session_id');
        $stm->bindParam(':session_id', $session_id, PDO::PARAM_INT);
        $stm->execute();

        echo json_encode(['state' => true]);
        exit;
    }
