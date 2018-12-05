<?php
    session_start();
    header('Content-type: text/plain');
    require_once __DIR__.'/control/init.php';

    if (!isset($_POST['username']) and !isset($_POST['password'])) {
        exit('not found account');
    }
    $user = $_POST['username'];
    $pass = hash('sha256', $_POST['password']);
    //USER
    $stm = $_DB->prepare('SELECT * FROM users JOIN users_role_title ON users.role = users_role_title.role_id WHERE users.username = :username AND users.password = :password LIMIT 1');
    $stm->bindParam(':username', $user, PDO::PARAM_STR);
    $stm->bindParam(':password', $pass, PDO::PARAM_STR);
    $stm->execute();
    $user_row = $stm->fetch(PDO::FETCH_ASSOC);

    if ($user_row['role'] == 2) {
        $stm = $_DB->prepare('SELECT * FROM answer_data JOIN users ON answer_data.uid = users.uid WHERE answer_data.session = :session ORDER BY users.stu_id ASC');
        $stm->bindParam(':session', $_GET['session_id'], PDO::PARAM_INT);
        $stm->execute();
        while ($rows = $stm->fetch(PDO::FETCH_ASSOC)) {
            echo $rows['stu_id'].','.$rows['question'].','.$rows['time_taken_s'].','.($rows['ans_check'] == 1 ? 'True' : 'False').PHP_EOL;
        }
    } else {
        exit('don\'t have permission');
    }
