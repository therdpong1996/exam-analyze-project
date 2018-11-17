<?php

    date_default_timezone_set("Asia/Bangkok");
    ini_set('display_errors', 0);
    error_reporting(E_ALL);


    //PDO
    $_PDO['dsn'] = 'mysql:host=localhost;dbname=project;charset=utf8';
    $_PDO['username'] = 'root';
    $_PDO['password'] = '';

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

    $_G['url'] = 'http://localhost/project/';

    require_once 'functions.php';
