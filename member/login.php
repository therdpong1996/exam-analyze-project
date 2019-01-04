<?php
    session_start();
    require_once __DIR__.'/control/init.php';
    if(isset($_SESSION['auth']) and isset($_SESSION['username'])){
        header('Location: '.$_G['url'].'dashboard/');
        exit;
    }

    $_G['title'] = 'เข้าสู่ระบบ';

    include_once __DIR__.'/views/parts/header.common.php';

    include_once __DIR__.'/views/login.page.php';

    include_once __DIR__.'/views/parts/footer.content.php';
    include_once __DIR__.'/views/parts/footer.common.php';

?>