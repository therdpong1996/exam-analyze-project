<?php

    @session_start();
    require_once 'member/control/init.php';
    $atid = $_GET['atid'];

    $stm = $_DB->prepare("SELECT * FROM articles JOIN subjects ON articles.subject = subjects.subject_id JOIN users ON articles.uid = users.uid WHERE articles.atid = :atid");
    $stm->bindParam(":atid", $atid);
    $stm->execute();
    $row = $stm->fetch(PDO::FETCH_ASSOC);

    $meta['title'] = $row['title'].' | Computerized Adaptive Testing';

    include_once 'views/header.php';
    include_once 'views/navbar.php';
    include_once 'views/article.php';
    include_once 'views/footer.php';