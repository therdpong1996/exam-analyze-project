<?php

    require_once '../member/control/init.php';

    $post = [];

    $stm = $_DB->prepare("SELECT articles.atid,articles.title,articles.content,articles.poston,subjects.subject_title,users.full_name FROM articles JOIN subjects ON articles.subject = subjects.subject_id JOIN users ON articles.uid = users.uid WHERE articles.public = 1 ORDER BY articles.atid DESC");
    $stm->execute();
    while ($rows = $stm->fetch(PDO::FETCH_ASSOC)) {
        array_push($post, $rows);
    }

    $destination = '../static/post.data.json';
    $data = json_encode($data);
    file_put_contents($destination, $data);