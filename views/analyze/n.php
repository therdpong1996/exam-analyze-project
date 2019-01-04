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
                        <div class="col-6">
                            <canvas id="choiceChart"></canvas>
                        </div>
                        <div class="col-6">
                            <canvas id="AdaptivechoiceChart"></canvas>
                        </div>
                        <div class="col-6">
                            <?php 
                                $stm = $_DB->prepare('SELECT COUNT(uid) AS c FROM answer_data WHERE subject = :subject AND session = :session AND examination = :exam AND question = :question AND temp = 0');
                                $stm->bindParam(':subject', $session['examination_subject']);
                                $stm->bindParam(':session', $session['session_id']);
                                $stm->bindParam(':exam', $session['examination_id']);
                                $stm->bindParam(':question', $exam_row['qa_id']);
                                $stm->execute();
                                $ntaken = $stm->fetch(PDO::FETCH_ASSOC);
                            ?>
                            <div class="mt-4 text-center">จำนวนผู้เข้าสอบ: <?php __($ntaken['c']); ?></div>
                        </div>
                        <div class="col-6">
                            <?php 
                                $stm = $_DB->prepare('SELECT COUNT(uid) AS c FROM adaptive_answer_data WHERE subject = :subject AND session = :session AND examination = :exam AND question = :question AND temp = 0');
                                $stm->bindParam(':subject', $session['examination_subject']);
                                $stm->bindParam(':session', $session['session_id']);
                                $stm->bindParam(':exam', $session['examination_id']);
                                $stm->bindParam(':question', $exam_row['qa_id']);
                                $stm->execute();
                                $antaken = $stm->fetch(PDO::FETCH_ASSOC);
                            ?>
                            <div class="mt-4 text-center">จำนวนผู้เข้าสอบ: <?php __($antaken['c']); ?></div>
                        </div>
                        <div class="col-12 mt-4">
                            <hr>
                            <?php echo strip_tags($exam_row['qa_question']); ?>
                            <hr>
                            <p><strong>A.</strong> <?php echo $exam_row['qa_choice_1']; ?> <?php echo in_array(1, $answer_arr) ? '<i class="fa fa-check text-success"></i> ' : ''; ?>
                            </p>
                            <p><strong>B.</strong> <?php echo $exam_row['qa_choice_2']; ?> <?php echo in_array(2, $answer_arr) ? '<i class="fa fa-check text-success"></i> ' : ''; ?>
                            </p>
                            <p><strong>C.</strong> <?php echo $exam_row['qa_choice_3']; ?> <?php echo in_array(3, $answer_arr) ? '<i class="fa fa-check text-success"></i> ' : ''; ?>
                            </p>
                            <p><strong>D.</strong> <?php echo $exam_row['qa_choice_4']; ?> <?php echo in_array(4, $answer_arr) ? '<i class="fa fa-check text-success"></i> ' : ''; ?>
                            </p>
                            <button class="btn btn-info btn-block mt-3" type="button" data-toggle="modal" data-target="#adapReport">Adaptive Report (ข้อมูลการวิเคราะห์)</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="adapReport" tabindex="-1" role="dialog" aria-labelledby="adapReportLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="adapReportLabel"><?php echo strip_tags($exam_row['qa_question']); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="g-container"></div>
                <div class="mt-3">
                    <?php
                        $report_s = json_decode($exam_row['qa_report'], true);
                    ?>
                    <p><strong>DIM: </strong> <?php __($report_s['dim']); ?></p>
                    <p><strong>BIAS: </strong> <?php __($report_s['bias']); ?></p>
                </div>
                <?php
                    $stm23 = $_DB->prepare("SELECT COUNT(DISTINCT(answer_data.uid)) AS stdn FROM answer_data JOIN users ON answer_data.uid = users.uid WHERE answer_data.question = :qa_id");
                    $stm23->bindParam(':qa_id', $exam_row['qa_id'], PDO::PARAM_INT);
                    $stm23->execute();
                    $row23 = $stm23->fetch(PDO::FETCH_ASSOC);
                ?>
                <p class="mt-3">จากข้อมูลผู้ทดสอบทั้งหมด <?php __($row23['stdn']); ?> คน</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
        </div>
        <script>
            var chart = Highcharts.chart('g-container', {
                            yAxis: {
                                title: {
                                    text: 'Answer (Correct)'
                                },
                                min: 0,
                                max: 1
                            },
                            xAxis: {
                                title: {
                                    text: 'Student Ability'
                                }
                            },
                            legend: {
                                layout: 'vertical',
                                align: 'right',
                                verticalAlign: 'middle'
                            },
                            plotOptions: {
                                series: {
                                    label: {
                                        connectorAllowed: false
                                    },
                                    pointStart: -3.00,
                                    pointInterval: 0.01
                                }
                            },
                            <?php //#endregion
                                $gs = json_decode($exam_row['qa_graph'], true);
                            ?>
                            series: [{
                                name: "<?php __($gs['name']); ?>",
                                data: <?php __($gs['data']); ?>
                            }],
                            responsive: {
                                rules: [{
                                    condition: {
                                        maxWidth: 500
                                    },
                                    chartOptions: {
                                        legend: {
                                            layout: 'horizontal',
                                            align: 'center',
                                            verticalAlign: 'bottom'
                                        }
                                    }
                                }]
                            }
                        });
        </script>
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
            <?php if (($ac1c['c'] + $ac2c['c'] + $ac3c['c'] + $ac4c['c']) >= 1) { ?>
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
                        'A (<?php echo round(($ac1c['c']+0 / $atotal) * 100); ?>%)',
                        'B (<?php echo round(($ac2c['c']+0 / $atotal) * 100); ?>%)',
                        'C (<?php echo round(($ac3c['c']+0 / $atotal) * 100); ?>%)',
                        'D (<?php echo round(($ac4c['c']+0 / $atotal) * 100); ?>%)'
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
            <?php } ?>
            window.onload = function() {
                var ctx = document.getElementById('choiceChart').getContext('2d');
                window.myPie = new Chart(ctx, Nconfig);
                var actx = document.getElementById('AdaptivechoiceChart').getContext('2d');
                window.myPie = new Chart(actx, Aconfig);
            };
        </script>
        