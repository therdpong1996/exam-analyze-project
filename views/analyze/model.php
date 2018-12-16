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
                    ?>
                    <div id="container"></div>
                    <div class="row">
                        <div class="col-3"><button id="hide-all" class="mt-3 btn btn-primary btn-lg btn-block">Hide All</button></div>
                        <div class="col-3"><button id="show-all" class="mt-3 btn btn-info btn-lg btn-block">Show All</button></div>
                        <div class="col-6">
                            <form action="javascript:void(0)" id="plot-data">
                                <input type="hidden" name="session" value="<?php echo $session['session_id']; ?>">
                                <input type="hidden" name="token" value="<?php echo md5('computerizedadaptivetesting'.$session['session_id']); ?>">
                                <button id="plot-btn" class="btn btn-success btn-lg mt-3 btn-block" type="submit">Refresh</button>
                            </form>
                        </div>
                    </div>
                    
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