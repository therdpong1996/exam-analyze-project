<!-- Main content -->
<div class="main-content">
    <!-- Top navbar -->
    <nav class="navbar bg-danger navbar-top navbar-expand-md navbar-dark" id="navbar-main">
      <div class="container-fluid">
        <!-- Brand -->
        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="<?php url('session/'); ?>">Access Denied <small>(ไม่อนุญาตให้เข้าใช้งาน)</small></a>
        <?php require_once 'parts/usermenu.common.php'; ?>
        <!-- Page content -->
        <div class="container-fluid pb-8 pt-5 pt-md-8">
          <div class="row">
            <div class="col-xl-12">
                  <div class="card shadow">
                    <div class="card-body text-center">
                      <i class="fas fa-times-circle fa-10x text-danger mb-3"></i>
                      <h1>Access Denied</h1>
                      <p class="text-muted">ไม่อนุญาตให้เข้าใช้งาน</p>
                    </div>
                  </div>
              </div>
            </div>
        </div>