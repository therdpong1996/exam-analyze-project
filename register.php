<?php
    session_start();
    require_once __DIR__.'/control/init.php';
    if(isset($_SESSION['auth']) and isset($_SESSION['username'])){
        header('Location: '.$_G['url'].'dashboard/');
        exit;
    }

    $_G['title'] = 'สมัครสมาชิก';

    include_once __DIR__.'/views/parts/header.common.php';

    if($_GS['init_regis']){
        include_once __DIR__.'/views/register.page.php';
    }else{
        include_once __DIR__.'/views/undercons.page.php';
    }

    include_once __DIR__.'/views/parts/footer.content.php';
    include_once __DIR__.'/views/parts/footer.common.php';

?>