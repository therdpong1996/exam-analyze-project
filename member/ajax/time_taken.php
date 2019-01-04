<?php
    header('Content-type: application/json');
    session_start();
    if(!isset($_SESSION['auth']) and !isset($_SESSION['username']) and $_SESSION['role'] < 3){
        echo json_encode(['state'=>false,'msg'=>'No permission']); exit;
    }

    require_once '../control/init.php';

    $uid = $_POST['uid'];
    $question = $_POST['question'];
    $subject = $_POST['subject'];
    $examination = $_POST['examination'];
    $session = $_POST['session'];
    $time = $_POST['timetake'];
    $answer = 0;
    $temp = 1;

    $stm = $_DB->prepare("UPDATE answer_data SET time_taken_s = time_taken_s + :time WHERE uid = :uid AND question = :question AND subject = :subject AND examination = :exam AND session = :session");
    $stm->bindParam(':time', $time, PDO::PARAM_INT);
    $stm->bindParam(':uid', $uid, PDO::PARAM_INT);
    $stm->bindParam(':question', $question, PDO::PARAM_INT);
    $stm->bindParam(':subject', $subject, PDO::PARAM_INT);
    $stm->bindParam(':exam', $examination, PDO::PARAM_INT);
    $stm->bindParam(':session', $session, PDO::PARAM_INT);
    $stm->execute();