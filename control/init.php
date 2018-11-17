<?php

    date_default_timezone_set("Asia/Bangkok");
    ini_set('display_errors', 0);
    error_reporting(E_ALL);


    //PDO
    $_PDO['dsn'] = 'mysql:host=178.128.16.91;dbname=admin_project;charset=utf8';
    $_PDO['username'] = 'admin_project';
    $_PDO['password'] = 'pwa@pass';

    try {
        $_DB = new PDO($_PDO['dsn'], $_PDO['username'], $_PDO['password']);
    } catch (Exception $ex) {
        $ex->getMessage();
    }

    if (!strrpos($_SERVER['SCRIPT_NAME'], 'doing-examination')) {

        if (isset($_SESSION['exam_lock'])) {
            unset($_SESSION['exam_lock']);
        }

    }

    //WEB URL
    $_G['url'] = 'http://localhost/project/';

    require_once 'functions.php';
