<?php
    session_start();
    require_once __DIR__.'/control/init.php';
    if (!isset($_SESSION['auth']) and !isset($_SESSION['username'])) {
        header('Location: '.$_G['url'].'login/');
        exit;
    }

    $_G['title'] = 'จัดการข้อสอบ';

    //USER
    $stm = $_DB->prepare("SELECT * FROM users JOIN users_role_title ON users.role = users_role_title.role_id WHERE users.username = :username LIMIT 1");
    $stm->bindParam(':username', $_SESSION['username'], PDO::PARAM_STR);
    $stm->execute();
    $user_row = $stm->fetch(PDO::FETCH_ASSOC);
    
    //CHECK PERMISSION
    $stm2 = $_DB->prepare('SELECT * FROM examinations WHERE examination_id = :examination_id LIMIT 1');
    $stm2->bindParam(':examination_id', $_GET['examination_id'], PDO::PARAM_INT);
    $stm2->execute();
    $row2 = $stm2->fetch(PDO::FETCH_ASSOC);

    $stmc2 = $_DB->prepare("SELECT uid FROM subject_owner WHERE subject_id = :id AND uid = :uid");
    $stmc2->bindParam(":id", $row2['examination_subject']);
    $stmc2->bindParam(":uid", $_SESSION['uid']);
    $stmc2->execute();
    $rowc2 = $stmc2->fetch(PDO::FETCH_ASSOC);

    //EXAM
    $stm = $_DB->prepare("SELECT * FROM examinations JOIN subjects ON examinations.examination_subject = subjects.subject_id WHERE examinations.examination_id = :exam_id LIMIT 1");
    $stm->bindParam(':exam_id', $_GET['examination_id'], PDO::PARAM_STR);
    $stm->execute();
    $exam = $stm->fetch(PDO::FETCH_ASSOC);

    include_once __DIR__.'/views/parts/header.common.php';
    include_once __DIR__.'/views/parts/sidemenu.common.php';
    
    if ($user_row['role'] == 2) {
        if (empty($rowc2['uid'])) {
            include_once __DIR__.'/views/denied.page.php';
        }else{
            if($_GS['init_exam']){
                include_once __DIR__.'/views/examination.qa.page.php';
            }else{
                include_once __DIR__.'/views/undercons.page.php';
            }
        }
    }else{
        include_once __DIR__.'/views/denied.page.php';
    }

    include_once __DIR__.'/views/parts/footer.content.php';
    include_once __DIR__.'/views/parts/footer.common.php';

?>