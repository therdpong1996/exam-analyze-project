<!-- Main content -->
<div class="main-content">
    <!-- Top navbar -->
    <nav class="navbar bg-gradient-primary navbar-top navbar-expand-md navbar-dark" id="navbar-main">
        <div class="container-fluid">
        <!-- Brand -->
        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="<?php url('admin/users'); ?>">User Management</a>
        <?php require_once 'parts/usermenu.common.php'; ?>
    <!-- Page content -->
    <div class="container-fluid pb-8 pt-5 pt-md-8">
        <div class="row">
            <div class="col-xl-12">
            <a class="btn btn-success mb-3" href="?add"><span class="btn-inner--icon"><i class="ni ni-fat-add"></i></span> เพิ่มรายวิชา</a>
                <div class="card shadow">
                <div class="card-header bg-transparent">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="text-uppercase text-muted ls-1 mb-1">รายการผู้ใช้งาน</h6>
                            <h2 class="mb-0">User List</h2>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-items-center dataTable">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">Username</th>
                                <th scope="col">Name</th>
                                <th scope="col">Role</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                    <tbody>
                        <?php
                            $stm1 = $_DB->prepare("SELECT * FROM users JOIN users_role_title ON users.role = users_role_title.role_id ORDER BY uid");
                            $stm1->execute();
                            while ($rows = $stm1->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                        <tr>
                            <th scope="row">
                                <span class="mb-0 text-sm"><?php echo $rows['username']; ?></span>
                            </th>
                            <td>
                                <?php echo $rows['full_name']; ?>
                            </td>
                            <td>
                                <?php echo $rows['role_title']; ?>
                            </td>
                            <td class="text-right">
                                <button id="ban-btn-<?php echo $rows['uid']; ?>" onclick="useraction(<?php echo $rows['uid']; ?>, 'ban')" class="btn btn-warning btn-sm">Ban</button>
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
    <script>
    useraction = function(uid, action){
        var oldtext = $('#ban-btn-' + uid).html();
        $('#ban-btn-' + uid).html('<i class="fa fa-spinner fa-spin"></i> Process..');

        $.ajax({
            type: "POST",
            url: weburl + "ajax/ban-user",
            data: {uid: uid, action: action},
            dataType: "json",
            success: function (response) {
                if(response.state){
                    if(action == 'ban'){
                        $('#ban-btn-' + uid).html('Unban').removeClass('btn-warning').addClass('btn-info').attr('onclick', 'useraction('+uid+', \'unban\')');
                    }else{
                        $('#ban-btn-' + uid).html('Ban').removeClass('btn-info').addClass('btn-warning').attr('onclick', 'useraction('+uid+', \'ban\')');
                    }
                }else{
                    $('#ban-btn-' + uid).html(oldtext);
                    swal(
                        'SORRY',
                        response.msg,
                        'error'
                    );
                }
            }
        });
    }
    </script>