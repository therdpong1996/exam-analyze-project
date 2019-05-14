<?php

    header('Content-type: application/json');
    session_start();
    require_once '../control/init.php';
    require '../vendor/autoload.php';
    use Mailgun\Mailgun;

    $email = $_POST['email'];
        
    $token = md5($_POST['email'] . 'cat@rmutl');
    $email = base64_encode($_POST['email']);
    $vurl = $_G['url'].'register-teacher/?token='.$token.'&email='.$email.'&exp='.base64_encode(time()+(86400*3));

    $mgClient = Mailgun::create('c6a6fb3027866dd672043e123c011a2e-9b463597-b03543b2');
    $domain = "mg.inzpi.com";
    $text = 'Hello, Verify URL: '.$vurl;
    $html = '<strong>สวัสดีครับ,</strong><br><p>กรุณาคลิกลิงก์ยืนยันด้านล่างนี้ เพื่อสมัครสมาชิกสำหรับอาจารย์ (ลิงก์มีอายุการใช้งาน 3 วัน)</p><p>Verify URL : <a href="'.$vurl.'" target="_blank">'.$vurl.'</a></p><br><br><small>CAT@RMUTL<br>'.$_G['url'].'</small>';
    $result = $mgClient->messages()->send($domain,
            [
                'from' => 'No-reply CAT@RMUTL <noreply@mg.inzpi.com>',
                'to' => 'Anonymouse <'.$_POST['email'].'>',
                'subject' => 'สวัสดีครับ, นี่เป็นอีเมล์สำหรับยืนยันตัวอาจาย์ ('.$_POST['email'] .')',
                'text' => $text,
                'html' => $html
            ]
    );

    if ($result->http_response_code == 200) {
        $html = '<div class="text-center"><i class="fas fa-check-circle fa-5x text-success"></i><br><br><h3>ระบบได้ส่งลิงก์ยืนยันไปที่เมล์ "'.$_POST['email'].'" แล้ว กรุณาตรวจสอบที่กล่องข้อความของอีเมล์หรืออีเมล์ขยะ ขอบคุณครับ</h3></div>';
        echo json_encode(['state' => true, 'html' => $html]);
    }else{
        echo json_encode(['state' => false, 'msg' => $result->http_response_body->message, 'head'=> 'ผิดพลาด']);
        exit;
    }