<?php
    //TIME
    $time_tt = time();
    $stmt3 = $_DB->prepare('SELECT * FROM adaptive_time_remaining WHERE session = :session AND uid = :uid');
    $stmt3->bindParam(':session', $session['session_id']);
    $stmt3->bindParam(':uid', $user_row['uid']);
    $stmt3->execute();
    $time_re = $stmt3->fetch(PDO::FETCH_ASSOC);
    if ($time_re['time_remaining']) {
        $time_ree = $time_re['time_remaining'] - ($time_tt - $time_re['time_update1']);
        $time_ree = ($time_ree <= 0) ? 0 : $time_ree;
        if ($time_tt > $time_re['time_start'] + ($session['session_timeleft'] * 60)) {
            $time_ree = 0;
        }

        $stm = $_DB->prepare('UPDATE adaptive_time_remaining SET time_update1 = :updatet1, time_remaining = :time_re WHERE uid = :uid AND session = :session');
        $stm->bindParam(':time_re', $time_ree, PDO::PARAM_INT);
        $stm->bindParam(':uid', $user_row['uid'], PDO::PARAM_INT);
        $stm->bindParam(':updatet1', $time_tt, PDO::PARAM_INT);
        $stm->bindParam(':session', $session['session_id'], PDO::PARAM_INT);
        $stm->execute();
    } else {
        $time_ree = $session['session_timeleft'] * 60;
        $stmt3 = $_DB->prepare('INSERT INTO adaptive_time_remaining (uid,session,time_start,time_update1,time_remaining) VALUES (:uid, :session, :start, :updatet1, :time_re)');
        $stmt3->bindParam(':session', $session['session_id']);
        $stmt3->bindParam(':uid', $user_row['uid']);
        $stmt3->bindParam(':start', $time_tt);
        $stmt3->bindParam(':updatet1', $time_tt);
        $stmt3->bindParam(':time_re', $time_ree);
        $stmt3->execute();
    }

    //INSERT PRE-DATA ANSWER
    $stmt = $_DB->prepare('SELECT qa_id FROM q_and_a WHERE qa_subject = :subject AND qa_exam = :exam ORDER BY qa_order ASC');
    $stmt->bindParam(':subject', $session['examination_subject']);
    $stmt->bindParam(':exam', $session['examination_id']);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $stm = $_DB->prepare('SELECT id FROM adaptive_answer_data WHERE uid = :uid AND question = :question AND subject = :subject AND examination = :exam AND session = :session LIMIT 1');
        $stm->bindParam(':uid', $user_row['uid'], PDO::PARAM_INT);
        $stm->bindParam(':question', $row['qa_id'], PDO::PARAM_INT);
        $stm->bindParam(':subject', $session['examination_subject'], PDO::PARAM_INT);
        $stm->bindParam(':exam', $session['examination_id'], PDO::PARAM_INT);
        $stm->bindParam(':session', $session['session_id'], PDO::PARAM_INT);
        $stm->execute();
        $ans_rows = $stm->fetch(PDO::FETCH_ASSOC);

        if (empty($ans_rows['id'])) {
            $ans = 0;
            $temp = 1;
            $stm = $_DB->prepare('INSERT INTO answer_data(uid,question,subject,examination,session,answer,temp) VALUES (:uid, :question, :subject, :examination, :session, :answer, :temp)');
            $stm->bindParam(':uid', $user_row['uid'], PDO::PARAM_INT);
            $stm->bindParam(':question', $row['qa_id'], PDO::PARAM_INT);
            $stm->bindParam(':subject', $session['examination_subject'], PDO::PARAM_INT);
            $stm->bindParam(':examination', $session['examination_id'], PDO::PARAM_INT);
            $stm->bindParam(':session', $session['session_id'], PDO::PARAM_INT);
            $stm->bindParam(':answer', $ans, PDO::PARAM_INT);
            $stm->bindParam(':temp', $temp, PDO::PARAM_INT);
            $stm->execute();
        }
    }

?>
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
        <script type="text/javascript">
            let session_id = <?php echo $session['session_id']; ?>;
            let userid = <?php echo $user_row['uid']; ?>;
            let timeleft = <?php echo $time_ree; ?>;
            let number = <?php echo $session['session_adap_number']; ?>;
        </script>
        <div class="col-xl-10" id="exam-content">
            <div class="card shadow mb-3 card-qa">
                <div class="card-header">
                    <strong>Adaptive Testing not sorted</strong>
                </div>
                <div class="card-body" id="adaptive-content">
                    <!--CONTENT FOR ADAPTIVE EXERCISES-->
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <button id="next_exercise" class="btn btn-success" type="button">ถัดไป <i class="fa fa-arrow-circle-right"></i></button>
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
            <form id="submit-exam" name="submit-exam" action="<?php url('solve-adaptive/'); ?>" method="POST" onclick="return confirm('ตราจคำตอบให้ละเอียด! หากส่งแล้วจะไม่สามารถกลับมาทำใหม่ได้')">
                <input type="hidden" name="uid" value="<?php echo $user_row['uid']; ?>">
                <input type="hidden" name="subject" value="<?php echo $session['examination_subject']; ?>">
                <input type="hidden" name="examination" value="<?php echo $session['examination_id']; ?>">
                <input type="hidden" name="session" value="<?php echo $session['session_id']; ?>">
                <button type="submit" class="btn btn-success btn-block pt-3 pb-3">ส่งข้อสอบ</button>
            </form>
            <a class="mt-3 btn btn-warning btn-block pt-3 pb-3" href="<?php url('cancel-examination-adaptive/'.$session['session_id']); ?>">บันทึกและออก</a>
        </div>
        </div>
    </div>
    <script type="text/javascript">
        const content = $('div#adaptive-content');
        //FIRST VISIT
        $(document).ready(function () {
            content.html('<div class="text-center pt-5 pb-5"><i class="fas fa-spinner fa-spin fa-3x"></i></div>');
            $.ajax({
                type: "POST",
                url: weburl + "ajax/adaptive_firstrun",
                data: {userid: userid, session: session_id, number: number},
                dataType: "html",
                success: function (response) {
                    content.html(response)
                }
            });
        })

        $('button#next_exercise').on('click', function (e) { 
            e.preventDefault();
            var data = $('form#doing-exam-form').serialize();
            data = data + '&number=' + number;
            $.ajax({
                type: "POST",
                url: weburl + "ajax/adaptive_continue",
                data: data,
                dataType: "html",
                success: function (response) {
                    content.html(response)
                }
            });
        });

        // TIME COUNTDOWN
        var timer = new Timer();
        if (timeleft == 0) {
            $("form#submit-exam").submit();
        }
        timer.start({countdown: true, startValues: {seconds: timeleft}});
        $('span#timeleft').html(timer.getTimeValues().toString());
        timer.addEventListener('secondsUpdated', function (e) {
            $('span#timeleft').html(timer.getTimeValues().toString());
            timeleft = timeleft - 1;
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
        // END TIME COUNTDOWN
        
        // TIME TAKEN
        var time_taken = 1;
        setInterval(function(){
            $('input#time_taken').val(time_taken);
            time_taken++;
        },1000)


        // SEND DATA WHEN SELECTED
        $('input:radio[name="answer"]').change(
        function(){
            var data = $('form#doing-exam-form').serialize();
            $.ajax({
            url: weburl + 'ajax/adaptive_taken',
            type: 'POST',
            dataType: 'json',
            data: data,
            })
            .done(function(response) {
                console.log(response);
            });
        });
    </script>