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
            <a href="?session_id=<?php echo $session['session_id']; ?>&model" class="btn btn-outline-info mb-1 btn-block <?php echo isset($_GET['model']) ? 'active' : ''; ?>">Train Model</a>
            <a href="?session_id=<?php echo $session['session_id']; ?>&scorelist" class="btn btn-outline-warning mb-1 btn-block <?php echo isset($_GET['scorelist']) ? 'active' : ''; ?>">Score by Student</a>
            <div class="exam-scollbar pr-1" style="height:500px;">
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

                $stmt = $_DB->prepare('SELECT * FROM q_and_a WHERE qa_subject = :subject AND qa_exam = :exam ORDER BY qa_order ASC');
                $stmt->bindParam(':subject', $session['examination_subject']);
                $stmt->bindParam(':exam', $session['examination_id']);
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
            if (isset($_GET['n']) and !isset($_GET['overview']) and !isset($_GET['scorelist']) and !isset($_GET['model'])) {
                include_once 'analyze/n.php';
            } elseif (!isset($_GET['n']) and isset($_GET['overview']) and !isset($_GET['scorelist']) and !isset($_GET['model'])) {
                include_once 'analyze/overview.php';
            } elseif (!isset($_GET['n']) and !isset($_GET['overview']) and !isset($_GET['scorelist']) and isset($_GET['model'])) {
                include_once 'analyze/model.php';
            } else {
                include_once 'analyze/scorelist.php';
            } 
        ?>
    </div>
</div>