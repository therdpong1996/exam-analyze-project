<?php
    header('Content-type: application/json');
    session_start();
    require_once '../control/init.php';

    $username = $_POST['username'];
    $password = hash('sha256', $_POST['password']);
    $student_id = $_POST['student_id'];
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];

    $stm = $_DB->prepare("INSERT INTO users (username,password,email,full_name,stu_id) VALUES (:username, :password, :email, :full_name, :stu_id)");
    $stm->bindParam(':username', $username, PDO::PARAM_STR);
    $stm->bindParam(':password', $password, PDO::PARAM_STR);
    $stm->bindParam(':stu_id', $student_id, PDO::PARAM_STR);
    $stm->bindParam(':full_name', $fullname, PDO::PARAM_STR);
    $stm->bindParam(':email', $email, PDO::PARAM_STR);
    $stm->execute();
    $lastid = $_DB->lastInsertId();

    if($lastid){
        $_SESSION['username'] = $username;
        $_SESSION['auth'] = session_id();
        $_SESSION['role'] = 3;
        $_SESSION['uid'] = $lastid;
		echo json_encode(['state'=>true, 'msg'=>'กำลังพาท่านไปหน้าแดชบอร์ด']);
    }else{
        echo json_encode(['state'=>false, 'msg'=>'Error MySQL Query']);
    }
    exit;
    