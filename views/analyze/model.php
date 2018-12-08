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
                            ?>
                            
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