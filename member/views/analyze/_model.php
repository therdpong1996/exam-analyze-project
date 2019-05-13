<div class="col-xl-12">
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
                        if ($examination['examination_train'] == 1) {
                            $stm1 = $_DB->prepare("SELECT * FROM adaptive_table WHERE adap_id = :aid");
                            $stm1->bindParam(":aid", $examination['examination_table']);
                            $stm1->execute();
                            $adrow = $stm1->fetch(PDO::FETCH_ASSOC);
                            $chart_data = json_decode($adrow['graph_file'], true);
                    ?>
                    <div id="container"></div>
                    <div class="row">
                        <div class="col-3"><button id="hide-all" class="mt-3 btn btn-primary btn-lg btn-block">Hide All</button></div>
                        <div class="col-3"><button id="show-all" class="mt-3 btn btn-info btn-lg btn-block">Show All</button></div>
                        <div class="col-6"><form action="javascript:void(0)" id="train-data">
                        <input type="hidden" name="uid" value="<?php echo $user_row['uid']; ?>">
                                    <input type="hidden" name="adap_id" value="<?php echo $examination['examination_table']; ?>">
                                    <input type="hidden" name="examination" value="<?php echo $examination['examination_id']; ?>">
                                    <input type="hidden" name="token" value="<?php echo md5('computerizedadaptivetesting'.$examination['examination_id']); ?>"><button type="submit" id="train-btn" class="mt-3 btn btn-success btn-lg btn-block">Refresh Graph</button></form></div>
                    </div>
                    
                    <script>

                        var report = <?php echo $adrow['report_file']; ?>;
                        var chart = Highcharts.chart('container', {
                            title: {
                                text: 'Item Characteristic Curve'
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
                                    events: {
                                        click: function (event) {
                                            $.ajax({
                                                type: "POST",
                                                url: weburl + "ajax/lineDetail",
                                                data: {id: this.name, exam: '<?php echo $examination['examination_id']; ?>'},
                                                dataType: "json",
                                                success: function (response) {
                                                    alert(
                                                    '(' + response.qa_id + ') ' + response.qa_question + '\n' +
                                                    <?php 
                                                        for($i=1; $i <= $adrow['dimensional']; $i++){
                                                            echo "'Dim".$i.": ' + report[response.qa_id.toString()]['dim".$i."'] + '\\n' +";
                                                        }
                                                    ?>
                                                    'Bias: ' + report[response.qa_id.toString()]['bias']
                                                );
                                                }
                                            });
                                        }
                                    },
                                    pointStart: -3.00,
                                    pointInterval: 0.01
                                }
                            },
                            series: [
                                <?php
                                    foreach ($chart_data as $data) {
                                        if ($data['name']) {
                                            $stm1_order = $_DB->prepare("SELECT qa_order FROM q_and_a WHERE qa_id = :qaid LIMIT 1");
                                            $stm1_order->bindParam(":qaid", $data['name']);
                                            $stm1_order->execute();
                                            $orderid = $stm1_order->fetch(PDO::FETCH_ASSOC);
                                            echo '{';
                                            echo 'name: \''.$orderid['qa_order'].'\',';
                                            echo 'data: '.$data['data'].',';
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
                                    <input type="hidden" name="uid" value="<?php echo $user_row['uid']; ?>">
                                    <input type="hidden" name="adap_id" value="<?php echo $examination['examination_table']; ?>">
                                    <input type="hidden" name="examination" value="<?php echo $examination['examination_id']; ?>">
                                    <input type="hidden" name="token" value="<?php echo md5('computerizedadaptivetesting'.$examination['examination_id']); ?>">
                                    <div class="row">
                                        <div class="col-12 mb-4">
                                            <label>มิติในการวิเคราะห์ข้อมูล</label><br>
                                            <input type="number" class="form-control" name="dimensional" min="1" value="1">
                                        </div>
                                        <div class="col-12 text-center">
                                            <button id="train-btn" type="submit" class="btn btn-success btn-lg">วิเคราะห์ข้อมูลการสอบ</button>
                                        </div>
                                    </div>
                                    
                                </form>
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