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

        $stmu = $_DB->prepare("UPDATE examinations SET examination_newex = 0 WHERE examination_id = :eid");
        $stmu->bindParam(':eid', $_POST['examination']);
        $stmu->execute();

        $score = 0;
        $full = 0;
        $stm = $_DB->prepare('SELECT * FROM answer_data WHERE uid = :uid AND subject = :subject AND examination = :exam AND session = :session AND temp = 1');
        $stm->bindParam(':uid', $_POST['uid']);
        $stm->bindParam(':subject', $_POST['subject']);
        $stm->bindParam(':exam', $_POST['examination']);
        $stm->bindParam(':session', $_POST['session']);
        $stm->execute();
        while ($rows = $stm->fetch(PDO::FETCH_ASSOC)) {
            if ($rows['ans_check'] == 1) {
                ++$score;
                ++$full;
            } else {
                ++$full;
            }
            $stmt = $_DB->prepare('UPDATE answer_data SET temp = 0 WHERE id = :id');
            $stmt->bindParam(':id', $rows['id']);
            $stmt->execute();
        }

        $percen = ($score / $full) * 100;
        $stms = $_DB->prepare('INSERT INTO session_score (session_id,exam_id,subject_id,uid,score,score_full) VALUES (:session, :exam, :subject, :uid, :sroce, :full)');
        $stms->bindParam(':session', $_POST['session']);
        $stms->bindParam(':exam', $_POST['examination']);
        $stms->bindParam(':subject', $_POST['subject']);
        $stms->bindParam(':uid', $_POST['uid']);
        $stms->bindParam(':sroce', $score);
        $stms->bindParam(':full', $full);
        $stms->execute();
        $lastid = $_DB->lastInsertId();

        $stmt = $_DB->prepare('UPDATE time_remaining SET time_status = 1 WHERE uid = :uid AND session = :session');
        $stmt->bindParam(':session', $_POST['session']);
        $stmt->bindParam(':uid', $_POST['uid']);
        $stmt->execute();

        $stmy = $_DB->prepare('UPDATE sessions SET session_status = 1 WHERE session = :session');
        $stmy->bindParam(':session', $_POST['session']);
        $stmy->execute();

        addtotimeline('solve', '3', $lastid, $_POST['subject']);
        
        include_once __DIR__.'/views/solve.page.php';
    } else {
        include_once __DIR__.'/views/denied.page.php';
    }

    include_once __DIR__.'/views/parts/footer.content.php';
    include_once __DIR__.'/views/parts/footer.common.php';
