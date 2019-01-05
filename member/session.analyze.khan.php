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

        $stm = $_DB->prepare('SELECT * FROM answer_data JOIN users ON answer_data.uid = users.uid WHERE answer_data.examination = :exam ORDER BY users.stu_id ASC');
        $stm->bindParam(':exam', $_POST['examination'], PDO::PARAM_INT);
        $stm->execute();

        $stm2 = $_DB->prepare("SELECT COUNT(DISTINCT(answer_data.uid)) AS stdn FROM answer_data JOIN users ON answer_data.uid = users.uid WHERE answer_data.examination = :exam ORDER BY users.stu_id ASC");
        $stm2->bindParam(':exam', $_POST['examination'], PDO::PARAM_INT);
        $stm2->execute();
        $row = $stm2->fetch(PDO::FETCH_ASSOC);

        $stm3 = $_DB->prepare("UPDATE adaptive_table SET std_number = :number WHERE adap_id = :id");
        $stm3->bindParam(':number', $row['stdn'], PDO::PARAM_INT);
        $stm3->bindParam(':id', $_POST['adaptable'], PDO::PARAM_INT);
        $stm3->execute();

        $stm4 = $_DB->prepare("SELECT examination_subject FROM examinations WHERE examination_id = :id");
        $stm4->bindParam(':id', $_POST['examination']);
        $sub = $stm4->execute();

        addtotimeline('train', '2', $_POST['adaptable'], $sub['examination_subject']);

        while ($rows = $stm->fetch(PDO::FETCH_ASSOC)) {
            echo $rows['stu_id'].','.$rows['question'].','.$rows['time_taken_s'].','.($rows['ans_check'] == 1 ? 'True' : 'False').PHP_EOL;
        }

    } else {
        exit('don\'t have permission');
    }
