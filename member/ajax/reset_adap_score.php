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

    $uid = $_POST['uid'];
    $sc_id = $_POST['sc_id'];
    $session_id = $_POST['session_id'];

    $stm1 = $_DB->prepare('DELETE FROM adaptive_answer_data WHERE uid = :uid AND session = :session');
    $stm1->bindParam(':uid', $uid);
    $stm1->bindParam(':session', $session_id);
    $stm1->execute();

    $stm2 = $_DB->prepare('DELETE FROM adaptive_time_remaining WHERE uid = :uid AND session = :session');
    $stm2->bindParam(':uid', $uid);
    $stm2->bindParam(':session', $session_id);
    $stm2->execute();

    $stm3 = $_DB->prepare('DELETE FROM adaptive_session_score WHERE uid = :uid AND session_id = :session');
    $stm3->bindParam(':uid', $uid);
    $stm3->bindParam(':session', $session_id);
    $stm3->execute();

    $token = md5('computerizedadaptivetesting' . $session_id);

    $post_data = 'session='.$session_id.'&adapt='.$row['session_adap'].'&userid='.$uid.'&token='.$token;
    $url = $_G['webservice'] . 'resetscore/';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    echo json_encode(['state' => true, 'msg' => 'รีเซ็ตคะแนนเรียบร้อย']);
    exit;
