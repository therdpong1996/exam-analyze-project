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
    $answer = $_POST['answer'];
    $temp = 1;

    $stm = $_DB->prepare("SELECT * FROM q_and_a WHERE qa_id = :id AND qa_subject = :subject AND qa_exam = :exam LIMIT 1");
    $stm->bindParam(':id', $question, PDO::PARAM_INT);
    $stm->bindParam(':subject', $subject, PDO::PARAM_INT);
    $stm->bindParam(':exam', $examination, PDO::PARAM_INT);
    $stm->execute();
    $solve = $stm->fetch(PDO::FETCH_ASSOC);

    $ans_arr = explode(',', $solve['qa_choice_true']);

    if (in_array($answer, $ans_arr)) {
        $chk = 1;
    }else{
        $chk = 0;
    }


    $stm = $_DB->prepare("UPDATE answer_data SET answer = :answer, ans_check = :chk WHERE uid = :uid AND question = :question AND subject = :subject AND examination = :exam AND session = :session");
    $stm->bindParam(':answer', $answer, PDO::PARAM_INT);
    $stm->bindParam(':chk', $chk, PDO::PARAM_INT);
    $stm->bindParam(':uid', $uid, PDO::PARAM_INT);
    $stm->bindParam(':question', $question, PDO::PARAM_INT);
    $stm->bindParam(':subject', $subject, PDO::PARAM_INT);
    $stm->bindParam(':exam', $examination, PDO::PARAM_INT);
    $stm->bindParam(':session', $session, PDO::PARAM_INT);
    $stm->execute();