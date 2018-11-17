<?php 
    require_once 'control/init.php';
	session_start();
	session_destroy();
	header('Location: '.$_G['url'].'login/');