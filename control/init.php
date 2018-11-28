<?php

    date_default_timezone_set('Asia/Bangkok');
    ini_set('display_errors', 0);
    error_reporting(E_ALL);

    //PDO
    $_PDO['dsn'] = 'mysql:host=178.128.18.187;dbname=admin_cat;charset=utf8';
    $_PDO['username'] = 'admin_cat';
    $_PDO['password'] = 'pwa@pass';

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

    //WEB URL
    $_G['url'] = 'https://cat-project.azurewebsites.net/';

    require_once 'functions.php';
