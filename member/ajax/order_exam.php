<?php
    session_start();
    if(!isset($_SESSION['auth']) and !isset($_SESSION['username']) and $_SESSION['role'] > 2){
        echo json_encode(['state'=>false,'msg'=>'No permission']); exit;
    }

    require_once '../control/init.php';

    if($_SESSION['role'] != 2){
        echo json_encode(['state'=>false,'msg'=>'No permission']); exit;
    }
    $i = 1;
    foreach ($_POST['qa_id'] as $id) {
        $stm = $_DB->prepare("UPDATE q_and_a SET qa_order = :i WHERE qa_id = :id");
        $stm->bindParam(':i', $i, PDO::PARAM_INT);
        $stm->bindParam(':id', $id, PDO::PARAM_INT);
        $stm->execute();
        $i++;
    }

    $stmt = $_DB->prepare('SELECT * FROM q_and_a WHERE qa_subject = :subject AND qa_exam = :exam AND qa_delete = 0 ORDER BY qa_order ASC');
    $stmt->bindParam(':subject', $_POST['subject']);
    $stmt->bindParam(':exam', $_POST['exam']);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
?>
        <a style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; cursor: move;" href="?examination_id=<?php echo $exam['examination_id']; ?>&n=<?php echo $row['qa_id']; ?>" class="btn text-left list-exam-item btn-outline-primary mb-2 btn-block <?php echo $row['qa_id'] == $n ? 'active' : ''; ?>" exam-id="<?php echo $row['qa_id']; ?>"><span id="exam-order"><?php echo $row['qa_order']; ?></span>.<?php echo strip_tags($row['qa_question']); ?> </a>
<?php
    }
