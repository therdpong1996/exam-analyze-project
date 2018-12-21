<?php
    session_start();
    require_once __DIR__.'/control/init.php';
    if (!isset($_SESSION['auth']) and !isset($_SESSION['username'])) {
        header('Location: '.$_G['url'].'login/');
        exit;
    }

    $_G['title'] = 'ส่งข้อสอบและตรวจผล';

    //USER
    $stm = $_DB->prepare('SELECT * FROM users JOIN users_role_title ON users.role = users_role_title.role_id WHERE users.username = :username LIMIT 1');
    $stm->bindParam(':username', $_SESSION['username'], PDO::PARAM_STR);
    $stm->execute();
    $user_row = $stm->fetch(PDO::FETCH_ASSOC);

    include_once __DIR__.'/views/parts/header.common.php';
    include_once __DIR__.'/views/parts/sidemenu.common.php';

    if (isset($_POST['uid']) and isset($_POST['subject']) and isset($_POST['examination']) and isset($_POST['session'])) {
        $stmt = $_DB->prepare('UPDATE adaptive_answer_data SET temp = 0 WHERE id = :id');
        $stmt->bindParam(':id', $rows['id']);
        $stmt->execute();

        $stmu = $_DB->prepare("SELECT session_adap_number FROM sessions WHERE session_id = :sid");
        $stmu->bindParam(":sid", $_POST['session']);
        $stmu->execute();
        $full = $stmu->fetch(PDO::FETCH_ASSOC);
        $full = $full['session_adap_number'];

        $score = 0;
        $stm = $_DB->prepare('SELECT * FROM adaptive_answer_data WHERE uid = :uid AND subject = :subject AND examination = :exam AND session = :session AND temp = 1');
        $stm->bindParam(':uid', $_POST['uid']);
        $stm->bindParam(':subject', $_POST['subject']);
        $stm->bindParam(':exam', $_POST['examination']);
        $stm->bindParam(':session', $_POST['session']);
        $stm->execute();
        while ($rows = $stm->fetch(PDO::FETCH_ASSOC)) {
            if ($rows['ans_check'] == 1) {
                ++$score;
            }
            $stmt = $_DB->prepare('UPDATE adaptive_answer_data SET temp = 0 WHERE id = :id');
            $stmt->bindParam(':id', $rows['id']);
            $stmt->execute();
        }

        $percen = ($score / $full) * 100;
        $stms = $_DB->prepare('INSERT INTO adaptive_session_score (session_id,exam_id,subject_id,uid,score,score_full) VALUES (:session, :exam, :subject, :uid, :sroce, :full)');
        $stms->bindParam(':session', $_POST['session']);
        $stms->bindParam(':exam', $_POST['examination']);
        $stms->bindParam(':subject', $_POST['subject']);
        $stms->bindParam(':uid', $_POST['uid']);
        $stms->bindParam(':sroce', $score);
        $stms->bindParam(':full', $full);
        $stms->execute();

        $stmt = $_DB->prepare('UPDATE adaptive_time_remaining SET time_status = 1 WHERE uid = :uid AND session = :session');
        $stmt->bindParam(':session', $_POST['session']);
        $stmt->bindParam(':uid', $_POST['uid']);
        $stmt->execute();

        include_once __DIR__.'/views/solve-adaptive.page.php';
    } else {
        include_once __DIR__.'/views/denied.page.php';
    }

    include_once __DIR__.'/views/parts/footer.content.php';
    include_once __DIR__.'/views/parts/footer.common.php';
