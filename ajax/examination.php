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

        $uid = $_POST['uid'];
        $title = $_POST['examination_title'];
        $subject = $_POST['examination_subject'];
        $detail = $_POST['examination_detail'];

        $stm = $_DB->prepare('INSERT INTO examinations (examination_title,examination_subject,examination_detail,examination_owner) VALUES (:title, :subject, :detail, :owner)');
        $stm->bindParam(':title', $title, PDO::PARAM_STR);
        $stm->bindParam(':subject', $subject, PDO::PARAM_INT);
        $stm->bindParam(':detail', $detail, PDO::PARAM_STR);
        $stm->bindParam(':owner', $uid, PDO::PARAM_INT);
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

        if ($_SESSION['uid'] != $_POST['examination_owner']) {
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

        if ($_SESSION['uid'] != $row['examination_owner']) {
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
