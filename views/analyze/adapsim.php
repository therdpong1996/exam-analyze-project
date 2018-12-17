        <div class="col-xl-10">
            <div class="card shadow">
                <div class="card-header">
                    <strong>ข้อที่ <span id="num_current">1</span>/<?php echo $session['session_adap_number']; ?></strong>
                </div>
                <div class="card-body" id="simulation-content">
                    <!--CONTENT FOR ADAPTIVE EXERCISES-->
                    <div class="text-center pt-5 pb-5"><i class="fas fa-spinner fa-spin fa-3x"></i></div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-6">
                            <button id="next_exercise" class="btn btn-success" type="button">ถัดไป <i class="fa fa-arrow-circle-right"></i></button>
                        </div>
                        <div class="col-6 text-right">
                            <button id="reset-sim" class="btn btn-warning" type="button"><i class="fas fa-trash"></i> รีเซ็ต</button>
                            <button id="report-modal" class="btn btn-info" type="button" data-toggle="modal" data-target="#reportModal"><i class="fas fa-paper-plane"></i> สรุปผล</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="reportModal" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        
                        <div class="modal-header">
                            <h2 class="modal-title">สรุปผล</h2>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        
                        <div class="modal-body">
                            <div class="table-responsive">
                                <table class="table align-items-center">
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col">ข้อ</th>
                                            <th scope="col">คำถาม</th>
                                            <th scope="col">คำตอบ</th>
                                            <th scope="col">ผลลัพธ์</th>
                                        </tr>
                                    </thead>
                                    <tbody id="report-table">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <div class="modal-footer">
                            <button type="button" class="btn btn-link  ml-auto" data-dismiss="modal">ปิด</button> 
                        </div>
                        
                    </div>
                </div>
            </div>
            <script>
                var num_cur = <?php echo isset($_COOKIE['sim_number'])?$_COOKIE['sim_number']:1; ?>;
                let session_id = <?php echo $session['session_id']; ?>;
                let userid = <?php echo $user_row['uid']; ?>;
                let number = <?php echo $session['session_adap_number']; ?>;

            </script>

            <div class="card shadow mt-3">
                <div class="card-body">
                    <div id="container"></div>
                    
                    <?php 
                        $chart_data = json_decode($session['session_model'], true);
                    ?>
                    <script>
                        $('#plot-data').on('submit', function(){
                            var oldtext = $('#plot-btn').html();
                            $('#plot-btn').html('<i class="fa fa-spinner fa-spin"></i> Process..');
                            var sData = $(this).serialize();
                            $.ajax({
                                type: "POST",
                                url: webservice + "plot/",
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
                                $('#plot-btn').html(oldtext);
                                swal(
                                    'SORRY',
                                    response.msg,
                                    'error'
                                );
                                }
                            });
                        });

                        var report = <?php echo $session['session_report']; ?>;
                        var chart = Highcharts.chart('container', {
                            title: {
                                text: 'Simulation for each problem'
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

                    function findChartIndex(name){
                        var index = 0;
                        for(x in chart.series){
                            if (chart.series[x].name == name) {
                                return index;
                            }else{
                                index++;
                            }
                        }
                    }

                const content = $('div#simulation-content');
                //FIRST VISIT
                $(document).ready(function () {
                    content.html('<div class="text-center pt-5 pb-5"><i class="fas fa-spinner fa-spin fa-3x"></i></div>');
                    $.ajax({
                        type: "POST",
                        url: weburl + "ajax/simulation_firstrun",
                        data: {userid: userid, session: session_id, number: number},
                        dataType: "html",
                        success: function (response) {
                            content.html(response)
                            var qid = $('input#question').val();
                            var cIndex = findChartIndex(qid);
                            chart.series[cIndex].show();
                            $('#num_current').html(num_cur)
                            Cookies.set('sim_number', num_cur, { expires: 7, path: '/' });
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
                        url: weburl + "ajax/simulation_continue",
                        data: data,
                        dataType: "html",
                        success: function (response) {
                            content.html(response)
                            var qid = $('input#question').val();
                            var cIndex = findChartIndex(qid);
                            chart.series[cIndex].show();
                            num_cur++;
                            $('#num_current').html(num_cur)
                            Cookies.set('sim_number', num_cur, { expires: 7, path: '/' });
                        }
                    });

                    $.ajax({
                        type: "POST",
                        url: weburl + "ajax/simulation_report",
                        data: data,
                        dataType: "html",
                        success: function (response) {
                            $('#report-table').append(response);
                        }
                    });

                });

                // TIME TAKEN
                var time_taken = 1;
                setInterval(function(){
                    $('input#time_taken').val(time_taken);
                    time_taken++;
                },1000)

                $('button#reset-sim').on('click', function (e){
                    e.preventDefault();
                    var data = $('form#doing-exam-form').serialize();
                    $.ajax({
                        url: weburl + 'ajax/simulation_delete',
                        type: 'POST',
                        dataType: 'json',
                        data: data,
                    })
                    .done(function() {});
                    window.location.href = window.location.href
                })

                    </script>
                </div>
            </div>
        </div>