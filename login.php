<?php
    session_start();
    if(isset($_SESSION['auth']) and isset($_SESSION['username'])){
        header('Location: ../dashboard/');
        exit;
    }

    require_once __DIR__.'/control/init.php';

    $_G['title'] = 'เข้าสู่ระบบ';

    include_once __DIR__.'/views/parts/header.common.php';

    include_once __DIR__.'/views/login.page.php';

    include_once __DIR__.'/views/parts/footer.content.php';
    include_once __DIR__.'/views/parts/footer.common.php';

?>