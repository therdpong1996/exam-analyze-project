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
                        if ($session['session_train'] == 1) {
                            $stm1 = $_DB->prepare("SELECT * FROM adaptive_table WHERE adap_id = :aid");
                            $stm1->bindParam(":aid", $session['session_adap']);
                            $stm1->execute();
                            $adrow = $stm1->fetch(PDO::FETCH_ASSOC);
                            $chart_data = json_decode($adrow['graph_file'], true);
                    ?>
                    <div id="container"></div>
                    <div class="row">
                        <div class="col-6"><button id="hide-all" class="mt-3 btn btn-primary btn-lg btn-block">Hide All</button></div>
                        <div class="col-6"><button id="show-all" class="mt-3 btn btn-info btn-lg btn-block">Show All</button></div>
                    </div>
                    
                    <script>

                        var report = <?php echo $adrow['report_file']; ?>;
                        var chart = Highcharts.chart('container', {
                            title: {
                                text: 'For each problem'
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
                            tooltip: {
                                formatter: function () {
                                    return '<b>' + this.series.name + '</b><br /> Dim: ' + report[this.series.name]['dim'] + ', Bias: ' + report[this.series.name]['bias'] + '<br /> StuAbi: ' + this.x + ', Answer: ' + this.y;
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
                                            echo '{';
                                            echo 'name: \''.$data['name'].'\',';
                                            echo 'data: '.$data['data'].',';
                                            echo 'visible: false,';
                                            echo 'label: {enabled: false}';
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

                        $('#hide-all').click(function () {
                            for (x in chart.series){
                                chart.series[x].hide();
                            }
                        });

                        $('#show-all').click(function () {
                            for (x in chart.series){
                                chart.series[x].show();
                            }
                        });
                    </script>
                    <?php
                        } else {
                            if($_GS['init_graph']){
                            ?>
                            <div class="mt-5 mb-5">
                                <form action="javascript:void(0)" id="train-data">
                                    <?php
                                        $stmt = $_DB->prepare("SELECT session,COUNT(DISTINCT(question)) AS cn FROM answer_data WHERE examination = :exam AND session != :session GROUP BY session ORDER BY session DESC LIMIT 1");
                                        $stmt->bindParam(":exam", $session['session_exam']);
                                        $stmt->bindParam(":session", $session['session_id']);
                                        $stmt->execute();
                                        $row = $stmt->fetch(PDO::FETCH_ASSOC);

                                        $stmt1 = $_DB->prepare("SELECT session,COUNT(DISTINCT(question)) AS cn FROM answer_data WHERE examination = :exam AND adap_table = :adapt GROUP BY session ORDER BY session DESC LIMIT 1");
                                        $stmt1->bindParam(":exam", $session['session_exam']);
                                        $stmt1->bindParam(":adapt", $session['session_adap']);
                                        $stmt1->execute();
                                        $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);

                                        $stmt2 = $_DB->prepare("SELECT session,COUNT(DISTINCT(question)) AS cn FROM answer_data WHERE examination = :exam AND session = :session");
                                        $stmt2->bindParam(":exam", $session['session_exam']);
                                        $stmt2->bindParam(":session", $session['session_id']);
                                        $stmt2->execute();
                                        $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
                                    ?> 
                                    <input type="hidden" name="adap_id" value="<?php echo $session['session_adap']; ?>">
                                    <input type="hidden" name="examination" value="<?php echo $session['session_exam']; ?>">
                                    <input type="hidden" name="session" value="<?php echo $session['session_id']; ?>">
                                    <input type="hidden" name="token" value="<?php echo md5('computerizedadaptivetesting'.$session['session_id']); ?>">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <select class="form-control" name="action">
                                                    <option value="0" disabled selected>เลือกรูปแบบการ Train</option>
                                                    <option value="1" <?php echo ($row['cn'] != $row2['cn'])?'disabled':''; ?>>เลือกข้อมูลทั้งหมดในคลังข้อสอบ</option>
                                                    <option value="2" <?php echo ($row1['cn'] != $row2['cn'])?'disabled':''; ?>>เลือกเฉพาะข้อมูลที่เกี่ยวข้อง</option>
                                                    <option value="3">เลือกเฉพาะของเซสซั่นนี้</option>
                                                </select>  
                                            </div>
                                            <button id="train-btn" type="submit" <?php echo timebetween($session['session_start'], $session['session_end']) ? 'disabled' : ''; ?> class="btn btn-success btn-lg <?php echo timebetween($session['session_start'], $session['session_end']) ? 'disabled' : ''; ?>">Train data</button>
                                        </div>
                                        <div class="col-6">
                                        </div>
                                    </div>
                                    
                                </form>
                                <?php if (timebetween($session['session_start'], $session['session_end'])) { ?>
                                    <p class="text-danger mt-3">จะสามารถ train data ได้เมื่อเวลาการทดสอบสิ้นสุดลงแล้วเท่านั้น</p>
                                <?php } ?>
                            </div>

                    <?php }else{ ?>
                            <div class="text-center mt-5 mb-5 pt-5 pb-5">
                                <h2>ฟังก์ชันนี้กำลังอยู่ในช่วงการปรับปรุง</h2>
                            </div>
                    <?php
                            }
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