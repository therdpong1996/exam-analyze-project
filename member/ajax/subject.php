<?php
    header('Content-type: application/json');
    session_start();
    if (!isset($_SESSION['auth']) and !isset($_SESSION['username']) and $_SESSION['role'] > 2) {
        echo json_encode(['state' => false, 'msg' => 'No permission']);
        exit;
    }

    require_once '../control/init.php';

    if(!$_GS['init_subject']){
        echo json_encode(['state' => false, 'msg' => 'Function under construction']);
        exit;
    }

    if ($_POST['action'] == 'add') {

        if ($_SESSION['role'] != 2) {
            echo json_encode(['state' => false, 'msg' => 'No permission']);
            exit;
        }

        $uid = $_POST['uid'];
        $title = $_POST['subject_title'];
        $detail = $_POST['subject_detail'];
        $invite = generateRandomString(4);

        $stm = $_DB->prepare('INSERT INTO subjects (subject_title,subject_detail,subject_invite_code) VALUES (:title, :detail, :invite)');
        $stm->bindParam(':title', $title, PDO::PARAM_STR);
        $stm->bindParam(':detail', $detail, PDO::PARAM_STR);
        $stm->bindParam(':invite', $invite, PDO::PARAM_STR);
        $stm->execute();
        $lastid = $_DB->lastInsertId();

        $stm2 = $_DB->prepare('INSERT INTO subject_owner (subject_id,uid) VALUES (:id, :uid)');
        $stm2->bindParam(':id', $lastid, PDO::PARAM_INT);
        $stm2->bindParam(':uid', $_SESSION['uid'], PDO::PARAM_INT);
        $stm2->execute();

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
        $stmc2->bindParam(":id", $_POST['subject_id']);
        $stmc2->bindParam(":uid", $_SESSION['uid']);
        $stmc2->execute();
        $rowc2 = $stmc2->fetch(PDO::FETCH_ASSOC);

        if (empty($rowc2['uid'])) {
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

        $stmc2 = $_DB->prepare("SELECT uid FROM subject_owner WHERE subject_id = :id AND uid = :uid");
        $stmc2->bindParam(":id", $_POST['subject_id']);
        $stmc2->bindParam(":uid", $_SESSION['uid']);
        $stmc2->execute();
        $rowc2 = $stmc2->fetch(PDO::FETCH_ASSOC);

        if (empty($rowc2['uid'])) {
            echo json_encode(['state' => false, 'msg' => 'No permission']);
            exit;
        }

        $subject_id = $_POST['subject_id'];

        $stm = $_DB->prepare('DELETE FROM student_subject WHERE subject_id = :subject_id');
        $stm->bindParam(':subject_id', $subject_id, PDO::PARAM_INT);
        $stm->execute();

        $stm = $_DB->prepare('DELETE FROM subject_owner WHERE subject_id = :subject_id');
        $stm->bindParam(':subject_id', $subject_id, PDO::PARAM_INT);
        $stm->execute();

        $stm = $_DB->prepare('DELETE FROM subjects WHERE subject_id = :subject_id');
        $stm->bindParam(':subject_id', $subject_id, PDO::PARAM_INT);
        $stm->execute();

        echo json_encode(['state' => true]);
        exit;
    }
