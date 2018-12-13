        <div class="col-xl-10">
            <div class="card shadow">
                <div class="card-body" id="simulation-content">
                    <!--CONTENT FOR ADAPTIVE EXERCISES-->
                </div>
                <div class="card-footer">
                    <button id="next_exercise" class="btn btn-success" type="button">ถัดไป <i class="fa fa-arrow-circle-right"></i></button>
                </div>
            </div>

            <script>

                let session_id = <?php echo $session['session_id']; ?>;
                let userid = <?php echo $user_row['uid']; ?>;
                let number = <?php echo $session['session_adap_number']; ?>;

            </script>

            <div class="card shadow mt-3">
                <div class="card-body">
                    <div id="container"></div>
                    <div class="row">
                        <div class="col-3"><button id="hide-all" class="mt-3 btn btn-primary btn-lg btn-block">Hide All</button></div>
                        <div class="col-3"><button id="show-all" class="mt-3 btn btn-info btn-lg btn-block">Show All</button></div>
                        <div class="col-6">
                            <form action="javascript:void(0)" id="plot-data">
                                <input type="hidden" name="session" value="<?php echo $session['session_id']; ?>">
                                <input type="hidden" name="token" value="<?php echo md5('computerizedadaptivetesting'.$session['session_id']); ?>">
                                <button id="plot-btn" class="btn btn-success btn-lg mt-3 btn-block" type="submit">Ganerate Graph Again</button>
                            </form>
                        </div>
                    </div>
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
                                text: 'Sigmoid Graph Each Exercises'
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
                        }
                    });
                });

                // TIME TAKEN
                var time_taken = 1;
                setInterval(function(){
                    $('input#time_taken').val(time_taken);
                    time_taken++;
                },1000)

                $('a').on('click', function (e){ 
                    e.preventDefault();
                    var data = $('form#doing-exam-form').serialize();
                    $.ajax({
                        url: weburl + 'ajax/simulation_delete',
                        type: 'POST',
                        dataType: 'json',
                        data: data,
                    })
                    .done(function() {});
                    window.location.href = $(this).attr('href');
                })

                    </script>
                </div>
            </div>
        </div>