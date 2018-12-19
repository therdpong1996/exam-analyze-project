<?php
    session_start();
    require_once __DIR__.'/control/init.php';
    if (!isset($_SESSION['auth']) and !isset($_SESSION['username'])) {
        header('Location: '.$_G['url'].'login/');
        exit;
    }

    $_G['title'] = 'ทำข้อสอบ';

    //USER
    $stm = $_DB->prepare('SELECT * FROM users JOIN users_role_title ON users.role = users_role_title.role_id WHERE users.username = :username LIMIT 1');
    $stm->bindParam(':username', $_SESSION['username'], PDO::PARAM_STR);
    $stm->execute();
    $user_row = $stm->fetch(PDO::FETCH_ASSOC);

    //EXAM
    $stm = $_DB->prepare('SELECT * FROM sessions JOIN examinations ON sessions.session_exam = examinations.examination_id JOIN subjects ON examinations.examination_subject = subjects.subject_id WHERE sessions.session_id = :session_id LIMIT 1');
    $stm->bindParam(':session_id', $_GET['session_id'], PDO::PARAM_STR);
    $stm->execute();
    $session = $stm->fetch(PDO::FETCH_ASSOC);

    //DOING
    $stmt = $_DB->prepare('SELECT * FROM session_score WHERE session_id = :session AND exam_id = :exam AND subject_id = :subject AND uid = :uid LIMIT 1');
    $stmt->bindParam(':session', $session['session_id']);
    $stmt->bindParam(':exam', $session['examination_id']);
    $stmt->bindParam(':subject', $session['subject_id']);
    $stmt->bindParam(':uid', $user_row['uid']);
    $stmt->execute();
    $crow = $stmt->fetch(PDO::FETCH_ASSOC);
    include_once __DIR__.'/views/parts/header.common.php';
    include_once __DIR__.'/views/parts/sidemenu.common.php';

    $password_fail = false;

    if ($session['session_password'] != null and !isset($_SESSION['exam_lock'])) {
        $_SESSION['exam_lock'] = false;
    }

    if (isset($_POST['password']) and $_POST['password'] == $session['session_password']) {
        $_SESSION['exam_lock'] = true;
    } elseif (isset($_POST['password']) and $_POST['password'] != $session['session_password']) {
        $password_fail = true;
    }

    $stme = $_DB->prepare("SELECT * FROM student_subject WHERE uid = :uid AND subject_id = :subject_id");
    $stme->bindParam(":uid", $user_row['uid']);
    $stme->bindParam(":subject_id", $session['subject_id']);
    $stme->execute();
    $permid = $stme->fetch(PDO::FETCH_ASSOC);

    if ($user_row['role'] == 3) {
        if ($crow['score_id'] or time() > strtotime($session['session_end'])) {
            include_once __DIR__.'/views/denied.page.php';
        } else {
            if ($session['session_adap'] == 0) {
                if (empty($permid['subject_id'])) {
                    include_once __DIR__.'/views/denied.page.php';
                }else{
                    if($_GS['init_testing']){
                        if ($session['session_password'] != null and $_SESSION['exam_lock'] == false) {
                            include_once __DIR__.'/views/doing-password-examination.page.php';
                        } elseif ($session['session_password'] == null and $_SESSION['exam_lock'] == false) {
                            include_once __DIR__.'/views/doing-examination.page.php';
                        } elseif ($session['session_password'] != null and $_SESSION['exam_lock'] == true) {
                            include_once __DIR__.'/views/doing-examination.page.php';
                        } elseif ($session['session_password'] == null and $_SESSION['exam_lock'] == true) {
                            include_once __DIR__.'/views/doing-examination.page.php';
                        }
                    }else{
                        include_once __DIR__.'/views/undercons.page.php';
                    }
                }
            } else {
                include_once __DIR__.'/views/denied.page.php';
            }
        }
    } else {
        include_once __DIR__.'/views/denied.page.php';
    }
    include_once __DIR__.'/views/parts/footer.content.php';
    include_once __DIR__.'/views/parts/footer.common.php';
