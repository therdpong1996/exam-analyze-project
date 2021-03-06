<?php
    header('Content-type: application/json');
    session_start();
    if (!isset($_SESSION['auth']) and !isset($_SESSION['username']) and $_SESSION['role'] > 2) {
        echo json_encode(['state' => false, 'msg' => 'คุณไม่มีสิทธิ์ดำเนินการส่วนนี้ (ER000)']);
        exit;
    }

    require_once '../control/init.php';

    if(!$_GS['init_subject']){
        echo json_encode(['state' => false, 'msg' => 'Function under construction']);
        exit;
    }

    if ($_POST['action'] == 'add') {

        if ($_SESSION['role'] != 2) {
            echo json_encode(['state' => false, 'msg' => 'คุณไม่มีสิทธิ์ดำเนินการส่วนนี้ (ER004)']);
            exit;
        }

        $stmc2 = $_DB->prepare("SELECT uid FROM subject_owner WHERE subject_id = :id AND uid = :uid");
        $stmc2->bindParam(":id", $_POST['article_subject']);
        $stmc2->bindParam(":uid", $_SESSION['uid']);
        $stmc2->execute();
        $rowc2 = $stmc2->fetch(PDO::FETCH_ASSOC);

        if (empty($rowc2['uid'])) {
            echo json_encode(['state' => false, 'msg' => 'คุณไม่มีสิทธิ์ดำเนินการส่วนนี้ (ER001)']);
            exit;
        }

        $uid = $_POST['uid'];
        $subject = $_POST['article_subject'];
        $title = $_POST['article_title'];
        $content = $_POST['article_content'];
        $tags = $_POST['article_tag'];
        $poston = str_replace('/', '-', $_POST['article_poston']).' 00:00';
        $public = (isset($_POST['article_public']) ? '1' : '0');
        $order = $_POST['article_order'];
        if ($_POST['article_type'] == 'draft') {
            $draft = 1;
        }else{
            $draft = 0;
        }

        $stm = $_DB->prepare('INSERT INTO articles (uid,subject,title,content,tags,poston,public,draft,a_order) VALUES (:uid, :subject, :title, :content, :tags, :poston, :public, :draft, :a_order)');
        $stm->bindParam(':uid', $uid, PDO::PARAM_INT);
        $stm->bindParam(':subject', $subject, PDO::PARAM_INT);
        $stm->bindParam(':title', $title, PDO::PARAM_STR);
        $stm->bindParam(':content', $content, PDO::PARAM_STR);
        $stm->bindParam(':tags', $tags, PDO::PARAM_STR);
        $stm->bindParam(':poston', $poston, PDO::PARAM_STR);
        $stm->bindParam(':public', $public, PDO::PARAM_INT);
        $stm->bindParam(':draft', $draft, PDO::PARAM_INT);
        $stm->bindParam(':a_order', $order, PDO::PARAM_INT);
        $stm->execute();
        $lastid = $_DB->lastInsertId();

        addtotimeline('article', '3', $lastid, $subject);
        addtotimeline('article', '2', $lastid, $subject);

        //Sync Firestore
        /*if($public == 1){
            $stm = $_DB->prepare("SELECT subjects.subject_title,users.full_name FROM subjects JOIN users WHERE subjects.subject_id = :sid AND users.uid = :uid LIMIT 1");
            $stm->bindParam(":sid", $subject);
            $stm->bindParam(":uid", $uid);
            $stm->execute();
            $sync_row = $stm->fetch(PDO::FETCH_ASSOC);
            insertFirestore($lastid, $title, strtotime($poston), $sync_row['subject_title'], $sync_row['full_name'], $content);
        }*/

        if ($lastid) {
            echo json_encode(['state' => true, 'msg' => 'บทความได้ถูกเพิ่มแล้ว']);
        } else {
            echo json_encode(['state' => false, 'msg' => 'Error MySQL Query']);
        }
        exit;

    } elseif ($_POST['action'] == 'edit') {

        if ($_SESSION['role'] != 2) {
            echo json_encode(['state' => false, 'msg' => 'คุณไม่มีสิทธิ์ดำเนินการส่วนนี้ (ER004)']);
            exit;
        }

        $stmc2 = $_DB->prepare("SELECT uid FROM subject_owner WHERE subject_id = :id AND uid = :uid");
        $stmc2->bindParam(":id", $_POST['article_subject']);
        $stmc2->bindParam(":uid", $_SESSION['uid']);
        $stmc2->execute();
        $rowc2 = $stmc2->fetch(PDO::FETCH_ASSOC);

        if (empty($rowc2['uid'])) {
            echo json_encode(['state' => false, 'msg' => 'คุณไม่มีสิทธิ์ดำเนินการส่วนนี้ (ER001)']);
            exit;
        }

        $uid = $_POST['uid'];
        $atid = $_POST['article_id'];
        $subject = $_POST['article_subject'];
        $title = $_POST['article_title'];
        $content = $_POST['article_content'];
        $tags = $_POST['article_tag'];
        $poston = str_replace('/', '-', $_POST['article_poston']).' 00:00';
        $public = (isset($_POST['article_public']) ? '1' : '0');
        $order = $_POST['article_order'];
        if ($_POST['article_type'] == 'draft') {
            $draft = 1;
        }else{
            $draft = 0;
        }

        $stm = $_DB->prepare('UPDATE articles SET subject = :subject, title = :title, content = :content, tags = :tags, poston = :poston, public = :public, draft = :draft, a_order = :a_order WHERE atid = :atid');
        $stm->bindParam(':atid', $atid, PDO::PARAM_INT);
        $stm->bindParam(':subject', $subject, PDO::PARAM_INT);
        $stm->bindParam(':title', $title, PDO::PARAM_STR);
        $stm->bindParam(':content', $content, PDO::PARAM_STR);
        $stm->bindParam(':tags', $tags, PDO::PARAM_STR);
        $stm->bindParam(':poston', $poston, PDO::PARAM_STR);
        $stm->bindParam(':public', $public, PDO::PARAM_STR);
        $stm->bindParam(':draft', $draft, PDO::PARAM_INT);
        $stm->bindParam(':a_order', $order, PDO::PARAM_INT);
        $stm->execute();

        //Sync Firestore
        /*if($public == 1){
            $stm = $_DB->prepare("SELECT subjects.subject_title,users.full_name FROM subjects JOIN users WHERE subjects.subject_id = :sid AND users.uid = :uid LIMIT 1");
            $stm->bindParam(":sid", $subject);
            $stm->bindParam(":uid", $uid);
            $stm->execute();
            $sync_row = $stm->fetch(PDO::FETCH_ASSOC);
            updateFirestore($atid, $title, strtotime($poston), $sync_row['subject_title'], $sync_row['full_name'], $content);
        }*/

        echo json_encode(['state' => true, 'msg' => 'แก้ไขบทความแล้ว']);
        exit;
        
    } elseif ($_POST['action'] == 'delete') {

        if ($_SESSION['role'] != 2) {
            echo json_encode(['state' => false, 'msg' => 'คุณไม่มีสิทธิ์ดำเนินการส่วนนี้ (ER004)']);
            exit;
        }

        $atid = $_POST['atid'];

        removefromtimeline('article', $atid);

        $stm = $_DB->prepare('UPDATE articles SET on_delete = 1 WHERE atid = :atid');
        $stm->bindParam(':atid', $atid, PDO::PARAM_INT);
        $stm->execute();

        //Sync Firestore
        /*if($public == 1){
            deleteFirestore($atid);
        }*/

        echo json_encode(['state' => true]);
        exit;
    }
