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
         <?php if (isset($_GET['n']) and !isset($_GET['overview']) and !isset($_GET['scorelist'])) {
                ?>
        <div class="col-xl-10">
            <div class="card shadow">
                <div class="card-header bg-transparent">
                  <div class="row align-items-center">
                    <div class="col">
                      <strong>Analyze</strong>
                    </div>
                  </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-5">
                            <?php echo strip_tags($exam_row['qa_question']); ?>
                        </div>
                        <div class="col-6">
                            <canvas id="choiceChart"></canvas>
                        </div>
                        <div class="col-6">
                            <p><strong>A.</strong> <?php echo $exam_row['qa_choice_1']; ?> <?php echo in_array(1, $answer_arr) ? '<i class="fa fa-check text-success"></i> ' : ''; ?>
                            </p>
                            <p><strong>B.</strong> <?php echo $exam_row['qa_choice_2']; ?> <?php echo in_array(2, $answer_arr) ? '<i class="fa fa-check text-success"></i> ' : ''; ?>
                            </p>
                            <p><strong>C.</strong> <?php echo $exam_row['qa_choice_3']; ?> <?php echo in_array(3, $answer_arr) ? '<i class="fa fa-check text-success"></i> ' : ''; ?>
                            </p>
                            <p><strong>D.</strong> <?php echo $exam_row['qa_choice_4']; ?> <?php echo in_array(4, $answer_arr) ? '<i class="fa fa-check text-success"></i> ' : ''; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
            $stm = $_DB->prepare('SELECT COUNT(id) AS c FROM answer_data WHERE answer = 1 AND subject = :subject AND session = :session AND examination = :exam AND question = :question AND temp = 0');
                $stm->bindParam(':subject', $session['examination_subject']);
                $stm->bindParam(':session', $session['session_id']);
                $stm->bindParam(':exam', $session['examination_id']);
                $stm->bindParam(':question', $exam_row['qa_id']);
                $stm->execute();
                $c1c = $stm->fetch(PDO::FETCH_ASSOC);
                $stm = $_DB->prepare('SELECT COUNT(id) AS c FROM answer_data WHERE answer = 2 AND subject = :subject AND session = :session AND examination = :exam AND question = :question AND temp = 0');
                $stm->bindParam(':subject', $session['examination_subject']);
                $stm->bindParam(':session', $session['session_id']);
                $stm->bindParam(':exam', $session['examination_id']);
                $stm->bindParam(':question', $exam_row['qa_id']);
                $stm->execute();
                $c2c = $stm->fetch(PDO::FETCH_ASSOC);
                $stm = $_DB->prepare('SELECT COUNT(id) AS c FROM answer_data WHERE answer = 3 AND subject = :subject AND session = :session AND examination = :exam AND question = :question AND temp = 0');
                $stm->bindParam(':subject', $session['examination_subject']);
                $stm->bindParam(':session', $session['session_id']);
                $stm->bindParam(':exam', $session['examination_id']);
                $stm->bindParam(':question', $exam_row['qa_id']);
                $stm->execute();
                $c3c = $stm->fetch(PDO::FETCH_ASSOC);
                $stm = $_DB->prepare('SELECT COUNT(id) AS c FROM answer_data WHERE answer = 4 AND subject = :subject AND session = :session AND examination = :exam AND question = :question AND temp = 0');
                $stm->bindParam(':subject', $session['examination_subject']);
                $stm->bindParam(':session', $session['session_id']);
                $stm->bindParam(':exam', $session['examination_id']);
                $stm->bindParam(':question', $exam_row['qa_id']);
                $stm->execute();
                $c4c = $stm->fetch(PDO::FETCH_ASSOC);
                $total = $c1c['c'] + $c2c['c'] + $c3c['c'] + $c4c['c']; ?>
        <script type="text/javascript">
            var config = {
                type: 'pie',
                data: {
                    datasets: [{
                        data: [
                            <?php echo $c1c['c']; ?>,
                            <?php echo $c2c['c']; ?>,
                            <?php echo $c3c['c']; ?>,
                            <?php echo $c4c['c']; ?>,
                        ],
                        backgroundColor: [
                            '#4298b5',
                            '#49a942',
                            '#ffc845',
                            '#fe5000'
                        ],
                        label: 'Choice Chart'
                    }],
                    labels: [
                        'A (<?php echo round(($c1c['c'] / $total) * 100); ?>%)',
                        'B (<?php echo round(($c2c['c'] / $total) * 100); ?>%)',
                        'C (<?php echo round(($c3c['c'] / $total) * 100); ?>%)',
                        'D (<?php echo round(($c4c['c'] / $total) * 100); ?>%)'
                    ]
                },
                options: {
                    responsive: true,
                    legend: {
                        display: true
                    },
                }
            };

            window.onload = function() {
                var ctx = document.getElementById('choiceChart').getContext('2d');
                window.myPie = new Chart(ctx, config);
            };
        </script>
        <?php
            } elseif (!isset($_GET['n']) and isset($_GET['overview']) and !isset($_GET['scorelist'])) {
                ?>
        <div class="col-xl-10">
            <div class="card shadow">
                <div class="card-header bg-transparent">
                  <div class="row align-items-center">
                    <div class="col">
                      <strong>Overview</strong>
                    </div>
                  </div>
                </div>
                <div class="card-body">
                    <?php
                        $stmt = $_DB->prepare('SELECT * FROM q_and_a WHERE qa_subject = :subject AND qa_exam = :exam ORDER BY qa_order ASC');
                $stmt->bindParam(':subject', $session['examination_subject']);
                $stmt->bindParam(':exam', $session['examination_id']);
                $stmt->execute();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $stm = $_DB->prepare('SELECT COUNT(id) AS c FROM answer_data WHERE subject = :subject AND session = :session AND examination = :exam AND question = :question AND temp = 0');
                    $stm->bindParam(':subject', $row['qa_subject']);
                    $stm->bindParam(':session', $session['session_id']);
                    $stm->bindParam(':exam', $row['qa_exam']);
                    $stm->bindParam(':question', $row['qa_id']);
                    $stm->execute();
                    $total = $stm->fetch(PDO::FETCH_ASSOC);

                    $stm = $_DB->prepare('SELECT COUNT(id) AS c FROM answer_data WHERE ans_check = 1 AND subject = :subject AND session = :session AND examination = :exam AND question = :question AND temp = 0');
                    $stm->bindParam(':subject', $row['qa_subject']);
                    $stm->bindParam(':session', $session['session_id']);
                    $stm->bindParam(':exam', $row['qa_exam']);
                    $stm->bindParam(':question', $row['qa_id']);
                    $stm->execute();
                    $true = $stm->fetch(PDO::FETCH_ASSOC);

                    $precen = ($true['c'] / $total['c']) * 100;
                    $precen = floor($precen); ?>
                        <div class="progress-wrapper" style="padding-top: 0.5rem;">
                          <div class="progress-info">
                            <div class="exer-text"><?php echo $row['qa_order']; ?>. <?php echo strip_tags($row['qa_question']); ?></div>
                            <div class="progress-percentage">
                              <span><i class="fa fa-check text-success"></i> <strong class="text-success"><?php echo $precen; ?>%</strong> | <i class="fa fa-times text-danger"></i> <strong class="text-danger"><?php echo 100 - $precen; ?>%</strong></span>
                            </div>
                          </div>
                          <div class="progress" style="background-color: #ff4c4c;">
                            <div class="progress-bar bg-success" role="progressbar" aria-valuenow="<?php echo $precen; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $precen; ?>%;"></div>
                          </div>
                        </div>
                    <?php
                } ?>
                </div>
            </div>
        </div>
        <?php
            } else {
                ?>
        <div class="col-xl-10">
            <div class="card shadow">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-items-center">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Time Taken</th>
                                    <th scope="col">Score</th>
                                    <th scope="col">Date</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                  $stm = $_DB->prepare('SELECT * FROM session_score JOIN sessions ON session_score.session_id = sessions.session_id JOIN subjects ON session_score.subject_id = subjects.subject_id JOIN users ON session_score.uid = users.uid WHERE session_score.session_id = :session ORDER BY users.stu_id ASC');
                $stm->bindParam(':session', $_GET['session_id']);
                $stm->execute();
                while ($rows = $stm->fetch(PDO::FETCH_ASSOC)) {
                    $stm2 = $_DB->prepare('SELECT * FROM time_remaining WHERE uid = :uid AND session = :session');
                    $stm2->bindParam(':uid', $rows['uid']);
                    $stm2->bindParam(':session', $rows['session_id']);
                    $stm2->execute();
                    $timet = $stm2->fetch(PDO::FETCH_ASSOC);
                    $timet['time_remaining'] = ($timet['time_remaining'] < 0 ? 0 : $timet['time_remaining']); ?>
                                <tr id="score-<?php echo $rows['score_id']; ?>">
                                    <th scope="row">
                                      <span class="mb-0 text-sm"><?php echo $rows['stu_id']; ?></span>
                                    </th>
                                    <td>
                                      <span class="mb-0 text-sm"><?php echo $rows['full_name']; ?></span>
                                    </td>
                                    <td>
                                      <span class="mb-0 text-sm"><?php echo isset($timet['time_remaining']) ? floor((($rows['session_timeleft'] * 60) - $timet['time_remaining']) / 60).' min' : 'N/A'; ?></span>
                                    </td>
                                    <td>
                                      <?php echo $rows['score']; ?>/<?php echo $rows['score_full']; ?>
                                    </td>
                                    <td>
                                      <?php echo $rows['finish_tile']; ?>
                                    </td>
                                    <td>
                                        <button id="reset_sc_<?php echo $rows['uid']; ?>" class="btn btn-danger btn-sm" onclick="resetscore(<?php echo $rows['score_id']; ?>, <?php echo $rows['session_id']; ?>, <?php echo $rows['uid']; ?>)">รีเซ็ต</button>
                                    </td>
                                </tr>
                                <?php
                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php
            } ?>
    </div>
</div>
<script>
    resetscore = function(sc_id, ses_id, uid){
        swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, reset it!'
        }).then(function(result){
            if (result.value) {
                var oldtext = $('#reset_sc_' + uid).html();
                $('#reset_sc_' + uid).html('<i class="fa fa-spinner fa-spin"></i>');
                $.ajax({
                    type: "POST",
                    url: weburl + "ajax/reset_score",
                    data: {sc_id: sc_id, session_id: ses_id, uid: uid},
                    dataType: "json"
                })
                .done(function(response){
                    if(response.state){
                        $('#score-' + sc_id).remove();
                    }else{
                    $('#delete-btn').html(oldtext);
                    swal(
                        'ERROR',
                        response.msg,
                        'error'
                    );
                    }
                });
            }
        })
    }
</script>