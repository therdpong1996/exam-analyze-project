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
        $title = $_POST['subject_title'];
        $detail = $_POST['subject_detail'];

        $stm = $_DB->prepare('INSERT INTO subjects (subject_title,subject_detail,subject_owner) VALUES (:title, :detail, :owner)');
        $stm->bindParam(':title', $title, PDO::PARAM_STR);
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

        if ($_SESSION['uid'] != $_POST['subject_owner']) {
            echo json_encode(['state' => false, 'msg' => 'No permission']);
            exit;
        }

        $subject_id = $_POST['subject_id'];
        $title = $_POST['subject_title'];
        $detail = $_POST['subject_detail'];

        $stm = $_DB->prepare('UPDATE subjects SET subject_title = :title, subject_detail = :detail WHERE subject_id = :subject_id');
        $stm->bindParam(':title', $title, PDO::PARAM_STR);
        $stm->bindParam(':detail', $detail, PDO::PARAM_STR);
        $stm->bindParam(':subject_id', $subject_id, PDO::PARAM_INT);
        $stm->execute();

        echo json_encode(['state' => true, 'msg' => 'แก้ไข '.$title.' แล้ว']);
        exit;
    } elseif ($_POST['action'] == 'delete') {
        if ($_SESSION['role'] != 2) {
            echo json_encode(['state' => false, 'msg' => 'No permission']);
            exit;
        }

        $stm = $_DB->prepare('SELECT * FROM subjects WHERE subject_id = :subject_id LIMIT 1');
        $stm->bindParam(':subject_id', $_POST['subject_id'], PDO::PARAM_INT);
        $stm->execute();
        $row = $stm->fetch(PDO::FETCH_ASSOC);

        if ($_SESSION['uid'] != $row['subject_owner']) {
            echo json_encode(['state' => false, 'msg' => 'No permission']);
            exit;
        }

        $subject_id = $_POST['subject_id'];

        $stm = $_DB->prepare('DELETE FROM subjects WHERE subject_id = :subject_id');
        $stm->bindParam(':subject_id', $subject_id, PDO::PARAM_INT);
        $stm->execute();

        echo json_encode(['state' => true]);
        exit;
    }
