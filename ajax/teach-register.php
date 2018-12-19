<?php
    header('Content-type: application/json');
    session_start();
    require_once '../control/init.php';
    require '../vendor/autoload.php';
    use Mailgun\Mailgun;

    $username = $_POST['username'];
    $password = hash('sha256', $_POST['password']);
    $student_id = $_POST['student_id'];
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];

    $stm = $_DB->prepare('SELECT uid FROM users WHERE username = :username LIMIT 1');
    $stm->bindParam(':username', $username, PDO::PARAM_STR);
    $stm->execute();
    $userchk = $stm->fetch(PDO::FETCH_ASSOC);
    if ($userchk['uid']) {
        echo json_encode(['state' => false, 'msg' => 'Username นี้มีคนใช้งานแล้ว']);
        exit;
    }

    $stm = $_DB->prepare('INSERT INTO users (username,password,email,full_name,stu_id,role) VALUES (:username, :password, :email, :full_name, :stu_id, 2)');
    $stm->bindParam(':username', $username, PDO::PARAM_STR);
    $stm->bindParam(':password', $password, PDO::PARAM_STR);
    $stm->bindParam(':stu_id', $student_id, PDO::PARAM_STR);
    $stm->bindParam(':full_name', $fullname, PDO::PARAM_STR);
    $stm->bindParam(':email', $email, PDO::PARAM_STR);
    $stm->execute();
    $lastid = $_DB->lastInsertId();

    if ($lastid) {
        $_SESSION['username'] = $username;
        $_SESSION['auth'] = session_id();
        $_SESSION['role'] = 3;
        $_SESSION['uid'] = $lastid;

        $mgClient = new Mailgun('c6a6fb3027866dd672043e123c011a2e-9b463597-b03543b2');
        $domain = "mg.inzpi.com";
        $text = 'สวัสดีครับ, '.$username.' ขอบคุณสำหรับการสมัครสมาชิก';
        $html = '<strong>สวัสดีครับ, '.$username.'</strong><br><p>ขอบคุณสำหรับการสมัครสมาชิกเว็บไซต์</p><p>วันที่สมัคร '.date('d/m/Y H:i').'</p><br><br><small>CAT@RMUTL<br>'.$_G['url'].'</small>';
        $result = $mgClient->sendMessage("$domain",
            array('from' => 'No-reply CAT@RMUTL <noreply@mg.inzpi.com>',
                'to' => 'Anonymouse <'.$email.'>',
                'subject' => 'สวัสดีครับ, ขอบคุณสำหรับการสมัครสมาชิก ('.$email .')',
                'text' => $text,
                'html' => $html
            )
        );

        if ($result->http_response_code == 200) {
            echo json_encode(['state' => true, 'msg' => 'กำลังพาท่านไปหน้าแดชบอร์ด']);
        }
    } else {
        echo json_encode(['state' => false, 'msg' => 'Error MySQL Query']);
    }
    exit;
