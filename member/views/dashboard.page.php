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
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-12">
                    <?php
                        $num_rows = 0;
                        if ($user_row['role'] == 2) {
                            $stm = $_DB->prepare('SELECT * FROM timeline JOIN users ON timeline.taken = users.uid WHERE subject IN (SELECT subject_id FROM subject_owner WHERE uid = :uid) AND for_time = :role ORDER BY ontime DESC');
                        }elseif($user_row['role'] == 3){
                            $stm = $_DB->prepare('SELECT * FROM timeline JOIN users ON timeline.taken = users.uid WHERE subject IN (SELECT subject_id FROM student_subject WHERE uid = :uid) AND for_time = :role ORDER BY ontime DESC');
                        }
                        $stm->bindParam(':role', $user_row['role']);
                        $stm->bindParam(":uid", $user_row['uid']);
                        $stm->execute();
                        while ($rows = $stm->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                            <?php if($rows['type']=='article'){
                                $num_rows++;
                                $stmt = $_DB->prepare("SELECT * FROM articles JOIN subjects ON articles.subject = subjects.subject_id WHERE articles.atid = :id");
                                $stmt->bindParam(':id', $rows['content_id']);
                                $stmt->execute();
                                $row = $stmt->fetch(PDO::FETCH_ASSOC);    
                            ?>
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-1 text-center pt-1">
                                                <div class="avatar avatar-sm rounded-circle"><img avatar="<?php echo $rows['email']; ?>"></div>
                                            </div>
                                            <div class="col-11 pl-1">
                                                <?php __($rows['full_name']); ?><br><small class="text-muted"><?php __($rows['ontime']); ?></small>
                                            </div>
                                        </div>    
                                    </div>
                                    <p>ได้สร้างบทความใหม่ "<strong><?php __($row['title']); ?></strong>" ในรายวิชา "<?php __($row['subject_title']); ?>"</p>
                                    <div class="article-content-preview">
                                        <?php echo iconv_substr(strip_tags($row['content']), 0,500, "UTF-8"); ?>...
                                    </div>
                                    <a href="<?php url('post/'.$rows['content_id']); ?>" class="btn btn-info mt-3">อ่านบทความ</a>
                                </div>
                            </div>
                            <?php }elseif($rows['type']=='exam'){
                                $num_rows++;
                                $stmt = $_DB->prepare("SELECT examinations.examination_id,examinations.examination_title,subjects.subject_title FROM examinations JOIN subjects ON examinations.examination_subject = subjects.subject_id WHERE examinations.examination_id = :id");
                                $stmt->bindParam(':id', $rows['content_id']);
                                $stmt->execute();
                                $row = $stmt->fetch(PDO::FETCH_ASSOC);    
                            ?>
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-1 text-center pt-1">
                                                <div class="avatar avatar-sm rounded-circle"><img avatar="<?php echo $rows['email']; ?>"></div>
                                            </div>
                                            <div class="col-11 pl-1">
                                                <?php __($rows['full_name']); ?><br><small class="text-muted"><?php __($rows['ontime']); ?></small>
                                            </div>
                                        </div>    
                                    </div>
                                    <p>ได้สร้างชุดข้อสอบใหม่ "<strong><?php __($row['examination_title']); ?></strong>" ในรายวิชา "<?php __($row['subject_title']); ?>"</p>
                                    <a class="btn btn-primary mt-3" href="<?php url('examination/qa/?examination_id='.$rows['content_id']); ?>">ตรวจสอบคำถาม-คำตอบ</a>
                                </div>
                            </div>
                            <?php }elseif($rows['type']=='session'){
                                $num_rows++;
                                $stmt = $_DB->prepare("SELECT sessions.session_id,sessions.session_adap_active,examinations.examination_title,subjects.subject_title FROM sessions JOIN examinations ON sessions.session_exam = examinations.examination_id JOIN subjects ON examinations.examination_subject = subjects.subject_id WHERE sessions.session_id = :id");
                                $stmt->bindParam(':id', $rows['content_id']);
                                $stmt->execute();
                                $row = $stmt->fetch(PDO::FETCH_ASSOC);    
                            ?>
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-1 text-center pt-1">
                                                <div class="avatar avatar-sm rounded-circle"><img avatar="<?php echo $rows['email']; ?>"></div>
                                            </div>
                                            <div class="col-11 pl-1">
                                                <?php __($rows['full_name']); ?><br><small class="text-muted"><?php __($rows['ontime']); ?></small>
                                            </div>
                                        </div>    
                                    </div>
                                    <p>ได้สร้างเซสชั่นการทำข้อสอบของชุดข้อสอบ "<strong><?php __($row['examination_title']); ?></strong>" ในรายวิชา "<?php __($row['subject_title']); ?>"</p>
                                    <a class="btn btn-primary mt-3" href="<?php url('session/analyze/?session_id='.$rows['content_id'].'&overview'); ?>">ดูเกี่ยวกับเซสชั่นนี้</a>
                                </div>
                            </div>
                            <?php }elseif($rows['type']=='solve' and $rows['taken'] == $user_row['uid']){
                                $num_rows++;
                                $stmt = $_DB->prepare("SELECT session_score.score,session_score.score_full,examinations.examination_title,subjects.subject_title FROM session_score JOIN examinations ON session_score.exam_id = examinations.examination_id JOIN subjects ON session_score.subject_id = subjects.subject_id WHERE session_score.score_id = :id AND session_score.uid = :uid");
                                $stmt->bindParam(':id', $rows['content_id']);
                                $stmt->bindParam(':uid', $user_row['uid']);
                                $stmt->execute();
                                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                            ?>
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-1 text-center pt-1">
                                                <div class="avatar avatar-sm rounded-circle"><img avatar="<?php echo $rows['email']; ?>"></div>
                                            </div>
                                            <div class="col-11 pl-1">
                                                <?php __($rows['full_name']); ?><br><small class="text-muted"><?php __($rows['ontime']); ?></small>
                                            </div>
                                        </div>    
                                    </div>
                                    <p>ได้ทำข้อสอบ "<?php __($row['examination_title']); ?>" ในรายวิชา "<?php __($row['subject_title']); ?>"</p>
                                    <h1>ได้คะแนน <?php __($row['score']); ?>/<?php __($row['score_full']); ?></h1>
                                </div>
                            </div>
                            <?php }elseif($rows['type']=='solve-a' and $rows['taken'] == $user_row['uid']){
                                $num_rows++;
                                $stmt = $_DB->prepare("SELECT adaptive_session_score.score,adaptive_session_score.score_full,examinations.examination_title,subjects.subject_title FROM adaptive_session_score JOIN examinations ON adaptive_session_score.exam_id = examinations.examination_id JOIN subjects ON adaptive_session_score.subject_id = subjects.subject_id WHERE adaptive_session_score.score_id = :id AND adaptive_session_score.uid = :uid");
                                $stmt->bindParam(':id', $rows['content_id']);
                                $stmt->bindParam(':uid', $user_row['uid']);
                                $stmt->execute();
                                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                            ?>
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-1 text-center pt-1">
                                                <div class="avatar avatar-sm rounded-circle"><img avatar="<?php echo $rows['email']; ?>"></div>
                                            </div>
                                            <div class="col-11 pl-1">
                                                <?php __($rows['full_name']); ?><br><small class="text-muted"><?php __($rows['ontime']); ?></small>
                                            </div>
                                        </div>    
                                    </div>
                                    <p>ได้ทำข้อสอบ "<?php __($row['examination_title']); ?>" ในรายวิชา "<?php __($row['subject_title']); ?>"</p>
                                    <h1>ได้คะแนน <?php __($row['score']); ?>/<?php __($row['score_full']); ?></h1>
                                </div>
                            </div>
                            <?php } ?>
                    <?php }
                        if($num_rows == 0){
                    ?>
                        <div class="mt-5 mb-5 pt-5 pb-5 text-center">
                            <i class="fa fa-times-circle fa-10x text-muted"></i>
                            <h2 class="mt-4 text-muted">ไม่พบข้อมูลในไทม์ไลน์</h2>
                        </div>
                    <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 affix">
                    <div class="row">
                        <?php if($user_row['role'] == 2){ ?>
                        <div class="col-6 text-center mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <?php
                                        $stm = $_DB->prepare("SELECT COUNT(atid) AS n FROM articles WHERE uid = :uid");
                                        $stm->bindParam(":uid", $user_row['uid']);
                                        $stm->execute();
                                        $myar = $stm->fetch(PDO::FETCH_ASSOC);
                                    ?>
                                    <h2><?php echo $myar['n']; ?></h2>
                                    <small>บทความของฉัน</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 text-center mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <?php
                                        $stm = $_DB->prepare("SELECT COUNT(DISTINCT(subject_id)) AS n FROM subject_owner WHERE uid = :uid");
                                        $stm->bindParam(":uid", $user_row['uid']);
                                        $stm->execute();
                                        $mysub = $stm->fetch(PDO::FETCH_ASSOC);
                                    ?>
                                    <h2><?php echo $mysub['n']; ?></h2>
                                    <small>รายวิชา</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 text-center mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <?php
                                        $stm = $_DB->prepare("SELECT COUNT(examination_id) AS n FROM examinations WHERE examination_subject IN (SELECT DISTINCT(subject_id) FROM subject_owner WHERE uid = :uid)");
                                        $stm->bindParam(":uid", $user_row['uid']);
                                        $stm->execute();
                                        $myexam = $stm->fetch(PDO::FETCH_ASSOC);
                                    ?>
                                    <h2><?php echo $myexam['n']; ?></h2>
                                    <small>ชุดข้อสอบ</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 text-center mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <?php
                                        $stm = $_DB->prepare("SELECT COUNT(session_id) AS n FROM sessions WHERE session_exam IN (SELECT DISTINCT(examination_id) FROM examinations WHERE examination_subject IN (SELECT DISTINCT(subject_id) FROM subject_owner WHERE uid = :uid))");
                                        $stm->bindParam(":uid", $user_row['uid']);
                                        $stm->execute();
                                        $myses = $stm->fetch(PDO::FETCH_ASSOC);
                                    ?>
                                    <h2><?php echo $myses['n']; ?></h2>
                                    <small>เซสชั่น</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">บทความล่าสุดของฉัน</div>
                                <div class="card-body">
                                    <ul>
                                    <?php
                                        $stm = $_DB->prepare("SELECT atid,title FROM articles WHERE uid = :uid ORDER BY atid DESC LIMIT 5");
                                        $stm->bindParam(":uid", $user_row['uid']);
                                        $stm->execute();
                                        while($arows = $stm->fetch(PDO::FETCH_ASSOC)){
                                    ?>
                                    <li>
                                        <a href=""><?php echo $arows['title']; ?></a>
                                    </li>
                                    <?php
                                        }
                                    ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <?php }elseif($user_row['role'] == 3){ ?>
                        <div class="col-6 text-center mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <?php
                                        $stm = $_DB->prepare("SELECT COUNT(DISTINCT(subject_id)) AS n FROM student_subject WHERE uid = :uid");
                                        $stm->bindParam(":uid", $user_row['uid']);
                                        $stm->execute();
                                        $mysub = $stm->fetch(PDO::FETCH_ASSOC);
                                    ?>
                                    <h2><?php echo $mysub['n']; ?></h2>
                                    <small>รายวิชา</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 text-center mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <?php
                                        $stm = $_DB->prepare("SELECT COUNT(session_id) AS n FROM sessions WHERE session_exam IN (SELECT DISTINCT(examination_id) FROM examinations WHERE sessions.session_active = 1 AND examination_subject IN (SELECT DISTINCT(subject_id) FROM student_subject WHERE uid = :uid))");
                                        $stm->bindParam(":uid", $user_row['uid']);
                                        $stm->execute();
                                        $myses = $stm->fetch(PDO::FETCH_ASSOC);
                                    ?>
                                    <h2><?php echo $myses['n']; ?></h2>
                                    <small>เซสชั่น</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">การทดสอบที่สามารถทำได้</div>
                                <div class="card-body">
                                    <ul>
                                    <?php
                                        $num_rows = 0;
                                        $stm = $_DB->prepare('SELECT session_title,examination_title FROM sessions JOIN examinations ON sessions.session_exam = examinations.examination_id JOIN subjects ON examinations.examination_subject = subjects.subject_id WHERE sessions.session_active = 1 AND subjects.subject_id IN (SELECT DISTINCT(subject_id) FROM student_subject WHERE uid = :uid) ORDER BY sessions.session_start ASC');
                                        $stm->bindParam(":uid", $user_row['uid']);
                                        $stm->execute();
                                        while ($rows = $stm->fetch(PDO::FETCH_ASSOC)) {
                                            $num_rows++;
                                    ?>
                                    <li>
                                        <?php echo $rows['session_title']; ?> [<?php echo $rows['examination_title']; ?>]
                                    </li>
                                    <?php }
                                        if($num_rows == 0){
                                    ?>
                                    <li>
                                        ไม่มีการทดสอบ
                                    </li>
                                    <?php } ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>