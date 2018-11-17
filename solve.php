<?php
    session_start();
    if(!isset($_SESSION['auth']) and !isset($_SESSION['username'])){
        header('Location: ../login/');
        exit;
    }

    require_once __DIR__.'/control/init.php';

    $_G['title'] = 'สำเร็จแล้ว';

    //USER
    $stm = $_DB->prepare("SELECT * FROM users JOIN users_role_title ON users.role = users_role_title.role_id WHERE users.username = :username LIMIT 1");
    $stm->bindParam(':username', $_SESSION['username'], PDO::PARAM_STR);
    $stm->execute();
    $user_row = $stm->fetch(PDO::FETCH_ASSOC);

    include_once __DIR__.'/views/parts/header.common.php';
    include_once __DIR__.'/views/parts/sidemenu.common.php';

    if (isset($_POST['uid']) and isset($_POST['subject']) and isset($_POST['examination']) and isset($_POST['session'])) {

        $stmt = $_DB->prepare("UPDATE answer_data SET temp = 0 WHERE id = :id");
        $stmt->bindParam(":id", $rows['id']);
        $stmt->execute();

        $score = 0;
        $full = 0;
        $stm = $_DB->prepare("SELECT * FROM answer_data WHERE uid = :uid AND subject = :subject AND examination = :exam AND session = :session AND temp = 1");
        $stm->bindParam(":uid", $_POST['uid']);
        $stm->bindParam(":subject", $_POST['subject']);
        $stm->bindParam(":exam", $_POST['examination']);
        $stm->bindParam(":session", $_POST['session']);
        $stm->execute();
        while ($rows = $stm->fetch(PDO::FETCH_ASSOC)){
            if ($rows['ans_check'] == 1) {
                $score += 1;
                $full += 1;
            }else{
                $full += 1;
            }
            $stmt = $_DB->prepare("UPDATE answer_data SET temp = 0 WHERE id = :id");
            $stmt->bindParam(":id", $rows['id']);
            $stmt->execute();
        }

        $percen = ($score/$full)*100;
        $stms = $_DB->prepare("INSERT INTO session_score (session_id,exam_id,subject_id,uid,score,score_full) VALUES (:session, :exam, :subject, :uid, :sroce, :full)");
        $stms->bindParam(":session", $_POST['session']);
        $stms->bindParam(":exam", $_POST['examination']);
        $stms->bindParam(":subject", $_POST['subject']);
        $stms->bindParam(":uid", $_POST['uid']);
        $stms->bindParam(":sroce", $score);
        $stms->bindParam(":full", $full);
        $stms->execute();
        include_once __DIR__.'/views/solve.page.php';
    }else{
        include_once __DIR__.'/views/denied.page.php';
    }

    include_once __DIR__.'/views/parts/footer.content.php';
    include_once __DIR__.'/views/parts/footer.common.php';
?>