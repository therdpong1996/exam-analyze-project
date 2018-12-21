<?php
    header('Content-type: application/json');
    session_start();
    if (!isset($_SESSION['auth']) and !isset($_SESSION['username']) and $_SESSION['role'] > 2) {
        echo json_encode(['state' => false, 'msg' => 'No permission']);
        exit;
    }

    require_once '../control/init.php';

    if(!$_GS['init_exam']){
        echo json_encode(['state' => false, 'msg' => 'Function under construction']);
        exit;
    }

    if ($_POST['action'] == 'add') {
        if ($_SESSION['role'] != 2) {
            echo json_encode(['state' => false, 'msg' => 'No permission']);
            exit;
        }

        $stmc2 = $_DB->prepare("SELECT uid FROM subject_owner WHERE subject_id = :id AND uid = :uid");
        $stmc2->bindParam(":id", $_POST['qa_subject']);
        $stmc2->bindParam(":uid", $_SESSION['uid']);
        $stmc2->execute();
        $rowc2 = $stmc2->fetch(PDO::FETCH_ASSOC);

        if (empty($rowc2['uid'])) {
            echo json_encode(['state' => false, 'msg' => 'No permission']);
            exit;
        }

        $subject = $_POST['qa_subject'];
        $exam = $_POST['qa_exam'];
        $question = $_POST['question'];
        $choice_1 = $_POST['choice_1'];
        $choice_2 = $_POST['choice_2'];
        $choice_3 = $_POST['choice_3'];
        $choice_4 = $_POST['choice_4'];
        $true = implode(',', $_POST['true']);

        if (empty($true)) {
            echo json_encode(['state' => false, 'msg' => 'กรุณาเลือกคำตอบที่ถูกต้อง']);
            exit;
        }

        $stm = $_DB->prepare('SELECT COUNT(qa_id) AS c FROM q_and_a WHERE qa_subject = :subj AND qa_exam = :exam');
        $stm->bindParam(':subj', $subject, PDO::PARAM_INT);
        $stm->bindParam(':exam', $exam, PDO::PARAM_INT);
        $stm->execute();
        $order = $stm->fetch(PDO::FETCH_ASSOC);
        $order = $order['c'] + 1;

        $stm = $_DB->prepare('INSERT INTO q_and_a (qa_subject,qa_exam,qa_question,qa_choice_1,qa_choice_2,qa_choice_3,qa_choice_4,qa_choice_true,qa_order) VALUES (:subject, :exam, :question, :ch1, :ch2, :ch3, :ch4, :true, :order)');
        $stm->bindParam(':subject', $subject, PDO::PARAM_INT);
        $stm->bindParam(':exam', $exam, PDO::PARAM_INT);
        $stm->bindParam(':question', $question, PDO::PARAM_STR);
        $stm->bindParam(':ch1', $choice_1, PDO::PARAM_STR);
        $stm->bindParam(':ch2', $choice_2, PDO::PARAM_STR);
        $stm->bindParam(':ch3', $choice_3, PDO::PARAM_STR);
        $stm->bindParam(':ch4', $choice_4, PDO::PARAM_STR);
        $stm->bindParam(':true', $true, PDO::PARAM_STR);
        $stm->bindParam(':order', $order, PDO::PARAM_INT);
        $stm->execute();
        $lastid = $_DB->lastInsertId();

        if ($lastid) {
            $stm = $_DB->prepare("UPDATE examinations SET examination_newex = 1 WHERE examination_id = :id");
            $stm->bindParam(":id", $exam);
            $stm->execute();
            echo json_encode(['state' => true, 'msg' => 'คำถามได้ถูกเพิ่มแล้ว']);
        } else {
            echo json_encode(['state' => false, 'msg' => 'Error MySQL Query']);
        }
        exit;
    } elseif ($_POST['action'] == 'edit') {
        if ($_SESSION['role'] != 2) {
            echo json_encode(['state' => false, 'msg' => 'No permission']);
            exit;
        }

        $stmc2 = $_DB->prepare("SELECT uid FROM subject_owner WHERE subject_id = :id AND uid = :uid");
        $stmc2->bindParam(":id", $_POST['qa_subject']);
        $stmc2->bindParam(":uid", $_SESSION['uid']);
        $stmc2->execute();
        $rowc2 = $stmc2->fetch(PDO::FETCH_ASSOC);

        if (empty($rowc2['uid'])) {
            echo json_encode(['state' => false, 'msg' => 'No permission']);
            exit;
        }

        $id = $_POST['qa_id'];
        $subject = $_POST['qa_subject'];
        $exam = $_POST['qa_exam'];
        $question = $_POST['question'];
        $choice_1 = $_POST['choice_1'];
        $choice_2 = $_POST['choice_2'];
        $choice_3 = $_POST['choice_3'];
        $choice_4 = $_POST['choice_4'];
        $true = implode(',', $_POST['true']);

        $stm = $_DB->prepare('UPDATE q_and_a SET qa_subject = :subject, qa_exam = :exam, qa_question = :question, qa_choice_1 = :ch1, qa_choice_2 = :ch2, qa_choice_3 = :ch3, qa_choice_4 = :ch4, qa_choice_true = :true WHERE qa_id = :id');
        $stm->bindParam(':subject', $subject, PDO::PARAM_INT);
        $stm->bindParam(':exam', $exam, PDO::PARAM_INT);
        $stm->bindParam(':question', $question, PDO::PARAM_STR);
        $stm->bindParam(':ch1', $choice_1, PDO::PARAM_STR);
        $stm->bindParam(':ch2', $choice_2, PDO::PARAM_STR);
        $stm->bindParam(':ch3', $choice_3, PDO::PARAM_STR);
        $stm->bindParam(':ch4', $choice_4, PDO::PARAM_STR);
        $stm->bindParam(':true', $true, PDO::PARAM_STR);
        $stm->bindParam(':id', $id, PDO::PARAM_INT);
        $stm->execute();

        $stm2 = $_DB->prepare("UPDATE examinations SET examination_newex = 1 WHERE examination_id = :id");
        $stm2->bindParam(":id", $exam);
        $stm2->execute();

        echo json_encode(['state' => true, 'msg' => 'แก้ไขคำถามเรียบร้อย']);
        exit;
    } elseif ($_POST['action'] == 'delete') {

        if ($_SESSION['role'] != 2) {
            echo json_encode(['state' => false, 'msg' => 'No permission']);
            exit;
        }

        $stm = $_DB->prepare('SELECT qa_exam FROM q_and_a WHERE qa_id = :qa_id LIMIT 1');
        $stm->bindParam(':qa_id', $_POST['qa_id'], PDO::PARAM_INT);
        $stm->execute();
        $row1 = $stm->fetch(PDO::FETCH_ASSOC);

        $stm = $_DB->prepare('SELECT * FROM examinations WHERE examination_id = :examination_id LIMIT 1');
        $stm->bindParam(':examination_id', $row1['qa_exam'], PDO::PARAM_INT);
        $stm->execute();
        $row2 = $stm->fetch(PDO::FETCH_ASSOC);

        $stmc2 = $_DB->prepare("SELECT uid FROM subject_owner WHERE subject_id = :id AND uid = :uid");
        $stmc2->bindParam(":id", $row2['examination_subject']);
        $stmc2->bindParam(":uid", $_SESSION['uid']);
        $stmc2->execute();
        $rowc2 = $stmc2->fetch(PDO::FETCH_ASSOC);

        if (empty($rowc2['uid'])) {
            echo json_encode(['state' => false, 'msg' => 'No permission']);
            exit;
        }

        $stm3 = $_DB->prepare("UPDATE examinations SET examination_newex = 1 WHERE examination_id = :id");
        $stm3->bindParam(":id", $exam);
        $stm3->execute();

        $qa_id = $_POST['qa_id'];
        $stm = $_DB->prepare('UPDATE q_and_a SET qa_delete = 1 WHERE qa_id = :qa_id');
        $stm->bindParam(':qa_id', $qa_id, PDO::PARAM_INT);
        $stm->execute();
        echo json_encode(['state' => true]);
        exit;
    }
