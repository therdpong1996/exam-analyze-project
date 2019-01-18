<?php

    @session_start();
    require_once 'member/control/init.php';

    $meta['title'] = 'Computerized Adaptive Testing';

    include_once 'views/header.php';
    include_once 'views/navbar.php';
    include_once 'views/main.php';
    include_once 'views/footer.php';