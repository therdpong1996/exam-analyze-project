<?php
    session_start();
    if(!isset($_SESSION['auth']) and !isset($_SESSION['username'])){
        header('Location: ../login/');
        exit;
    }

    require_once __DIR__.'/control/init.php';

    $_G['title'] = 'ข้อสอบ';

    //USER
    $stm = $_DB->prepare("SELECT * FROM users JOIN users_role_title ON users.role = users_role_title.role_id WHERE users.username = :username LIMIT 1");
    $stm->bindParam(':username', $_SESSION['username'], PDO::PARAM_STR);
    $stm->execute();
    $user_row = $stm->fetch(PDO::FETCH_ASSOC);

    include_once __DIR__.'/views/parts/header.common.php';
    include_once __DIR__.'/views/parts/sidemenu.common.php';

    if ($user_row['role'] == 3) {
        include_once __DIR__.'/views/stu-examination.page.php';
    }else{
        include_once __DIR__.'/views/denied.page.php';
    }
    include_once __DIR__.'/views/parts/footer.content.php';
    include_once __DIR__.'/views/parts/footer.common.php';

?>