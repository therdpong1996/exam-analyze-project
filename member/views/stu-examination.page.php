<!-- Main content -->
<div class="main-content">
    <!-- Top navbar -->
    <nav class="navbar bg-gradient-primary navbar-top navbar-expand-md navbar-dark" id="navbar-main">
        <div class="container-fluid">
        <!-- Brand -->
        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block">Examinations <small>(ข้อสอบที่สามารถทำได้)</small></a>
        <?php require_once 'parts/usermenu.common.php'; ?>

    <!-- Page content -->
    <div class="container-fluid pb-5 pt-5 pt-md-8">
        <div class="row">
            <?php
                $num_rows = 0;
                $stm = $_DB->prepare('SELECT * FROM sessions JOIN examinations ON sessions.session_exam = examinations.examination_id JOIN subjects ON examinations.examination_subject = subjects.subject_id WHERE sessions.session_active = 1 AND subjects.subject_id IN (SELECT DISTINCT(subject_id) FROM student_subject WHERE uid = :uid) ORDER BY sessions.session_start ASC');
                $stm->bindParam(":uid", $user_row['uid']);
                $stm->execute();
                while ($rows = $stm->fetch(PDO::FETCH_ASSOC)) {
                    $num_rows++;
            ?>
                    <div class="col-xl-6" id="exam-content">
                        <div class="card shadow mb-3">
                            <div class="card-header">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h2 class="mb-0"><?php echo $rows['examination_title']; ?> <?php if ($rows['session_adap_active']): ?><span class="badge badge-primary">Adaptive</span><?php endif; ?> <?php if ($rows['session_password']): ?><small class="badge badge-warning">ข้อสอบนี้มีการกำหนดรหัสผ่าน</small><?php endif; ?></h2>
                                        <h6 class="text-uppercase text-muted ls-1 mb-1"><?php echo $rows['subject_title']; ?></h6>
                                        <?php if ($rows['session_adap_active']): ?>
                                            <small class="text-danger">ข้อสอบนี้จะปรับความยาก-ง่ายตามความสามารถของผู้ทดสอบ</small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-8">
                                        <p class="text-muted">
                                            <?php echo $rows['examination_detail']; ?>
                                        </p>
                                        <small>เริ่มต้น: <span class="text-success"><?php echo date('l j M, Y', strtotime($rows['session_start'])); ?></span></small><br>
                                        <small>สิ้นสุด: <span class="text-danger"><?php echo date('l j M, Y', strtotime($rows['session_end'])); ?></span></small><br>
                                        <small>โดย: <?php
                                            $stm2 = $_DB->prepare("SELECT uid FROM subject_owner WHERE subject_id = :id");
                                            $stm2->bindParam(":id", $rows['subject_id']);
                                            $stm2->execute();
                                            $teach = [];
                                            while ($urow = $stm2->fetch(PDO::FETCH_ASSOC)){
                                                $stm3c = $_DB->prepare("SELECT full_name FROM users WHERE uid = :uid");
                                                $stm3c->bindParam(":uid", $urow['uid']);
                                                $stm3c->execute();
                                                $userow = $stm3c->fetch(PDO::FETCH_ASSOC);
                                                array_push($teach, $userow['full_name']);
                                            }
                                            __(implode(', ', $teach));
                                            ?></small>
                                    </div>
                                    <div class="col-4 text-center">
                                        <small>เวลาในการทำ <strong class="text-success"><?php echo $rows['session_timeleft']; ?></strong> นาที</small>

                                        <?php
                                            if($rows['session_adap_active']) {
                                                $stmt = $_DB->prepare('SELECT * FROM adaptive_session_score WHERE session_id = :session AND exam_id = :exam AND subject_id = :subject AND uid = :uid LIMIT 1');
                                                $stmt->bindParam(':session', $rows['session_id']);
                                                $stmt->bindParam(':exam', $rows['examination_id']);
                                                $stmt->bindParam(':subject', $rows['subject_id']);
                                                $stmt->bindParam(':uid', $user_row['uid']);
                                                $stmt->execute();
                                                $crow = $stmt->fetch(PDO::FETCH_ASSOC); 
                                                $scfull = $rows['session_adap_number'];
                                            }else{
                                                $stmt = $_DB->prepare('SELECT * FROM session_score WHERE session_id = :session AND exam_id = :exam AND subject_id = :subject AND uid = :uid LIMIT 1');
                                                $stmt->bindParam(':session', $rows['session_id']);
                                                $stmt->bindParam(':exam', $rows['examination_id']);
                                                $stmt->bindParam(':subject', $rows['subject_id']);
                                                $stmt->bindParam(':uid', $user_row['uid']);
                                                $stmt->execute();
                                                $crow = $stmt->fetch(PDO::FETCH_ASSOC);
                                                $scfull = $crow['score_full'];
                                            } 
                                        ?>

                                        <?php if ($crow['score_id']) { ?>
                                            <?php if ($rows['session_solve']) { ?>
                                            <?php if ($rows['session_adap_active']) { ?>
                                                <a class="btn btn-outline-success btn-block mt-4 pt-4 pb-4" href="<?php url('solve-examination-adaptive/'.$rows['session_id']); ?>"><?php echo $crow['score']; ?>/<?php echo $scfull; ?></a>
                                            <?php } else { ?>
                                                <a class="btn btn-outline-success btn-block mt-4 pt-4 pb-4" href="<?php url('solve-examination/'.$rows['session_id']); ?>"><?php echo $crow['score']; ?>/<?php echo $scfull; ?></a>
                                            <?php } } else { ?>
                                                <button class="btn btn-outline-success disabled btn-block mt-4 pt-4 pb-4"><?php echo $crow['score']; ?>/<?php echo $scfull; ?></button>
                                            <?php } ?>
                                        <?php } else { ?>
                                        <?php
                                            if (timebetween($rows['session_start'], $rows['session_end'])) {

                                                if ($rows['session_adap_active']) {
                                                    $stm6 = $_DB->prepare('SELECT * FROM adaptive_time_remaining JOIN sessions ON adaptive_time_remaining.session = sessions.session_id JOIN examinations ON sessions.session_exam = examinations.examination_id WHERE adaptive_time_remaining.uid = :uid AND adaptive_time_remaining.session = :session AND adaptive_time_remaining.time_status = 0 LIMIT 1');
                                                    $stm6->bindParam(':uid', $user_row['uid']);
                                                    $stm6->bindParam(':session', $rows['session_id']);
                                                    $stm6->execute();
                                                    $ongoing = $stm6->fetch(PDO::FETCH_ASSOC);
                                                }else{
                                                    $stm6 = $_DB->prepare('SELECT * FROM time_remaining JOIN sessions ON time_remaining.session = sessions.session_id JOIN examinations ON sessions.session_exam = examinations.examination_id WHERE time_remaining.uid = :uid AND time_remaining.session = :session AND time_remaining.time_status = 0 LIMIT 1');
                                                    $stm6->bindParam(':uid', $user_row['uid']);
                                                    $stm6->bindParam(':session', $rows['session_id']);
                                                    $stm6->execute();
                                                    $ongoing = $stm6->fetch(PDO::FETCH_ASSOC);
                                                }

                                            if ($ongoing['time_remaining'] > 0) {
                                                $textbtn = 'อยู่ระหว่างการทำ';
                                            } else {
                                                $textbtn = 'เข้าทดสอบ';
                                            }
                                            if ($rows['session_adap_active']) {
                                        ?>
                                            <a href="<?php url('doing-examination-adaptive/'.$rows['session_id']); ?>" class="btn btn-primary btn-block mt-4 pt-4 pb-4"><?php echo $textbtn; ?></a>
                                        <?php  } else { ?>
                                            <a href="<?php url('doing-examination/'.$rows['session_id']); ?>" class="btn btn-primary btn-block mt-4 pt-4 pb-4"><?php echo $textbtn; ?></a>
                                        <?php } } else { ?>
                                            <div class="text-danger mt-4 pt-4 pb-4">ไม่อยู่ในช่วงเวลาการทดสอบ</div>
                                        <?php } ?>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            <?php }
                if($num_rows == 0){
            ?>
                <div class="col-12">
                    <div class="mt-5 mb-5 pt-5 pb-5 text-center">
                        <i class="fa fa-times-circle fa-10x text-muted"></i>
                        <h2 class="mt-4 text-muted">ไม่พบข้อมูลการสอบ</h2>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
