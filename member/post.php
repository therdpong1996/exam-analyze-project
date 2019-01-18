<?php
    session_start();
    require_once __DIR__.'/control/init.php';
    if (!isset($_SESSION['auth']) and !isset($_SESSION['username'])) {
        header('Location: '.$_G['url'].'login/');
        exit;
    }

    //USER
    $stm = $_DB->prepare("SELECT * FROM users JOIN users_role_title ON users.role = users_role_title.role_id WHERE users.username = :username LIMIT 1");
    $stm->bindParam(':username', $_SESSION['username'], PDO::PARAM_STR);
    $stm->execute();
    $user_row = $stm->fetch(PDO::FETCH_ASSOC);

    //ARTICLE
    $stm = $_DB->prepare("SELECT * FROM articles JOIN users ON articles.uid = users.uid WHERE articles.atid = :atid LIMIT 1");
    $stm->bindParam(':atid', $_GET['atid'], PDO::PARAM_STR);
    $stm->execute();
    $article = $stm->fetch(PDO::FETCH_ASSOC);

    $_G['title'] = $article['title'];


    include_once __DIR__.'/views/parts/header.common.php';
    include_once __DIR__.'/views/parts/sidemenu.common.php';
    
    if($_GS['init_article']){
        include_once __DIR__.'/views/post.page.php';
    }else{
        include_once __DIR__.'/views/undercons.page.php';
    }

    include_once __DIR__.'/views/parts/footer.content.php';
    include_once __DIR__.'/views/parts/footer.common.php';

?>