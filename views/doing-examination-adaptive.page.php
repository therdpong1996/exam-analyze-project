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

?>
<!-- Main content -->
<div class="main-content">
    <!-- Top navbar -->
    <nav class="navbar bg-gradient-primary navbar-top navbar-expand-md navbar-dark" id="navbar-main">
        <div class="container-fluid">
        <!-- Brand -->
        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block">[ADAPTIVE] <?php echo $session['examination_title']; ?></a>
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
                    <strong>ข้อที่ <span id="num_current">1</span>/<?php echo $session['session_adap_number']; ?></strong>
                </div>
                <div class="card-body" id="adaptive-content">
                    <!--CONTENT FOR ADAPTIVE EXERCISES-->
                    <div class="text-center pt-5 pb-5"><i class="fas fa-spinner fa-spin fa-3x"></i></div>
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
    <div class="modal fade" id="adapAcceptModal" tabindex="-1" role="dialog" aria-labelledby="adapAcceptModal" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                
                <div class="modal-header">
                    <h2 class="modal-title">Adaptive Testing ?</h2>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                
                <div class="modal-body exam-scollbar" style="height: 400px;">
                    <h4>การทดสอบแบบปรับเหมาะด้วยคอมพิวเตอร์</h4>
                    <p>เป็นการทดสอบที่จัดข้อสอบให้เหมาะสมกับความสามารถของผู้เข้าสอบ ซึ่งอยู่บนพื้นฐานของการตอบข้อสอบข้อแรกหรือข้อที่ผ่านมาของผู้เข้าสอบ กล่าวคือ เมื่อผู้เข้าสอบทำข้อสอบข้อเริ่มต้นหรือชุดเริ่มต้นเรียบร้อยแล้ว ก็จะนำผลการตอบข้อสอบมาวิเคราะห์หรือประเมินระดับความสามารถของผู้เข้าสอบ เพื่อที่จะคัดเลือกข้อสอบข้อถัดไปที่เหมาะสม โดยอาศัยทฤษฎีการตอบสนองข้อสอบ (MIRT) เป็นพื้นฐาน และจะสิ้นสุดการทดสอบเมื่อผู้ทดสอบทำการทดสอบตามเงื่อนไขหรือเกณฑ์ที่กำหนด</p>
                    <h4>ขั้นตอนของการทดสอบแบบปรับเหมาะด้วยคอมพิวเตอร์</h4>
                    <p>คอมพิวเตอร์ ประกอบไปด้วย 5 ขั้นตอน ดังนี้ ขั้นตอนที่ 1 การสร้างคลังข้อสอบ ขั้นตอนนี้เป็นการคัดเลือกข้อสอบที่ผ่านการวิเคราะห์โดยใช้หลักทฤษฎีการตอบสนองข้อสอบ (MIRT) แบบ 4 พารามิเตอร์ ประกอบด้วย ความถูกต้องของข้อนั้นๆ (I<sub>ik</sub>) ความสามารถนักศึกษา (a<sub>jk</sub>) ค่าความสัมพันธ์ระหว่างข้อที่ i และนักศักษาคนที่ j (W<sub>ij</sub>) และค่าไบแอส (b<sub>i</sub>) ขั้นตอนที่ 2 การคัดเลือกข้อสอบข้อเริ่มต้น ขั้นตอนนี้จะทำการคัดเลือกข้อสอบที่มีค่าความยาก (b) อยู่ในระดับปานกลาง ขั้นตอนที่ 3 การคัดเลือกข้อสอบข้อถัดไป เป็นขั้นตอนที่ต้องคัดเลือกข้อสอบให้ใกล้เคียงกับความสามารถของผู้เข้าสอบในขณะนั้น โดยพิจารณาจากผลการตอบข้อสอบข้อก่อนหน้า ขั้นตอนที่ 4 การประมาณค่าความสามารถของผู้เข้าสอบ เป็นขั้นตอนที่จะทำการประมาณค่าความสามารถของผู้เข้าสอบหลังจากที่ตอบข้อสอบข้อนั้นๆ แล้ว และขั้นตอนที่ 5 การยุติการทดสอบ ขั้นตอนนี้เป็นการสิ้นสุดการทดสอบเมื่อผู้เข้าสอบทำข้อสอบได้ครบตรงตามเกณฑ์ที่กำหนด</p>
                    <h4>ประโยชน์และความสำคัญของโปรแกรมการทดสอบแบบปรับเหมาะด้วยคอมพิวเตอร์</h4>
                    <ul>
                        <li>พัฒนาวิธีการเรียนของตนเอง เพื่อพัฒนาให้มีผลการเรียนดีขึ้นในระดับชั้นต่อไป</li>
                        <li>พิจารณาเลือกสาขาวิชาที่เรียนเพื่อให้เหมาะสมกับตนเอง</li>
                        <li>วางแผนการเรียนรู้ของตนเองเพื่อให้มีประสิทธิภาพมากยิ่งขึ้น</li>
                    </ul>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-link  ml-auto" data-dismiss="modal">ฉันเข้าใจแล้ว</button> 
                </div>
                
            </div>
        </div>
    </div>
    <script type="text/javascript">
        var num_cur = <?php isset($_COOKIE['sim_number'])?$_COOKIE['sim_number']:1; ?>;;
        const content = $('div#adaptive-content');
        //FIRST VISIT
        $(document).ready(function () {
            $('#adapAcceptModal').modal('show')

            content.html('<div class="text-center pt-5 pb-5"><i class="fas fa-spinner fa-spin fa-3x"></i></div>');
            $.ajax({
                type: "POST",
                url: weburl + "ajax/adaptive_firstrun",
                data: {userid: userid, session: session_id, number: number},
                dataType: "html",
                success: function (response) {
                    $('#num_current').html(num_cur)
                    num_cur++;
                    Cookies.set('curr_number', num_cur, { expires: 7, path: '/' });
                    content.html(response)
                }
            });
        })

        $('button#next_exercise').on('click', function (e) { 
            e.preventDefault();
            var data = $('form#doing-exam-form').serialize();
            data = data + '&number=' + number;
            content.html('<div class="text-center pt-5 pb-5"><i class="fas fa-spinner fa-spin fa-3x"></i></div>');
            $.ajax({
                type: "POST",
                url: weburl + "ajax/adaptive_continue",
                data: data,
                dataType: "html",
                success: function (response) {
                    $('#num_current').html(num_cur)
                    num_cur++;
                    Cookies.set('curr_number', num_cur, { expires: 7, path: '/' });
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
    </script>