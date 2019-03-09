<?php
    header('Content-type: application/json');
    session_start();
    if(!isset($_SESSION['auth']) and !isset($_SESSION['username']) and $_SESSION['role'] < 3){
        echo json_encode(['state'=>false,'msg'=>'No permission']); exit;
    }

    require_once '../control/init.php';

    $uid = $_POST['uid'];
    $question = $_POST['question'];
    $subject = $_POST['subject'];
    $examination = $_POST['examination'];
    $session = $_POST['session'];
    $adap_table = $_POST['adap_table'];
    $answer = $_POST['answer'];
    $number = $_POST['number'];
    $time_taken = $_POST['time_taken'];
    $temp = 1;
    $token = md5('computerizedadaptivetesting' . $session);

    //TIME
    $time_tt = time();
    $stmt3 = $_DB->prepare('SELECT * FROM adaptive_time_remaining WHERE session = :session AND uid = :uid');
    $stmt3->bindParam(':session', $session);
    $stmt3->bindParam(':uid', $uid);
    $stmt3->execute();
    $time_re = $stmt3->fetch(PDO::FETCH_ASSOC);
    $time_ree = $time_re['time_remaining'] - ($time_tt - $time_re['time_update1']);
    $stm = $_DB->prepare('UPDATE adaptive_time_remaining SET time_update1 = :updatet1, time_remaining = :time_re WHERE uid = :uid AND session = :session');
    $stm->bindParam(':time_re', $time_ree, PDO::PARAM_INT);
    $stm->bindParam(':uid', $uid, PDO::PARAM_INT);
    $stm->bindParam(':updatet1', $time_tt, PDO::PARAM_INT);
    $stm->bindParam(':session', $session, PDO::PARAM_INT);
    $stm->execute();

    //CHECK ANSWER
    $stm = $_DB->prepare("SELECT * FROM q_and_a WHERE qa_id = :id AND qa_subject = :subject AND qa_exam = :exam LIMIT 1");
    $stm->bindParam(':id', $question, PDO::PARAM_INT);
    $stm->bindParam(':subject', $subject, PDO::PARAM_INT);
    $stm->bindParam(':exam', $examination, PDO::PARAM_INT);
    $stm->execute();
    $solve = $stm->fetch(PDO::FETCH_ASSOC);

    $ans_arr = explode(',', $solve['qa_choice_true']);

    if (in_array($answer, $ans_arr)) {
        $chk = 1;
    }else{
        $chk = 0;
    }

    // DATA PREPARE
    $stm = $_DB->prepare('INSERT INTO adaptive_answer_data(uid,question,subject,examination,session,answer,temp,ans_check,time_taken_s) VALUES (:uid, :question, :subject, :examination, :session, :answer, :temp, :ans_check, :time_taken_s)');
    $stm->bindParam(':uid', $uid, PDO::PARAM_INT);
    $stm->bindParam(':question', $question, PDO::PARAM_INT);
    $stm->bindParam(':subject', $subject, PDO::PARAM_INT);
    $stm->bindParam(':examination', $examination, PDO::PARAM_INT);
    $stm->bindParam(':session', $session, PDO::PARAM_INT);
    $stm->bindParam(':answer', $answer, PDO::PARAM_INT);
    $stm->bindParam(':temp', $temp, PDO::PARAM_INT);
    $stm->bindParam(':ans_check', $chk, PDO::PARAM_INT);
    $stm->bindParam(':time_taken_s', $time_taken, PDO::PARAM_INT);
    $stm->execute();

    $post_data = 'session='.$session.'&userid='.$uid.'&exercise='.$question.'&correct='.$chk.'&time_taken='.$time_taken.'&number='.$number.'&adapt='.$adap_table.'&token='.$token;
    $url = $_G['webservice'] . 'continue/';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $response = json_decode($response, true);
    if ($response['state'] == 'success') {
?>
        <div class="text-center text-success pt-5 pb-5"><i class="fas fa-check-square fa-3x"></i> <h3 class="mt-3">เสร็จเรียบร้อยแล้ว สามารถส่งคำตอบได้เลยครับ/ค่ะ</h3></div>
<?php
    }else{
        if ($response['qa_id']) {

            $stmt = $_DB->prepare('SELECT * FROM q_and_a WHERE qa_id = :n LIMIT 1');
            $stmt->bindParam(':n', $response['qa_id'], PDO::PARAM_INT);
            $stmt->execute();
            $exam_row = $stmt->fetch(PDO::FETCH_ASSOC);
?>
                <form action="javascript:void(0)" id="doing-exam-form">
                    <input type="hidden" name="uid" value="<?php echo $uid; ?>">
                    <input type="hidden" name="question" value="<?php echo $exam_row['qa_id']; ?>">
                    <input type="hidden" name="subject" value="<?php echo $exam_row['qa_subject']; ?>">
                    <input type="hidden" name="examination" value="<?php echo $exam_row['qa_exam']; ?>">
                    <input type="hidden" name="session" value="<?php echo $session; ?>">
                    <input type="hidden" name="adap_table" value="<?php echo $adap_table; ?>">
                    <input type="hidden" name="time_taken" id="time_taken" value="1">
                    <div class="form-group row">
                        <label class="col-sm-2" for="question">คำถาม ?</label>
                        <div class="col-sm-10">
                        <div><?php echo $exam_row['qa_question']; ?></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2">ตัวเลือก</label>
                        <div class="col-sm-10 row">
                        <?php 
                            $choices = UniqueRandomNumbersWithinRange(1, 4, 4);
                            foreach ($choices as $choice) {
                                ?>
                        <div class="col-12">
                            <div class="custom-control custom-radio mb-2">
                                <input class="custom-control-input" id="choice_<?php echo $choice; ?>" type="radio" name="answer" required value="<?php echo $choice; ?>">
                                <label class="custom-control-label" for="choice_<?php echo $choice; ?>"><?php echo $exam_row['qa_choice_'.$choice]; ?></label>
                            </div>
                        </div>
                        <?php
                            }
                        ?>
                        </div>
                    </div>
                </form>
<?php
        }else{
?>
            <div class="text-center text-danger pt-5 pb-5"><i class="fas fa-exclamation fa-3x"></i><h3 class="mt-3">ขออภัย! มีข้อผิดพลาดทางระบบ</h3></div>
<?php
        }
    }