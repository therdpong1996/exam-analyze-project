<!-- Main content -->
<div class="main-content">
    <!-- Top navbar -->
    <nav class="navbar bg-gradient-primary navbar-top navbar-expand-md navbar-dark" id="navbar-main">
      <div class="container-fluid">
        <!-- Brand -->
        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block">Midterm <small>(คะแนน)</small></a>
        <?php require_once 'parts/usermenu.common.php'; ?>

    <!-- Page content -->
    <div class="container-fluid pb-5 pt-5 pt-md-8">
      <div class="row">
            <div class="col-xl-12" id="exam-content">
            <div class="card shadow mb-3">
                <div class="card-body text-center pt-5 pb-5">
                    <i class="fas fa-check-circle fa-8x text-success mb-3"></i>
                    <div>
                        <small><strong>คะแนนที่ได้</strong></small>
                        <h1>
                            <?php if($percen >= 50){ ?>
                            <i class="fas fa-laugh-beam text-success"></i> 
                            <?php }else{ ?>
                            <i class="fas fa-sad-cry text-danger"></i> 
                            <?php } ?>
                            <?php echo $score; ?><span class="text-muted">/<?php echo $full; ?></span>
                        </h1>
                        <?php if($percen >= 50){ ?>
                            <p>ยินดีด้วย! คะแนนคุณผ่านเกณฑ์</p>
                        <?php }else{ ?>
                            <p>ไม่เป็นไร! รอบหน้าเอาใหม่</p>
                        <?php } ?>

                        <a href="<?php url('stu-examination/'); ?>" class="btn btn-primary mt-5"><i class="fa fa-arrow-left"></i> กลับ</a>
                    </div>
                </div>
            </div>
            </div>
      </div>
    </div>
