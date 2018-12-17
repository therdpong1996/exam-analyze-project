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
    $number = $_POST['number'];
    $time_taken = $_POST['time_taken'];
    $temp = 1;
    $token = md5('computerizedadaptivetesting' . $session);

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

    ?>

    <tr>
        <td><?php __($solve['qa_id']); ?></td>
        <td><div style="width: 400px;overflow: hidden;text-overflow: ellipsis;"><?php __($solve['qa_question']); ?></div></td>
        <td><div style="width: 100px;overflow: hidden;text-overflow: ellipsis;"><?php __($solve['qa_choice_'.$answer]); ?></div></td>
        <td><?php __(($chk==1?'<span class="text-success">ถูกต้อง</span>':'<span class="text-danger">ผิด</span>')); ?></td>
    </tr>