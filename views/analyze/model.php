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
                                <button <?php echo timebetween($session['session_start'], $session['session_end']) ? 'disabled' : ''; ?> class="btn btn-success btn-lg <?php echo timebetween($session['session_start'], $session['session_end']) ? 'disabled' : ''; ?>">Train data and plot graph</button>
                                <?php if (timebetween($session['session_start'], $session['session_end'])) {
                                ?>
                                    <p class="text-danger">จะสามารถ train data ได้เมื่อเวลาการทดสอบสิ้นสุดลงแล้วเท่านั้น</p>
                                    <?php
                            } ?>
                            </div>

                    <?php
                        }
                    ?>
                </div>
            </div>
        </div>