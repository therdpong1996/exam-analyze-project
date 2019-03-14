<?php

    date_default_timezone_set('Asia/Bangkok');
    ini_set('display_errors', 0);
    error_reporting(0);

    //PDO
    $_PDO['dsn'] = 'mysql:host=157.230.12.219;dbname=cat_database;charset=utf8';
    $_PDO['username'] = 'cat_database';
    $_PDO['password'] = 'cat_db@pass';

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
    $_G['furl'] = 'https://cat-rmutl.xyz/';
    $_G['url'] = 'https://cat-rmutl.xyz/member/';
    $_G['webservice'] = 'https://analyze-sv2.cat-rmutl.xyz/';

    require_once 'functions.php';
