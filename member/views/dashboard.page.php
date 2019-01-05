<!-- Main content -->
<div class="main-content">
    <!-- Top navbar -->
    <nav class="navbar bg-gradient-primary navbar-top navbar-expand-md navbar-dark" id="navbar-main">
        <div class="container-fluid">
        <!-- Brand -->
        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="#">Timeline</a>
        <?php require_once 'parts/usermenu.common.php'; ?>
        
        <div class="container-fluid pb-8 pt-5 pt-md-8">
            <div class="row">
                <div class="col-xl-12">
                    <?php
                        if ($user_row['role'] == 2) {
                            $stm1 = $_DB->prepare("SELECT subject_id FROM subject_owner WHERE uid = :uid");
                        }elseif($user_row['role'] == 3){
                            $stm1 = $_DB->prepare("SELECT subject_id FROM student_ubject WHERE uid = :uid");
                        }
                        $stm1->bindParam(":uid", $user_row['uid']);
                        $stm1->execute();
                        while ($orows = $stm1->fetch(PDO::FETCH_ASSOC)) {
                            $stm = $_DB->prepare('SELECT * FROM timeline JOIN users ON timeline.taken = users.uid WHERE subject = :id AND for_time = :role');
                            $stm->bindParam(':id', $orows['subject_id']);
                            $stm->bindParam(':role', $user_row['role']);
                            $stm->execute();
                            while ($rows = $stm->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <?php if($rows['type']=='article'){
                                $stmt = $_DB->prepare("SELECT * FROM articles JOIN subjects ON articles.subject = subjects.subject_id WHERE articles.atid = :id");
                                $stmt->bindParam(':id', $rows['content_id']);
                                $stmt->execute();
                                $row = $stmt->fetch(PDO::FETCH_ASSOC);    
                            ?>
                            <small class="text-muted"><?php __($rows['ontime']); ?></small>
                            <p class="mt-3"><div class="mr-2 avatar avatar-sm rounded-circle"><img avatar="<?php echo $rows['email']; ?>" /> <?php __($rows['full_name']); ?></div></p>
                            <p>ได้สร้างบทความใหม่ "<?php __($row['title']); ?>"</p>
                            <p>รายวิชา "<?php __($row['subject_title']); ?>"</p>
                            <a href="#" class="btn btn-info">อ่าน</a>
                            <?php }elseif($rows['type']=='exam'){
                                $stmt = $_DB->prepare("SELECT examinations.examination_id,examinations.examination_title,subjects.subject_title FROM examinations JOIN subjects ON examinations.examination_subject = subjects.subject_id WHERE examinations.examination_id = :id");
                                $stmt->bindParam(':id', $rows['content_id']);
                                $stmt->execute();
                                $row = $stmt->fetch(PDO::FETCH_ASSOC);    
                            ?>
                            <small class="text-muted"><?php __($rows['ontime']); ?></small>
                            <p class="mt-3"><div class="mr-2 avatar avatar-sm rounded-circle"><img avatar="<?php echo $rows['email']; ?>"> <?php __($rows['full_name']); ?></div></p>
                            <p>ได้สร้างชุดข้อสอบใหม่ "<?php __($row['examination_title']); ?>"</p>
                            <p>รายวิชา "<?php __($row['subject_title']); ?>"</p>
                            <?php }elseif($rows['type']=='session'){
                                $stmt = $_DB->prepare("SELECT sessions.session_id,sessions.session_adap_active,examinations.examination_title,subjects.subject_title FROM sessions JOIN examinations ON sessions.session_exam = examinations.examination_id JOIN subjects ON examinations.examination_subject = subjects.subject_id WHERE sessions.session_id = :id");
                                $stmt->bindParam(':id', $rows['content_id']);
                                $stmt->execute();
                                $row = $stmt->fetch(PDO::FETCH_ASSOC);    
                            ?>
                            <small class="text-muted"><?php __($rows['ontime']); ?></small>
                            <p class="mt-3"><div class="mr-2 avatar avatar-sm rounded-circle"><img avatar="<?php echo $rows['email']; ?>"> <?php __($rows['full_name']); ?></div></p>
                            <p>ได้สร้างเซสชั่นการทำข้อสอบของชุดข้อสอบ "<?php __($row['examination_title']); ?>"</p>
                            <p>รายวิชา "<?php __($row['subject_title']); ?>"</p>

                            <?php }elseif($rows['type']=='train'){
                                $stmt = $_DB->prepare("SELECT adaptive_table.std_number,examinations.examination_title,subjects.subject_title FROM adaptive_table JOIN examinations ON adaptive_table.exam_id = examinations.examination_id JOIN subjects ON examinations.examination_subject = subjects.subject_id WHERE adaptive_table.score_id = :id");
                                $stmt->bindParam(':id', $rows['content_id']);
                                $stmt->execute();
                                $row = $stmt->fetch(PDO::FETCH_ASSOC);    
                            ?>
                            <small class="text-muted"><?php __($rows['ontime']); ?></small>
                            <p class="mt-3"><div class="mr-2 avatar avatar-sm rounded-circle"><img avatar="<?php echo $rows['email']; ?>"> <?php __($rows['full_name']); ?></div></p>
                            <p>ได้สร้าง Train ข้อมูลของข้อสอบชุด "<?php __($row['examination_title']); ?>"</p>
                            <p>รายวิชา "<?php __($row['subject_title']); ?>"</p>

                            <?php }elseif($rows['type']=='solve'){
                                $stmt = $_DB->prepare("SELECT session_score.score,session_score.score_full,examinations.examination_title,subjects.subject_title FROM session_score JOIN examinations ON session_score.exam_id = examinations.examination_id JOIN subjects ON session_score.subject_id = subjects.subject_id WHERE session_score.score_id = :id");
                                $stmt->bindParam(':id', $rows['content_id']);
                                $stmt->execute();
                                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                            ?>
                            <small class="text-muted"><?php __($rows['ontime']); ?></small>
                            <p class="mt-3"><div class="mr-2 avatar avatar-sm rounded-circle"><img avatar="<?php echo $rows['email']; ?>"> <?php __($rows['full_name']); ?></div></p>
                            <p>ได้ทำข้อสอบ "<?php __($row['examination_title']); ?>"</p>
                            <p>รายวิชา "<?php __($row['subject_title']); ?>"</p>
                            <p>ได้คะแนน <?php __($row['score']); ?>/<?php __($row['score_full']); ?></p>
                            <?php }elseif($rows['type']=='solve-a'){
                                $stmt = $_DB->prepare("SELECT adaptive_session_score.score,adaptive_session_score.score_full,examinations.examination_title,subjects.subject_title FROM adaptive_session_score JOIN examinations ON adaptive_session_score.exam_id = examinations.examination_id JOIN subjects ON adaptive_session_score.subject_id = subjects.subject_id WHERE adaptive_session_score.score_id = :id");
                                $stmt->bindParam(':id', $rows['content_id']);
                                $stmt->execute();
                                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                            ?>
                            <small class="text-muted"><?php __($rows['ontime']); ?></small>
                            <p class="mt-3"><div class="mr-2 avatar avatar-sm rounded-circle"><img avatar="<?php echo $rows['email']; ?>"> <?php __($rows['full_name']); ?></div></p>
                            <p>ได้สร้าง Train ข้อมูลของข้อสอบชุด "<?php __($row['examination_title']); ?>"</p>
                            <p>รายวิชา "<?php __($row['subject_title']); ?>"</p>
                            <p>ได้คะแนน <?php __($row['score']); ?>/<?php __($row['score_full']); ?></p>
                            <?php } ?>
                        </div>
                    </div>
                    <?php } } ?>
                </div>
            </div>
        </div>