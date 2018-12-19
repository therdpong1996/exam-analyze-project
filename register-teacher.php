<?php
    session_start();
    require_once __DIR__.'/control/init.php';
    if(isset($_SESSION['auth']) and isset($_SESSION['username'])){
        header('Location: '.$_G['url'].'dashboard/');
        exit;
    }

    $_G['title'] = 'สมัครสมาชิกสำหรับอาจารย์';

    include_once __DIR__.'/views/parts/header.common.php';

    if($_GS['init_regis']){
        if(isset($_GET['token']) and isset($_GET['email']) and isset($_GET['exp'])){
            $emailmd5 = md5(base64_decode($_GET['email']) . 'cat@rmutl');
            $exp = base64_decode($_GET['exp']);
            if ($_GET['token'] != $emailmd5 and time() > $exp) {
                include_once __DIR__.'/views/denied.page.php';
            }else{
                $email = base64_decode($_GET['email']);
                include_once __DIR__.'/views/register-teacher.page.php';
            }
        }else{
            include_once __DIR__.'/views/register-teacher-email.page.php';
        }
    }else{
        include_once __DIR__.'/views/undercons.page.php';
    }

    include_once __DIR__.'/views/parts/footer.content.php';
    include_once __DIR__.'/views/parts/footer.common.php';

?>