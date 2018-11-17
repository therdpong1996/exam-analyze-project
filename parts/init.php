<?php

    date_default_timezone_set("Asia/Bangkok");

    //PDO
	$_PDO['dsn'] = 'mysql:host=localhost;dbname=project;charset=utf8';
	$_PDO['username'] = 'root';
    $_PDO['password'] = '';

    try {
		$_DB = new PDO($_PDO['dsn'], $_PDO['username'], $_PDO['password']);
	} catch (Exception $ex) {
		$ex->getMessage();
	}
    
    $_G['url'] = 'http://localhost/project/';