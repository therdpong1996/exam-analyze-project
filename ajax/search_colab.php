<?php

header('Content-type: application/json');
session_start();
if (!isset($_SESSION['auth']) and !isset($_SESSION['username']) and $_SESSION['role'] > 2) {
    echo json_encode(['state' => false, 'msg' => 'No permission']);
    exit;
}

require_once '../control/init.php';

if ($_SESSION['role'] != 2) {
    echo json_encode(['state' => false, 'msg' => 'No permission']);
    exit;
}

$search_key = '%'.$_POST['val'].'%';

$stm = $_DB->prepare("SELECT * FROM users WHERE username LIKE ? OR full_name LIKE ? AND role = 2");
$stm->bindParam(1, $search_key);
$stm->bindParam(2, $search_key);
$stm->execute();

while ($row = $stm->fetch(PDO::FETCH_ASSOC)){ ?>

    <span class="badge badge-primary" onclick="addcoldata(<?php __($row['uid']);?>, '<?php __($row['full_name']);?>')"><?php __($row['full_name']);?></span>

<?php
}