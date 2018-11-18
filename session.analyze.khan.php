<?php
    session_start();
    header('Content-type: text/plain');
    if(!isset($_SESSION['auth']) and !isset($_SESSION['username'])){
        header('Location: ../login/');
        exit;
    }

    require_once __DIR__.'/control/init.php';

    $_G['title'] = 'รายชื่อผู้ทำ';

    //USER
    $stm = $_DB->prepare("SELECT * FROM users JOIN users_role_title ON users.role = users_role_title.role_id WHERE users.username = :username LIMIT 1");
    $stm->bindParam(':username', $_SESSION['username'], PDO::PARAM_STR);
    $stm->execute();
    $user_row = $stm->fetch(PDO::FETCH_ASSOC);

    if ($user_row['role'] == 2) {
        $stm = $_DB->prepare("SELECT * FROM answer_data JOIN users ON answer_data.uid = users.uid WHERE answer_data.session = :session ORDER BY users.stu_id ASC");
        $stm->bindParam(':session', $_GET['session_id'], PDO::PARAM_INT);
        $stm->execute();
        while ($rows = $stm->fetch(PDO::FETCH_ASSOC)) {
            echo $rows['stu_id'].",".$rows['question'].",".$rows['time_taken_m'].",".($rows['ans_check']==1?'True':'False').PHP_EOL;
        }
    }else{
        include_once __DIR__.'/views/denied.page.php';

    }


?>