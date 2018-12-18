<?php
    header('Content-type: application/json');
    session_start();
    if (!isset($_SESSION['auth']) and !isset($_SESSION['username']) and $_SESSION['role'] > 2) {
        echo json_encode(['state' => false, 'msg' => 'No permission']);
        exit;
    }

    require_once '../control/init.php';

    if ($_POST['action'] == 'add') {

        if ($_SESSION['role'] != 2) {
            echo json_encode(['state' => false, 'msg' => 'No permission']);
            exit;
        }

        $stmc2 = $_DB->prepare("SELECT uid FROM subject_owner WHERE subject_id = :id AND uid = :uid");
        $stmc2->bindParam(":id", $_POST['examination_subject']);
        $stmc2->bindParam(":uid", $_SESSION['uid']);
        $stmc2->execute();
        $rowc2 = $stmc2->fetch(PDO::FETCH_ASSOC);

        if (empty($rowc2['uid'])) {
            echo json_encode(['state' => false, 'msg' => 'No permission']);
            exit;
        }

        $uid = $_POST['uid'];
        $title = $_POST['examination_title'];
        $subject = $_POST['examination_subject'];
        $detail = $_POST['examination_detail'];

        $stm = $_DB->prepare('INSERT INTO examinations (examination_title,examination_subject,examination_detail) VALUES (:title, :subject, :detail)');
        $stm->bindParam(':title', $title, PDO::PARAM_STR);
        $stm->bindParam(':subject', $subject, PDO::PARAM_INT);
        $stm->bindParam(':detail', $detail, PDO::PARAM_STR);
        $stm->execute();
        $lastid = $_DB->lastInsertId();

        if ($lastid) {
            echo json_encode(['state' => true, 'msg' => $title.' ได้ถูกเพิ่มแล้ว']);
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
        $stmc2->bindParam(":id", $_POST['examination_subject']);
        $stmc2->bindParam(":uid", $_SESSION['uid']);
        $stmc2->execute();
        $rowc2 = $stmc2->fetch(PDO::FETCH_ASSOC);

        if (empty($rowc2['uid'])) {
            echo json_encode(['state' => false, 'msg' => 'No permission']);
            exit;
        }

        $examination_id = $_POST['examination_id'];
        $title = $_POST['examination_title'];
        $subject = $_POST['examination_subject'];
        $detail = $_POST['examination_detail'];

        $stm = $_DB->prepare('UPDATE examinations SET examination_title = :title, examination_subject = :subject, examination_detail = :detail WHERE examination_id = :examination_id');
        $stm->bindParam(':title', $title, PDO::PARAM_STR);
        $stm->bindParam(':subject', $subject, PDO::PARAM_INT);
        $stm->bindParam(':detail', $detail, PDO::PARAM_STR);
        $stm->bindParam(':examination_id', $examination_id, PDO::PARAM_INT);
        $stm->execute();

        echo json_encode(['state' => true, 'msg' => 'แก้ไข '.$title.' แล้ว']);
        exit;
    } elseif ($_POST['action'] == 'delete') {

        if ($_SESSION['role'] != 2) {
            echo json_encode(['state' => false, 'msg' => 'No permission']);
            exit;
        }

        $stm = $_DB->prepare('SELECT * FROM examinations WHERE examination_id = :examination_id LIMIT 1');
        $stm->bindParam(':examination_id', $_POST['examination_id'], PDO::PARAM_INT);
        $stm->execute();
        $row = $stm->fetch(PDO::FETCH_ASSOC);

        $stmc2 = $_DB->prepare("SELECT uid FROM subject_owner WHERE subject_id = :id AND uid = :uid");
        $stmc2->bindParam(":id", $row['examination_subject']);
        $stmc2->bindParam(":uid", $_SESSION['uid']);
        $stmc2->execute();
        $rowc2 = $stmc2->fetch(PDO::FETCH_ASSOC);

        if (empty($rowc2['uid'])) {
            echo json_encode(['state' => false, 'msg' => 'No permission']);
            exit;
        }

        $examination_id = $_POST['examination_id'];

        $stm = $_DB->prepare('DELETE FROM examinations WHERE examination_id = :examination_id');
        $stm->bindParam(':examination_id', $examination_id, PDO::PARAM_INT);
        $stm->execute();
        echo json_encode(['state' => true]);
        exit;
    }
