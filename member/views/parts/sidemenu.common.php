
  <!-- Sidenav -->
  <nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main">
    <div class="container-fluid">
      <!-- Toggler -->
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <!-- Brand -->
      <a class="navbar-brand pt-0" href="">
        <img src="<?php url('assets/img/brand/blue.png'); ?>" class="navbar-brand-img" alt="logo">
      </a>
      <!-- User -->
      <ul class="nav align-items-center d-md-none">
        <li class="nav-item dropdown">
          <a class="nav-link nav-link-icon" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ni ni-bell-55"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right" aria-labelledby="navbar-default_dropdown_1">
            <a class="dropdown-item" href="#">Action</a>
            <a class="dropdown-item" href="#">Another action</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">Something else here</a>
          </div>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <div class="media align-items-center">
              <span class="avatar avatar-sm rounded-circle">
                <img alt="Image placeholder" src="<?php url('assets/img/theme/team-1-800x800.jpg'); ?>">
              </span>
            </div>
          </a>
          <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
            <div class=" dropdown-header noti-title">
              <h6 class="text-overflow m-0">Welcome!</h6>
            </div>
            <a href="./examples/profile.html" class="dropdown-item">
              <i class="ni ni-single-02"></i>
              <span>My profile</span>
            </a>
            <a href="./examples/profile.html" class="dropdown-item">
              <i class="ni ni-settings-gear-65"></i>
              <span>Settings</span>
            </a>
            <a href="./examples/profile.html" class="dropdown-item">
              <i class="ni ni-calendar-grid-58"></i>
              <span>Activity</span>
            </a>
            <a href="./examples/profile.html" class="dropdown-item">
              <i class="ni ni-support-16"></i>
              <span>Support</span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="#!" class="dropdown-item">
              <i class="ni ni-user-run"></i>
              <span>Logout</span>
            </a>
          </div>
        </li>
      </ul>
      <!-- Collapse -->
      <div class="collapse navbar-collapse" id="sidenav-collapse-main">
        <!-- Collapse header -->
        <div class="navbar-collapse-header d-md-none">
          <div class="row">
            <div class="col-6 collapse-brand">
              <a href="">
                <img src="<?php url('assets/img/brand/blue.png'); ?>">
              </a>
            </div>
            <div class="col-6 collapse-close">
              <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle sidenav">
                <span></span>
                <span></span>
              </button>
            </div>
          </div>
        </div>
        <!-- Form -->
        <form class="mt-4 mb-3 d-md-none">
          <div class="input-group input-group-rounded input-group-merge">
            <input type="search" class="form-control form-control-rounded form-control-prepended" placeholder="Search" aria-label="Search">
            <div class="input-group-prepend">
              <div class="input-group-text">
                <span class="fa fa-search"></span>
              </div>
            </div>
          </div>
        </form>
        <!-- Navigation -->
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="<?php url('dashboard/'); ?>">
              <i class="ni ni-tv-2 text-primary"></i> Dashboard
            </a>
          </li>
          <?php if($user_row['role'] == 1) { ?>
          <li class="nav-item">
            <a class="nav-link" href="<?php url('admin/users'); ?>">
              <i class="fas fa-user text-blue"></i> User Manage
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php url('admin/setting'); ?>">
              <i class="fas fa-cog text-blue"></i> Website Setting
            </a>
          </li>
          <?php } elseif ($user_row['role'] == 2) { ?>
          <li class="nav-item">
            <a class="nav-link" href="<?php url('article/'); ?>">
              <i class="fas fa-book-open text-blue"></i> Article
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php url('subject/'); ?>">
              <i class="ni ni-folder-17 text-blue"></i> Subject
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php url('examination/'); ?>">
              <i class="ni ni-ruler-pencil text-blue"></i> Examination
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php url('session/'); ?>">
              <i class="ni ni-calendar-grid-58 text-blue"></i> Session
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php url('docs/'); ?>">
              <i class="fa fa-file-word text-blue"></i> Documents
            </a>
          </li>
          <?php } elseif ($user_row['role'] == 3) { ?>
          <li class="nav-item">
            <a class="nav-link" href="<?php url('stu-article/'); ?>">
              <i class="fas fa-book-reader text-blue"></i> Article Archive
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php url('stu-subject/'); ?>">
              <i class="ni ni-folder-17 text-blue"></i> Subject
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php url('stu-examination/'); ?>">
              <i class="ni ni-ruler-pencil text-blue"></i> Examination
            </a>
          </li>
          <?php } ?>
        </ul>
        <?php if ($user_row['role'] == 3) { ?>
        <hr class="my-3">
        <!-- Navigation -->
        <ul class="navbar-nav mb-md-3">
        <?php 
        $stm = $_DB->prepare('SELECT * FROM time_remaining JOIN sessions ON time_remaining.session = sessions.session_id JOIN examinations ON sessions.session_exam = examinations.examination_id WHERE time_remaining.uid = :uid AND time_remaining.time_status = 0');
        $stm->bindParam(':uid', $user_row['uid']);
        $stm->execute();
        while ($rows = $stm->fetch(PDO::FETCH_ASSOC)) {
        ?>
          <li class="nav-item">
            <a class="nav-link" href="<?php url('doing-examination/'.$rows['session_id']); ?>">
              <i class="fa fa-clock"></i> [กำลังทำ] <?php echo $rows['examination_title']; ?>
            </a>
          </li>
        <?php } ?>
        <?php 
        $stm = $_DB->prepare('SELECT * FROM adaptive_time_remaining JOIN sessions ON adaptive_time_remaining.session = sessions.session_id JOIN examinations ON sessions.session_exam = examinations.examination_id WHERE adaptive_time_remaining.uid = :uid AND adaptive_time_remaining.time_status = 0');
        $stm->bindParam(':uid', $user_row['uid']);
        $stm->execute();
        while ($rows = $stm->fetch(PDO::FETCH_ASSOC)) {
        ?>
          <li class="nav-item">
            <a class="nav-link" href="<?php url('doing-examination-adaptive/'.$rows['session_id']); ?>">
              <i class="fa fa-clock"></i> [กำลังทำ] <?php echo $rows['examination_title']; ?>
            </a>
          </li>
        <?php } ?>
        </ul>
        <?php } ?>
      </div>
    </div>
  </nav>
  