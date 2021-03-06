<!-- Main content -->
<div class="main-content">
    <!-- Top navbar -->
    <nav class="navbar bg-gradient-primary navbar-top navbar-expand-md navbar-dark" id="navbar-main">
        <div class="container-fluid">
        <!-- Brand -->
        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="<?php url('session/'); ?>">Session Analyze <small>(วิเคราะห์ผลการทดสอบ)</small></a>
        <?php require_once 'parts/usermenu.common.php'; ?>
    <!-- Page content -->
    <div class="container-fluid pb-8 pt-5 pt-md-8">
        <div class="row">
        <div class="col-xl-2">
            <a href="?session_id=<?php echo $session['session_id']; ?>&overview" class="btn btn-outline-success mb-1 btn-block <?php echo isset($_GET['overview']) ? 'active' : ''; ?>">Overview</a>
            <?php 
                $stmt22 = $_DB->prepare('SELECT COUNT(score_id) as num FROM session_score WHERE session_id = :session AND exam_id = :exam');
                $stmt22->bindParam(':session', $session['session_id']);
                $stmt22->bindParam(':exam', $session['examination_id']);
                $stmt22->execute();
                $examNum = $stmt22->fetch(PDO::FETCH_ASSOC);
                if($examNum['num'] > 0){
            ?>
            <a href="?session_id=<?php echo $session['session_id']; ?>&scorelist" class="btn btn-outline-warning mb-1 btn-block <?php echo isset($_GET['scorelist']) ? 'active' : ''; ?>">Normal Score</a>
                <?php } if($session['session_adap_active'] == 1 and $session['session_train'] == 1) { ?>
            <a href="?session_id=<?php echo $session['session_id']; ?>&adapscore" class="btn btn-outline-warning mb-1 btn-block <?php echo isset($_GET['adapscore']) ? 'active' : ''; ?>">Adaptive Score</a>
            <a href="?session_id=<?php echo $session['session_id']; ?>&adapsim" class="btn btn-outline-warning mb-2 btn-block <?php echo isset($_GET['adapsim']) ? 'active' : ''; ?>">Simulator</a>
            <?php } ?>
            <div>
            <?php
                if (isset($_GET['n'])) {
                    $n = $_GET['n'];
                }
                $stmt = $_DB->prepare('SELECT * FROM q_and_a WHERE qa_subject = :subject AND qa_exam = :exam AND qa_id = :n LIMIT 1');
                $stmt->bindParam(':subject', $session['examination_subject']);
                $stmt->bindParam(':exam', $session['examination_id']);
                $stmt->bindParam(':n', $n, PDO::PARAM_INT);
                $stmt->execute();
                $exam_row = $stmt->fetch(PDO::FETCH_ASSOC);
                $answer_arr = explode(',', $exam_row['qa_choice_true']);

                if ($session['session_status'] == 1) {
                    $stmt = $_DB->prepare('SELECT DISTINCT(question),qa_id,qa_order,qa_question FROM answer_data JOIN q_and_a ON answer_data.question = q_and_a.qa_id WHERE answer_data.session = :session AND answer_data.temp = 0 ORDER BY q_and_a.qa_order ASC');
                    $stmt->bindParam(':session', $session['session_id']);
                }else{
                    $stmt = $_DB->prepare('SELECT qa_id,qa_order,qa_question FROM q_and_a WHERE q_and_a.qa_exam = :eid ORDER BY q_and_a.qa_order ASC');
                    $stmt->bindParam(':eid', $session['session_exam']);
                }
                $stmt->execute();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            ?>
                    <a style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" href="?session_id=<?php echo $session['session_id']; ?>&n=<?php echo $row['qa_id']; ?>" class="btn btn-outline-primary mb-1 btn-block <?php echo $row['qa_id'] == $n ? 'active' : ''; ?>"><?php echo $row['qa_order']; ?>.<?php echo strip_tags($row['qa_question']); ?></a>
            <?php
                }
            ?>
            </div>
        </div>
        <?php 
            if (isset($_GET['n'])) {
                include_once 'analyze/n.php';
            } elseif (isset($_GET['overview'])) {
                include_once 'analyze/overview.php';
            } elseif (isset($_GET['adapscore'])) {
                include_once 'analyze/adapscore.php';
            } elseif (isset($_GET['stdability'])) {
                include_once 'analyze/stdability.php';
            } elseif (isset($_GET['adapsim'])){
                include_once 'analyze/adapsim.php';
            } else {
                include_once 'analyze/scorelist.php';
            } 
        ?>
    </div>
</div>