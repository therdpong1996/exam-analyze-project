<!-- Main content -->
<div class="main-content">
    <!-- Top navbar -->
    <nav class="navbar bg-gradient-primary navbar-top navbar-expand-md navbar-dark" id="navbar-main">
      <div class="container-fluid">
        <!-- Brand -->
        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block"><?php echo $session['examination_title']; ?></a>
        <?php require_once 'parts/usermenu.common.php'; ?>

    <!-- Page content -->
    <div class="container-fluid pb-5 pt-5 pt-md-8">
      <div class="row">
        <?php  ?>
        <div class="col-xl-12">
            <div class="card shadow mb-3">
                <div class="card-header">
                    <h2 class="mb-0">Request Password to Access <small>(กรอกรหัสผ่านเพื่อทดสอบ)</small></h2>
                </div>
                <div class="card-body pt-5 pb-5">
                    <form action="<?php echo url('doing-examination-adaptive/'.$session['session_id']); ?>" method="POST">
                        <div class="form-group row">
                          <label class="col-sm-2 col-form-label" for="password">รหัสผ่าน</label>
                          <div class="col-sm-10">
                            <input type="password" class="form-control" id="password" name="password" required placeholder="Password">
                            <?php if($password_fail){ ?>
                            <p class="text-danger">รหัสผ่านผิดพลาด</p>
                            <?php } ?>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-sm-2 col-form-label" for="password"></label>
                          <div class="col-sm-10">
                                <button class="btn btn-primary">Submit</button>
                          </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
      </div>
    </div>