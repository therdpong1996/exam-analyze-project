<?php

    date_default_timezone_set('Asia/Bangkok');
    ini_set('display_errors', 0);
    error_reporting(0);

    //PDO
    $_PDO['dsn'] = 'mysql:host=us-cdbr-iron-east-01.cleardb.net;dbname=heroku_1f970f0ca4cd2ac;charset=utf8';
    $_PDO['username'] = 'baad56daf6e4f8';
    $_PDO['password'] = '385cbae4';

    try {
        $_DB = new PDO($_PDO['dsn'], $_PDO['username'], $_PDO['password']);
    } catch (Exception $ex) {
        echo $ex->getMessage();
    }

    if (!strrpos($_SERVER['SCRIPT_NAME'], 'doing-examination')) {
        if (isset($_SESSION['exam_lock'])) {
            unset($_SESSION['exam_lock']);
        }
    }

    $stms = $_DB->prepare("SELECT * FROM setting");
    $stms->execute();
    $_GS = $stms->fetch(PDO::FETCH_ASSOC);

    //WEB URL
    $_G['furl'] = 'https://exam-analyze.herokuapp.com/';
    $_G['url'] = 'https://exam-analyze.herokuapp.com/member/';
    $_G['webservice'] = 'https://analyze.cat-rmutl.xyz/';

    require_once 'functions.php';
