<?php
    session_start();
    require_once __DIR__.'/control/init.php';
    if (!isset($_SESSION['auth']) and !isset($_SESSION['username'])) {
        header('Location: '.$_G['url'].'login/');
        exit;
    }

    $_G['title'] = 'วิเคราะห์ชุดสอบสอบ';

    //USER
    $stm = $_DB->prepare("SELECT * FROM users JOIN users_role_title ON users.role = users_role_title.role_id WHERE users.username = :username LIMIT 1");
    $stm->bindParam(':username', $_SESSION['username'], PDO::PARAM_STR);
    $stm->execute();
    $user_row = $stm->fetch(PDO::FETCH_ASSOC);

    //EXAM
    $stm = $_DB->prepare("SELECT * FROM examinations WHERE examination_id = :examination_id LIMIT 1");
    $stm->bindParam(':examination_id', $_GET['examination_id'], PDO::PARAM_STR);
    $stm->execute();
    $examination = $stm->fetch(PDO::FETCH_ASSOC);

    include_once __DIR__.'/views/parts/header.common.php';
    include_once __DIR__.'/views/parts/sidemenu.common.php';

    if ($user_row['role'] == 2) {
        if($_GS['init_exam']){
            include_once __DIR__.'/views/examination.analyze.page.php';
        }else{
            include_once __DIR__.'/views/undercons.page.php';
        }
    }else{
        include_once __DIR__.'/views/denied.page.php';

    }
    include_once __DIR__.'/views/parts/footer.content.php';
    include_once __DIR__.'/views/parts/footer.common.php';

?>