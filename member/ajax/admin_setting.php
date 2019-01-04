<?php

header('Content-type: application/json');
session_start();
if (!isset($_SESSION['auth']) and !isset($_SESSION['username']) and $_SESSION['role'] > 1) {
    echo json_encode(['state' => false, 'msg' => 'No permission']);
    exit;
}

require_once '../control/init.php';

if ($_SESSION['role'] != 1) {
    echo json_encode(['state' => false, 'msg' => 'No permission']);
    exit;
}

$regis = isset($_POST['init_regis'])?$_POST['init_regis']:0;
$article = isset($_POST['init_article'])?$_POST['init_article']:0;
$subject = isset($_POST['init_subject'])?$_POST['init_subject']:0;
$exam = isset($_POST['init_exam'])?$_POST['init_exam']:0;
$session = isset($_POST['init_session'])?$_POST['init_session']:0;
$testing = isset($_POST['init_testing'])?$_POST['init_testing']:0;
$graph = isset($_POST['init_graph'])?$_POST['init_graph']:0;

$stm = $_DB->prepare("UPDATE setting SET init_regis = :regis, init_article = :article, init_subject = :subject, init_exam = :exam, init_session = :session, init_testing = :testing, init_graph = :graph");
$stm->bindParam(":regis", $regis);
$stm->bindParam(":article", $article);
$stm->bindParam(":subject", $subject);
$stm->bindParam(":exam", $exam);
$stm->bindParam(":session", $session);
$stm->bindParam(":testing", $testing);
$stm->bindParam(":graph", $graph);
$stm->execute();

echo json_encode(['state'=>1]); exit;