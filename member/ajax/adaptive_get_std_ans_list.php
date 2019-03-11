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

$label = $_POST['label'];
$letter_label = explode(' (', $label);
$ans = 1;
switch ($letter_label[0]) {
    case 'A':
        $ans = 1;
        break;
    case 'B':
        $ans = 2;
        break;
    case 'C':
        $ans = 3;
        break;
    case 'D':
        $ans = 4;
        break;
}

$stm = $_DB->prepare('SELECT adaptive_answer_data.time_taken_s,users.full_name,users.stu_id FROM adaptive_answer_data JOIN users ON adaptive_answer_data.uid = users.uid WHERE adaptive_answer_data.answer = :ans AND adaptive_answer_data.subject = :subject AND adaptive_answer_data.session = :session AND adaptive_answer_data.examination = :exam AND adaptive_answer_data.question = :question AND adaptive_answer_data.temp = 0');
$stm->bindParam(':ans', $ans);
$stm->bindParam(':subject', $_POST['subject']);
$stm->bindParam(':session', $_POST['session']);
$stm->bindParam(':exam', $_POST['exam']);
$stm->bindParam(':question', $_POST['qaid']);
$stm->execute();
?>
    <div class="table-responsive">
        <table class="table align-items-center">
            <thead class="thead-light">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Time Taken</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($rows = $stm->fetch(PDO::FETCH_ASSOC)) { ?>
                <tr>
                    <th scope="row">
                        <span class="mb-0 text-sm"><?php echo $rows['stu_id']; ?></span>
                    </th>
                    <td>
                        <span class="mb-0 text-sm"><?php echo $rows['full_name']; ?></span>
                    </td>
                    <td>
                        <span class="mb-0 text-sm"><?php echo round($rows['time_taken_s']/60); ?> min</span>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>