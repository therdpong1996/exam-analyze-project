<!-- Main content -->
<div class="main-content">
    <!-- Top navbar -->
    <nav class="navbar bg-gradient-primary navbar-top navbar-expand-md navbar-dark" id="navbar-main">
        <div class="container-fluid">
        <!-- Brand -->
        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block"><?php echo $session['examination_title']; ?> <small>(เฉลย)</small></a>
        <?php require_once 'parts/usermenu.common.php'; ?>

    <!-- Page content -->
    <div class="container-fluid pb-5 pt-5 pt-md-8">
        <div class="row">
            <div class="col-xl-2">
                <div class="exam-scollbar pr-1" style="height:500px;">
                <?php
                    if (isset($_GET['n'])) {
                        $n = $_GET['n'];
                    } else {
                        $stm = $_DB->prepare('SELECT question FROM adaptive_answer_data WHERE subject = :subject AND examination = :exam AND uid = :uid ORDER BY id ASC LIMIT 1');
                        $stm->bindParam(':subject', $session['examination_subject']);
                        $stm->bindParam(':exam', $session['examination_id']);
                        $stm->bindParam(':uid', $user_row['uid']);
                        $stm->execute();
                        $exam_n = $stm->fetch(PDO::FETCH_ASSOC);
                        $n = $exam_n['question'];
                    }

                    $stmt = $_DB->prepare('SELECT * FROM q_and_a WHERE qa_subject = :subject AND qa_exam = :exam AND qa_id = :n LIMIT 1');
                    $stmt->bindParam(':subject', $session['examination_subject']);
                    $stmt->bindParam(':exam', $session['examination_id']);
                    $stmt->bindParam(':n', $n, PDO::PARAM_INT);
                    $stmt->execute();
                    $exam_row = $stmt->fetch(PDO::FETCH_ASSOC);

                    $stm = $_DB->prepare('SELECT adaptive_answer_data.answer,q_and_a.qa_choice_true FROM adaptive_answer_data JOIN q_and_a ON adaptive_answer_data.question = q_and_a.qa_id WHERE uid = :uid AND question = :question AND subject = :subject AND examination = :exam AND session = :session AND temp = 0 LIMIT 1');
                    $stm->bindParam(':uid', $user_row['uid'], PDO::PARAM_INT);
                    $stm->bindParam(':question', $exam_row['qa_id'], PDO::PARAM_INT);
                    $stm->bindParam(':subject', $session['examination_subject'], PDO::PARAM_INT);
                    $stm->bindParam(':exam', $session['examination_id'], PDO::PARAM_INT);
                    $stm->bindParam(':session', $session['session_id'], PDO::PARAM_INT);
                    $stm->execute();
                    $answer = $stm->fetch(PDO::FETCH_ASSOC);

                    $answer_arr = explode(',', $answer['qa_choice_true']);

                    $stmt = $_DB->prepare('SELECT q_and_a.qa_id,q_and_a.qa_order,q_and_a.qa_question FROM adaptive_answer_data JOIN q_and_a ON adaptive_answer_data.question = q_and_a.qa_id WHERE adaptive_answer_data.uid = :uid AND adaptive_answer_data.subject = :subject AND adaptive_answer_data.examination = :exam ORDER BY adaptive_answer_data.id ASC');
                    $stmt->bindParam(':subject', $session['examination_subject']);
                    $stmt->bindParam(':exam', $session['examination_id']);
                    $stmt->bindParam(':uid', $user_row['uid']);
                    $stmt->execute();
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $stm = $_DB->prepare('SELECT ans_check FROM adaptive_answer_data WHERE uid = :uid AND question = :question AND subject = :subject AND examination = :exam AND session = :session AND temp = 0 LIMIT 1');
                        $stm->bindParam(':uid', $user_row['uid'], PDO::PARAM_INT);
                        $stm->bindParam(':question', $row['qa_id'], PDO::PARAM_INT);
                        $stm->bindParam(':subject', $session['examination_subject'], PDO::PARAM_INT);
                        $stm->bindParam(':exam', $session['examination_id'], PDO::PARAM_INT);
                        $stm->bindParam(':session', $session['session_id'], PDO::PARAM_INT);
                        $stm->execute();
                        $makec = $stm->fetch(PDO::FETCH_ASSOC); ?>
                        <a style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" href="?n=<?php echo $row['qa_id']; ?>" class="btn <?php echo $makec['ans_check'] == 0 ? 'btn-danger' : 'btn-outline-primary'; ?> mb-1 btn-block"><?php echo $row['qa_order']; ?>.<?php echo strip_tags($row['qa_question']); ?></a>
                <?php
                    }
                ?>
                </div>
            </div>
            <div class="col-xl-10" id="exam-content">
                <div class="card shadow mb-3 card-qa">
                    <div class="card-header">
                        <strong>#<?php echo $exam_row['qa_order']; ?></strong>
                    </div>
                    <div class="card-body">
                        <form action="javascript:void(0)">
                            <div class="form-group row">
                                <label class="col-sm-2" for="question">คำถาม ?</label>
                                <div class="col-sm-10">
                                    <div><?php echo $exam_row['qa_question']; ?></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2">ตัวเลือก</label>
                                <div class="col-sm-10 row">
                                    <div class="col-12">
                                        <div class="custom-control custom-radio mb-2">
                                            <input class="custom-control-input" id="choice_1" type="radio" name="answer" disabled value="1" <?php echo $answer['answer'] == 1 ? 'checked' : ''; ?>>
                                            <label class="custom-control-label" for="choice_1"><?php echo $exam_row['qa_choice_1']; ?></label> <?php echo in_array(1, $answer_arr) ? '<i class="fa fa-check text-success"></i>' : ''; ?>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="custom-control custom-radio mb-2">
                                            <input class="custom-control-input" id="choice_2" type="radio" name="answer" disabled value="2" <?php echo $answer['answer'] == 2 ? 'checked' : ''; ?>>
                                            <label class="custom-control-label" for="choice_2"><?php echo $exam_row['qa_choice_2']; ?></label> <?php echo in_array(2, $answer_arr) ? '<i class="fa fa-check text-success"></i>' : ''; ?>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="custom-control custom-radio mb-2">
                                            <input class="custom-control-input" id="choice_3" type="radio" name="answer" disabled value="3" <?php echo $answer['answer'] == 3 ? 'checked' : ''; ?>>
                                            <label class="custom-control-label" for="choice_3"><?php echo $exam_row['qa_choice_3']; ?></label> <?php echo in_array(3, $answer_arr) ? '<i class="fa fa-check text-success"></i>' : ''; ?>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="custom-control custom-radio mb-2">
                                            <input class="custom-control-input" id="choice_4" type="radio" name="answer" disabled value="4" <?php echo $answer['answer'] == 4 ? 'checked' : ''; ?>>
                                            <label class="custom-control-label" for="choice_4"><?php echo $exam_row['qa_choice_4']; ?></label> <?php echo in_array(4, $answer_arr) ? '<i class="fa fa-check text-success"></i>' : ''; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>