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
                            <canvas id="AdaptivechoiceChart"></canvas>
                            
                        </div>
                        <div class="col-12 mt-4">
                            <hr>
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
                $total = $c1c['c'] + $c2c['c'] + $c3c['c'] + $c4c['c']; 

                //ADAPTIVE
                $stm = $_DB->prepare('SELECT COUNT(id) AS c FROM adaptive_answer_data WHERE answer = 1 AND subject = :subject AND session = :session AND examination = :exam AND question = :question AND temp = 0');
                $stm->bindParam(':subject', $session['examination_subject']);
                $stm->bindParam(':session', $session['session_id']);
                $stm->bindParam(':exam', $session['examination_id']);
                $stm->bindParam(':question', $exam_row['qa_id']);
                $stm->execute();
                $ac1c = $stm->fetch(PDO::FETCH_ASSOC);
                $stm = $_DB->prepare('SELECT COUNT(id) AS c FROM adaptive_answer_data WHERE answer = 2 AND subject = :subject AND session = :session AND examination = :exam AND question = :question AND temp = 0');
                $stm->bindParam(':subject', $session['examination_subject']);
                $stm->bindParam(':session', $session['session_id']);
                $stm->bindParam(':exam', $session['examination_id']);
                $stm->bindParam(':question', $exam_row['qa_id']);
                $stm->execute();
                $ac2c = $stm->fetch(PDO::FETCH_ASSOC);
                $stm = $_DB->prepare('SELECT COUNT(id) AS c FROM adaptive_answer_data WHERE answer = 3 AND subject = :subject AND session = :session AND examination = :exam AND question = :question AND temp = 0');
                $stm->bindParam(':subject', $session['examination_subject']);
                $stm->bindParam(':session', $session['session_id']);
                $stm->bindParam(':exam', $session['examination_id']);
                $stm->bindParam(':question', $exam_row['qa_id']);
                $stm->execute();
                $ac3c = $stm->fetch(PDO::FETCH_ASSOC);
                $stm = $_DB->prepare('SELECT COUNT(id) AS c FROM adaptive_answer_data WHERE answer = 4 AND subject = :subject AND session = :session AND examination = :exam AND question = :question AND temp = 0');
                $stm->bindParam(':subject', $session['examination_subject']);
                $stm->bindParam(':session', $session['session_id']);
                $stm->bindParam(':exam', $session['examination_id']);
                $stm->bindParam(':question', $exam_row['qa_id']);
                $stm->execute();
                $ac4c = $stm->fetch(PDO::FETCH_ASSOC);
                $atotal = $ac1c['c'] + $ac2c['c'] + $ac3c['c'] + $ac4c['c']; 
        ?>
        <script type="text/javascript">
            var Nconfig = {
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
                    title: {
                        display: true,
                        text: 'Normal Testing'
                    },
                    legend: {
                        display: true
                    },
                }
            };

            var Aconfig = {
                type: 'pie',
                data: {
                    datasets: [{
                        data: [
                            <?php echo $ac1c['c']; ?>,
                            <?php echo $ac2c['c']; ?>,
                            <?php echo $ac3c['c']; ?>,
                            <?php echo $ac4c['c']; ?>,
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
                        'A (<?php echo round(($ac1c['c'] / $atotal) * 100); ?>%)',
                        'B (<?php echo round(($ac2c['c'] / $atotal) * 100); ?>%)',
                        'C (<?php echo round(($ac3c['c'] / $atotal) * 100); ?>%)',
                        'D (<?php echo round(($ac4c['c'] / $atotal) * 100); ?>%)'
                    ]
                },
                options: {
                    responsive: true,
                    title: {
                        display: true,
                        text: 'Adaptive Testing'
                    },
                    legend: {
                        display: true
                    },
                }
            };

            window.onload = function() {
                var ctx = document.getElementById('choiceChart').getContext('2d');
                window.myPie = new Chart(ctx, Nconfig);
                var actx = document.getElementById('AdaptivechoiceChart').getContext('2d');
                window.myPie = new Chart(actx, Aconfig);
            };
        </script>
        