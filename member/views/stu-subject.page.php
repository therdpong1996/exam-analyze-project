<!-- Main content -->
<div class="main-content">
    <!-- Top navbar -->
    <nav class="navbar bg-gradient-primary navbar-top navbar-expand-md navbar-dark" id="navbar-main">
        <div class="container-fluid">
        <!-- Brand -->
        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="<?php url('stu-subject/'); ?>">Subject <small>(รายวิชา)</small></a>
        <?php require_once 'parts/usermenu.common.php'; ?>
    <!-- Page content -->
    <div class="container-fluid pb-8 pt-5 pt-md-8">
        <div class="row">
        <div class="col-9">
            <div class="form-group">
                <input type="text" id="subject-invite" name="subject-invite" class="form-control" placeholder="Code 10 Characters" />
            </div>
        </div>
        <div class="col-3">
            <button class="btn btn-success btn-block" id="invite-check"><i class="fas fa-search"></i></span> ตรรวจสอบ</button>
        </div>
        <div class="col-xl-12">
                <div class="card shadow mt-3">
                <div class="card-header bg-transparent">
                    <div class="row align-items-center">
                    <div class="col">
                        <h6 class="text-uppercase text-muted ls-1 mb-1">รายชื่อวิชา</h6>
                        <h2 class="mb-0">Subject</h2>
                    </div>
                    </div>
                </div>
                <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-items-center">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">Subject</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $stm = $_DB->prepare('SELECT * FROM student_subject WHERE uid = :uid ORDER BY subject_id ASC');
                            $stm->bindParam(":uid", $user_row['uid']);
                            $stm->execute();
                            while ($rows = $stm->fetch(PDO::FETCH_ASSOC)) {
                                $stmt = $_DB->prepare('SELECT * FROM subjects WHERE subject_id = :id LIMIT 1');
                                $stmt->bindParam(":id", $rows['subject_id']);
                                $stmt->execute();
                                $subj = $stmt->fetch(PDO::FETCH_ASSOC);
                        ?>
                        <tr id="subject-<?php echo $subj['subject_id'].$user_row['uid']; ?>">
                            <th scope="row">
                                <span class="mb-0 text-sm"><?php echo $subj['subject_title']; ?></span>
                            </th>
                            <td class="text-right">
                                <button id="stu-delete-btn-<?php __($subj['subject_id'].$user_row['uid']); ?>" onclick="stusubject_delete(<?php __($subj['subject_id']); ?>, <?php __($user_row['uid']); ?>)" class="btn btn-danger btn-sm">Signout</button>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                    </table>
                </div>
                </div>
            </div>
        </div>
        </div>
    </div>
    <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModal" aria-hidden="true">
        <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
            <div class="modal-content">
                
                <div class="modal-header">
                    <h2 class="modal-title" id="modal-confirm-title"></h2>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                
                <div class="modal-body">
                    
                    <p id="modal-detail"></p>
                    <p>ผู้สอน: <span id="modal-user"></span></p>
                    
                </div>
                
                <div class="modal-footer">
                    <form action="javascript:void(0)" id="confirm-subject">
                        <input type="hidden" name="uid" value="<?php __($user_row['uid']); ?>">
                        <input type="hidden" name="subject_id" id="modal-subject_id" value="">
                        <button type="submit" class="btn btn-primary" id="confirm-btn">Accept</button>
                    </form>
                    <button type="button" class="btn btn-link  ml-auto" data-dismiss="modal">Close</button> 
                </div>
                
            </div>
        </div>
    </div>

    <script>
        function stusubject_delete(id, uid){
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, signout it!'
            }).then(function (result) {
                if (result.value) {
                    var oldtext = $('#stu-delete-btn-'+id+uid).html();
                    $('#stu-delete-btn-'+id+uid).html('<i class="fa fa-spinner fa-spin"></i> Process..');
                    $.ajax({
                        type: "POST",
                        url: weburl + "ajax/stu-subject-signout",
                        data: {id: id, uid: uid},
                        dataType: "json",
                        success: function (response) {
                            if(response.state){
                                $('#subject-'+id+uid).remove();
                            }
                        }
                    });
                }
            })
        }

        $(document).ready(function () {
            $('#invite-check').on('click', function(e){
                var oldtext = $('#invite-check').html();
                $('#invite-check').html('<i class="fa fa-spinner fa-spin"></i> Process..');
                var incode = $('input#subject-invite').val()
                if (incode.lenght < 1) {
                    alert('not found code');
                    return;
                }
                $.ajax({
                    type: "POST",
                    url: weburl + "ajax/stu-subject-check",
                    data: {code: incode},
                    dataType: "json",
                    success: function (response) {
                        if (response.subject_id) {
                            $('#modal-confirm-title').html(response.subject_title)
                            $('#modal-detail').html(response.subject_detail)
                            $('#modal-user').html(response.user)
                            $('#modal-subject_id').val(response.subject_id)
                            $('#invite-check').html(oldtext);
                            $('#confirmModal').modal('show')
                        }else{
                            $('#invite-check').html(oldtext)
                        }
                    }
                });
            })

            $('#confirm-subject').on('submit', function(){
                var oldtext = $('#confirm-btn').html();
                $('#confirm-btn').html('<i class="fa fa-spinner fa-spin"></i> Process..');
                var data = $(this).serialize()
                $.ajax({
                    type: "POST",
                    url: weburl + "ajax/stu-subject-confirm",
                    data: data,
                    dataType: "json",
                    success: function (response) {
                        if (response.state) {
                            $('#confirm-btn').html(oldtext);
                            window.location.href = window.location.href;
                        }
                    }
                });
            })
        });
    </script>