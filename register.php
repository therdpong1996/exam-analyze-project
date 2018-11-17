<?php
    session_start();
    if(isset($_SESSION['auth']) and isset($_SESSION['username'])){
        header('Location: ../dashboard/');
        exit;
    }

    require_once __DIR__.'/control/init.php';

    $_G['title'] = 'สมัครสมาชิก';

    include_once __DIR__.'/views/parts/header.common.php';

    include_once __DIR__.'/views/register.page.php';

    include_once __DIR__.'/views/parts/footer.content.php';
    include_once __DIR__.'/views/parts/footer.common.php';

?>