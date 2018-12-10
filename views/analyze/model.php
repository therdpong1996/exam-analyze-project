<div class="col-xl-10">
            <div class="card shadow">
                <div class="card-header bg-transparent">
                    <div class="row align-items-center">
                    <div class="col">
                        <strong>Adaptive Model</strong>
                    </div>
                    </div>
                </div>
                <div class="card-body">
                    <?php
                        if ($session['session_model'] != null) {
                            $chart_data = json_decode($session['session_model'], true);
                            $report_data = json_decode($session['session_report'], true);
                    ?>
                    <div id="container"></div>
                    <button id="hide-all" class="mt-3 btn btn-primary btn-lg">Hide All</button>
                    <script>

                        Highcharts.chart('container', {
                            title: {
                                text: 'Sigmoid Graph Each Exercises'
                            },
                            tooltip: {
                                formatter: function () {
                                    return '<b>' + this.point.question + '</b><br /> Dim: ' + this.point.dim + ', Bias: ' + this.point.bias + '<br /> StuAbi: ' + this.x + ', Answer: ' + this.y;
                                }
                            },
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
                            series: [
                                <?php
                                    foreach ($chart_data as $data) {
                                        if ($data['name']) {
                                            $stm = $_DB->prepare("SELECT qa_question FROM q_and_a WHERE qa_id = :id");
                                            $stm->bindParam(":id", $data['name']);
                                            $stm->execute();
                                            $row = $stm->fetch(PDO::FETCH_ASSOC);
                                            echo '{';
                                            echo 'name: \''.$data['name'].'\',';
                                            echo 'data: '.$data['data'].',';
                                            echo 'bias:'.$report_data[$data['name']]['bias'].',';
                                            echo 'dim:'.$report_data[$data['name']]['dim'].',';
                                            echo 'question: \''.strip_tags(trim(preg_replace('/\s\s+/', ' ', $row['qa_question']))).'\',';
                                            echo '},';
                                        }
                                    }
                                ?>
                            ],
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

                        var $button = $('#hide-all');
                        $button.click(function () {
                            for (x in chart.series){
                                f (chart.series[x].visible) {
                                    chart.series[x].hide();
                                    $button.html('Show series');
                                } else {
                                    chart.series[x].show();
                                    $button.html('Hide series');
                                }
                            }
                        });
                    </script>
                    <?php
                        } else {
                            ?>
                            <div class="text-center mt-5 mb-5">
                                <form action="javascript:void(0)" id="train-data">
                                    <input type="hidden" name="session" value="<?php echo $session['session_id']; ?>">
                                    <input type="hidden" name="token" value="<?php echo md5('computerizedadaptivetesting'.$session['session_id']); ?>">
                                    <button id="train-btn" type="submit" <?php echo timebetween($session['session_start'], $session['session_end']) ? 'disabled' : ''; ?> class="btn btn-success btn-lg <?php echo timebetween($session['session_start'], $session['session_end']) ? 'disabled' : ''; ?>">Train data and plot graph</button>
                                </form>
                                <?php if (timebetween($session['session_start'], $session['session_end'])) {
                                ?>
                                    <p class="text-danger mt-3">จะสามารถ train data ได้เมื่อเวลาการทดสอบสิ้นสุดลงแล้วเท่านั้น</p>
                                    <?php
                            } ?>
                            </div>

                    <?php
                        }
                    ?>
                </div>
            </div>
        </div>
        <script>
            $('#train-data').on('submit', function(){
                var oldtext = $('#train-btn').html();
                $('#train-btn').html('<i class="fa fa-spinner fa-spin"></i> Process..');
                var sData = $(this).serialize();
                $.ajax({
                    type: "POST",
                    url: webservice + "train/",
                    data: sData,
                    dataType: "json"
                })
                .done(function(response){
                    if(response.state){
                    swal({
                        title: 'SUCCESS',
                        text: response.msg,
                        type: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Yes'
                    }).then(function(result){
                        if (result.value) {
                        window.location.href = window.location.href;
                        }
                    });
                    }else{
                    $('#train-btn').html(oldtext);
                    swal(
                        'SORRY',
                        response.msg,
                        'error'
                    );
                    }
                });
                });
        </script>