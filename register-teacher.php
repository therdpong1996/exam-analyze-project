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

            if ($_GET['token'] != $emailmd5 or time() > $exp) {
                include_once __DIR__.'/views/denied.page.php';
            }else{
                $email = base64_decode($_GET['email']);
                $stm = $_DB->prepare("SELECT COUNT(uid) AS n FROM users WHERE email = :email");
                $stm->bindParam(":email", $email);
                $stm->execute();
                $ec = $stm->fetch(PDO::FETCH_ASSOC);
                if ($ec['n'] >= 1) {
                    include_once __DIR__.'/views/denied.page.php';
                }else{
                    include_once __DIR__.'/views/register-teacher.page.php';
                }
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