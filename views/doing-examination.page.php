<!-- Main content -->
<div class="main-content">
    <!-- Top navbar -->
    <nav class="navbar bg-gradient-primary navbar-top navbar-expand-md navbar-dark" id="navbar-main">
      <div class="container-fluid">
        <!-- Brand -->
        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block"><?php echo $session['examination_title']; ?></a>
        <?php require_once 'parts/usermenu.common.php'; ?>

    <!-- Page content -->
    <div class="container-fluid pb-5 pt-5 pt-md-8">
      <div class="row">
        <div class="col-xl-1">
            <?php
                $n = (isset($_GET['n'])?$_GET['n']-1:0);
                $stmt = $_DB->prepare("SELECT * FROM q_and_a WHERE qa_subject = :subject AND qa_exam = :exam LIMIT :n, 1");
                $stmt->bindParam(':subject', $session['examination_subject']);
                $stmt->bindParam(':exam', $session['examination_id']);
                $stmt->bindParam(':n', $n, PDO::PARAM_INT);
                $stmt->execute();
                $exam_row = $stmt->fetch(PDO::FETCH_ASSOC);

                $stmt2 = $_DB->prepare("SELECT COUNT(qa_id) AS C FROM q_and_a WHERE qa_subject = :subject AND qa_exam = :exam");
                $stmt2->bindParam(':subject', $session['examination_subject']);
                $stmt2->bindParam(':exam', $session['examination_id']);
                $stmt2->execute();
                $max = $stmt2->fetch(PDO::FETCH_ASSOC);

                //TIME
                $time_tt = time();
                $stmt3 = $_DB->prepare("SELECT * FROM time_remaining WHERE session = :session AND uid = :uid");
                $stmt3->bindParam(':session', $session['session_id']);
                $stmt3->bindParam(':uid', $user_row['uid']);
                $stmt3->execute();
                $time_re = $stmt3->fetch(PDO::FETCH_ASSOC);
                if ($time_re['time_remaining']) {

                    $time_ree = $time_re['time_remaining']-($time_tt - $time_re['time_update1']);
                    $time_ree = ($time_ree<=0)?0:$time_ree;
                    if ($time_tt > $time_re['time_start']+($session['session_timeleft']*60)) {
                        $time_ree = 0;
                    }

                }else{
                    $time_ree = $session['session_timeleft']*60;
                    $stmt3 = $_DB->prepare("INSERT INTO time_remaining (uid,session,time_start,time_update1,time_remaining) VALUES (:uid, :session, :start, :updatet1, :time_re)");
                    $stmt3->bindParam(':session', $session['session_id']);
                    $stmt3->bindParam(':uid', $user_row['uid']);
                    $stmt3->bindParam(':start', $time_tt);
                    $stmt3->bindParam(':updatet1', $time_tt);
                    $stmt3->bindParam(':time_re', $time_ree);
                    $stmt3->execute();
                    $max = $stmt3->fetch(PDO::FETCH_ASSOC);
                }

                $stm = $_DB->prepare("SELECT * FROM answer_data WHERE uid = :uid AND question = :question AND subject = :subject AND examination = :exam AND session = :session LIMIT 1");
                $stm->bindParam(':uid', $user_row['uid'], PDO::PARAM_INT);
                $stm->bindParam(':question', $exam_row['qa_id'], PDO::PARAM_INT);
                $stm->bindParam(':subject', $session['examination_subject'], PDO::PARAM_INT);
                $stm->bindParam(':exam', $session['examination_id'], PDO::PARAM_INT);
                $stm->bindParam(':session', $session['session_id'], PDO::PARAM_INT);
                $stm->execute();
                $answer = $stm->fetch(PDO::FETCH_ASSOC);
                $exami = 1;
                $not = 0;
                $stmt = $_DB->prepare("SELECT * FROM q_and_a WHERE qa_subject = :subject AND qa_exam = :exam ORDER BY qa_id ASC");
                $stmt->bindParam(':subject', $session['examination_subject']);
                $stmt->bindParam(':exam', $session['examination_id']);
                $stmt->execute();
                while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

                    $stm = $_DB->prepare("SELECT * FROM answer_data WHERE uid = :uid AND question = :question AND subject = :subject AND examination = :exam AND session = :session LIMIT 1");
                    $stm->bindParam(':uid', $user_row['uid'], PDO::PARAM_INT);
                    $stm->bindParam(':question', $row['qa_id'], PDO::PARAM_INT);
                    $stm->bindParam(':subject', $session['examination_subject'], PDO::PARAM_INT);
                    $stm->bindParam(':exam', $session['examination_id'], PDO::PARAM_INT);
                    $stm->bindParam(':session', $session['session_id'], PDO::PARAM_INT);
                    $stm->execute();
                    $ssrow = $stm->fetch(PDO::FETCH_ASSOC);

                    if (empty($ssrow['id'])) {
                        $ans = 0;
                        $temp = 1;
                        $stm = $_DB->prepare("INSERT INTO answer_data(uid,question,subject,examination,session,answer,temp) VALUES (:uid, :question, :subject, :examination, :session, :answer, :temp)");
                        $stm->bindParam(':uid', $user_row['uid'], PDO::PARAM_INT);
                        $stm->bindParam(':question', $row['qa_id'], PDO::PARAM_INT);
                        $stm->bindParam(':subject', $session['examination_subject'], PDO::PARAM_INT);
                        $stm->bindParam(':examination', $session['examination_id'], PDO::PARAM_INT);
                        $stm->bindParam(':session', $session['session_id'], PDO::PARAM_INT);
                        $stm->bindParam(':answer', $ans, PDO::PARAM_INT);
                        $stm->bindParam(':temp', $temp, PDO::PARAM_INT);
                        $stm->execute();
                    }

                    $stm = $_DB->prepare("SELECT * FROM answer_data WHERE uid = :uid AND question = :question AND subject = :subject AND examination = :exam AND session = :session LIMIT 1");

                    $stm->bindParam(':uid', $user_row['uid'], PDO::PARAM_INT);
                    $stm->bindParam(':question', $row['qa_id'], PDO::PARAM_INT);
                    $stm->bindParam(':subject', $session['examination_subject'], PDO::PARAM_INT);
                    $stm->bindParam(':exam', $session['examination_id'], PDO::PARAM_INT);
                    $stm->bindParam(':session', $session['session_id'], PDO::PARAM_INT);
                    $stm->execute();
                    $makec = $stm->fetch(PDO::FETCH_ASSOC);
            ?>
                    <a href="?n=<?php echo $exami;?>" class="btn btn-<?php echo ($makec['answer']==0||empty($makec['answer'])?'outline-':'');?>primary  mb-1 btn-block <?php echo ($exami==$n+1?'active':'');?>"><?php echo $exami;?></a>
            <?php
                    $exami++;
                }
            ?>
        </div>
            <?php

            ?>
        <script type="text/javascript">
            let session_id = <?php echo $session['session_id']; ?>;
            let user = <?php echo $user_row['uid']; ?>;
            let timeleft_1 = <?php echo $time_ree; ?>;
        </script>
        <div class="col-xl-9" id="exam-content">
            <div class="card shadow mb-3 card-qa">
                <div class="card-header">
                    <strong>#<?php echo $n+1;?></strong>
                </div>
                <div class="card-body">
                    <form action="javascript:void(0)" id="doing-exam-form">
                    <input type="hidden" name="uid" value="<?php echo $user_row['uid']; ?>">
                    <input type="hidden" name="question" value="<?php echo $exam_row['qa_id']; ?>">
                    <input type="hidden" name="subject" value="<?php echo $session['examination_subject']; ?>">
                    <input type="hidden" name="examination" value="<?php echo $session['examination_id']; ?>">
                    <input type="hidden" name="session" value="<?php echo $session['session_id']; ?>">
                    <div class="form-group row">
                      <label class="col-sm-2" for="question">คำถาม ?</label>
                      <div class="col-sm-10">
                        <div><?php echo $exam_row['qa_question'];?></div>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-2">ตัวเลือก</label>
                      <div class="col-sm-10 row">
                        <?php 
                            $choices = UniqueRandomNumbersWithinRange(1,4,4);
                            foreach ($choices as $choice) {
                        ?>
                        <div class="col-12">
                            <div class="custom-control custom-radio mb-2">
                                <input class="custom-control-input" id="choice_<?php echo $choice;?>" type="radio" name="answer" required value="<?php echo $choice;?>" <?php echo ($answer['answer']==$choice?'checked':'');?>>
                                <label class="custom-control-label" for="choice_<?php echo $choice;?>"><?php echo $exam_row['qa_choice_'.$choice];?></label>
                            </div>
                        </div>
                        <?php
                            }
                        ?>
                      </div>
                    </div>
                    <div class="form-group row">
                      <div class="col-sm-2"></div>
                      <div class="col-sm-10">
                        <a href="?n=<?php echo $_GET['n']-1;?>" class="btn btn-info <?php echo ($_GET['n']==1?'disabled':''); ?>"><i class="fa fa-arrow-left"></i> ข้อก่อนหน้า</a>
                        <a href="?n=<?php echo $_GET['n']+1;?>" class="btn btn-success <?php echo ($_GET['n']==$max['C']?'disabled':''); ?>"><i class="fa fa-arrow-right"></i> ข้อถัดไป</a>
                      </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-xl-2">
            <div class="card shadow mb-3">
                <div class="card-body text-center">
                    <small><strong>TIMELEFT</strong></small>
                    <h1><span id="timeleft"></span></h1>
                </div>
            </div>
            <form id="submit-exam" name="submit-exam" action="<?php url('solve/'); ?>" method="POST" onclick="return confirm('ตราจคำตอบให้ละเอียด! หากส่งแล้วจะไม่สามารถกลับมาทำใหม่ได้')">
                <input type="hidden" name="uid" value="<?php echo $user_row['uid']; ?>">
                <input type="hidden" name="subject" value="<?php echo $session['examination_subject']; ?>">
                <input type="hidden" name="examination" value="<?php echo $session['examination_id']; ?>">
                <input type="hidden" name="session" value="<?php echo $session['session_id']; ?>">
                <button type="submit" class="btn btn-success btn-block pt-3 pb-3">ส่งข้อสอบ</button>
            </form>
            <a class="mt-3 btn btn-warning btn-block pt-3 pb-3" href="<?php url('cancel-examination/'.$session['session_id']); ?>">บันทึกและออก</a>
        </div>
      </div>
    </div>
    <script type="text/javascript">

        var timer = new Timer();
        if (timeleft_1 == 0) {
            $("form#submit-exam").submit();
        }
        timer.start({countdown: true, startValues: {seconds: timeleft_1}});
        $('span#timeleft').html(timer.getTimeValues().toString());
        timer.addEventListener('secondsUpdated', function (e) {
            $('span#timeleft').html(timer.getTimeValues().toString());
            timeleft_1 = timeleft_1 - 1;
        });
        timer.addEventListener('targetAchieved', function (e) {
            $('span#timeleft').html('หมดเวลา!');
              swal({
                title: 'TIMEOUT',
                text: 'หมดเวลาการทำข้อสอบแล้ว',
                type: 'error',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Yes'
              }).then((result) => {
                if (result.value) {
                  $("form#submit-exam").submit();
                }
              })
        });

        var time_taken = 1;
        setInterval(function(){
            time_taken++;
        },1000)
        $(window).bind('beforeunload', function(){
            var data = $('form#doing-exam-form').serialize();
            data = data + '&timetake='+time_taken;
            $.ajax({
                url: weburl + 'ajax/time_taken',
                type: 'POST',
                dataType: 'json',
                data: data,
            })
            .done(function() {
                console.log("Update time taken");
            });

            $.ajax({
                url: weburl + 'ajax/time_remaining',
                type: 'POST',
                dataType: 'json',
                data: {uid: user, session: session_id},
            })
            .done(function() {
                console.log("Update time remaining");
            });
        });

        $('input:radio[name="answer"]').change(
        function(){
          var data = $('form#doing-exam-form').serialize();
          $.ajax({
            url: weburl + 'ajax/taken',
            type: 'POST',
            dataType: 'json',
            data: data,
          })
          .done(function(response) {
            console.log(response);
          });
        });
    </script>