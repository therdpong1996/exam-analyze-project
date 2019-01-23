<?php

    @session_start();
    require_once 'member/control/init.php';
    $tag = $_GET['tag'];

    $meta['title'] = 'Tags | Computerized Adaptive Testing';

    include_once 'views/header.php';
    include_once 'views/navbar.php';
    include_once 'views/tags.php';
    include_once 'views/footer.php';