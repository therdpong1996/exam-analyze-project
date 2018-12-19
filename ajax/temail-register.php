<?php
    header('Content-type: application/json');
    session_start();
    require_once '../control/init.php';
    require '../vendor/autoload.php';
    use Mailgun\Mailgun;

    $email = $_POST['email'];
    $activeMail = ['rmutl.ac.th'];
    $checkmail = explode('@', $email);

    if (!in_array($checkmail['1'], $activeMail)) {
        echo json_encode(['state' => false, 'msg' => 'ขออภัย! อีเมล์นี้ไม่มีการยืนยัน', 'head'=> 'ผิดพลาด']);
        exit;
    }else{
        
        $token = md5($_POST['email'] . 'cat@rmutl');
        $email = base64_encode($_POST['email']);
        $vurl = $_G['url'].'register-teacher/?token='.$token.'&email='.$email;

        $mgClient = new Mailgun('c6a6fb3027866dd672043e123c011a2e-9b463597-b03543b2');
        $domain = "sandbox9cf778143713409097470f85c022fee9.mailgun.org";
    }