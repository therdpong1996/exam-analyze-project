<div class="col-xl-10">
            <div class="card shadow">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-items-center">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Time Taken</th>
                                    <th scope="col">Score</th>
                                    <th scope="col">Date</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $stm = $_DB->prepare('SELECT * FROM adaptive_session_score JOIN sessions ON session_score.session_id = sessions.session_id JOIN subjects ON session_score.subject_id = subjects.subject_id JOIN users ON session_score.uid = users.uid WHERE session_score.session_id = :session ORDER BY users.stu_id ASC');
                                    $stm->bindParam(':session', $_GET['session_id']);
                                    $stm->execute();
                                    while ($rows = $stm->fetch(PDO::FETCH_ASSOC)) {
                                        $stm2 = $_DB->prepare('SELECT * FROM adaptive_time_remaining WHERE uid = :uid AND session = :session');
                                        $stm2->bindParam(':uid', $rows['uid']);
                                        $stm2->bindParam(':session', $rows['session_id']);
                                        $stm2->execute();
                                        $timet = $stm2->fetch(PDO::FETCH_ASSOC);
                                        $timet['time_remaining'] = ($timet['time_remaining'] < 0 ? 0 : $timet['time_remaining']); 
                                ?>
                                        <tr id="score-<?php echo $rows['score_id']; ?>">
                                            <th scope="row">
                                                <span class="mb-0 text-sm"><?php echo $rows['stu_id']; ?></span>
                                            </th>
                                            <td>
                                                <span class="mb-0 text-sm"><?php echo $rows['full_name']; ?></span>
                                            </td>
                                            <td>
                                            <span class="mb-0 text-sm"><?php echo isset($timet['time_remaining']) ? floor((($rows['session_timeleft'] * 60) - $timet['time_remaining']) / 60).' min' : 'N/A'; ?></span>
                                            </td>
                                            <td>
                                                <?php echo $rows['score']; ?>/<?php echo $rows['score_full']; ?>
                                            </td>
                                            <td>
                                                <?php echo $rows['finish_tile']; ?>
                                            </td>
                                            <td>
                                                <button id="reset_sc_<?php echo $rows['uid']; ?>" class="btn btn-danger btn-sm" onclick="resetadapscore(<?php echo $rows['score_id']; ?>, <?php echo $rows['session_id']; ?>, <?php echo $rows['uid']; ?>)">รีเซ็ต</button>
                                            </td>
                                        </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <script>
            resetadapscore = function(sc_id, ses_id, uid){
                swal({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, reset it!'
                }).then(function(result){
                    if (result.value) {
                        var oldtext = $('#reset_sc_' + uid).html();
                        $('#reset_sc_' + uid).html('<i class="fa fa-spinner fa-spin"></i>');
                        $.ajax({
                            type: "POST",
                            url: weburl + "ajax/reset_adap_score",
                            data: {sc_id: sc_id, session_id: ses_id, uid: uid},
                            dataType: "json"
                        })
                        .done(function(response){
                            if(response.state){
                                $('#score-' + sc_id).remove();
                            }else{
                            $('#delete-btn').html(oldtext);
                            swal(
                                'ERROR',
                                response.msg,
                                'error'
                            );
                            }
                        });
                    }
                })
            }
        </script>