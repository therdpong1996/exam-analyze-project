<?php
    header('Content-type: application/json');
    session_start();
    require_once '../control/init.php';
    require '../vendor/autoload.php';
    use Mailgun\Mailgun;

    $email = $_POST['email'];
    $activeMail = ['live.rmutl.ac.th'];
    $checkmail = explode('@', $email);

    if (!in_array($checkmail['1'], $activeMail)) {
        echo json_encode(['state' => false, 'msg' => 'ขออภัย! อีเมล์นี้ไม่มีการยืนยัน', 'head'=> 'ผิดพลาด']);
        exit;
    }else{
        
        $token = md5($_POST['email'] . 'cat@rmutl');
        $email = base64_encode($_POST['email']);
        $vurl = $_G['url'].'register-teacher/?token='.$token.'&email='.$email;

        $mgClient = new Mailgun('c6a6fb3027866dd672043e123c011a2e-9b463597-b03543b2');
        $domain = "mg.inzpi.com";
        $text = 'Hello, Verify URL: '.$vurl;
        $html = '<strong>สวัสดีครับ,</strong><br><p>กรุณาคลิกลิงก์ยืนยันด้านล่างนี้ เพื่อสมัครสมาชิกสำหรับอาจารย์</p><p>Verify URL : <a href="'.$vurl.'" target="_blank">'.$vurl.'</a></p><br><br><small>CAT@RMUTL<br>'.$_G['url'].'</small>';
        $result = $mgClient->sendMessage("$domain",
            array('from' => 'No-reply CAT@RMUTL <noreply@mg.inzpi.com>',
                'to' => 'Anonymouse <'.$_POST['email'].'>',
                'subject' => 'สวัสดีครับ, นี่เป็นอีเมล์สำหรับยืนยันตัวอาจาย์ ('.$_POST['email'] .')',
                'text' => $text,
                'html' => $html
            )
        );

        print_r($result);
    }