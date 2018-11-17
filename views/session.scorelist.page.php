<!-- Main content -->
<div class="main-content">
    <!-- Top navbar -->
    <nav class="navbar bg-gradient-primary navbar-top navbar-expand-md navbar-dark" id="navbar-main">
      <div class="container-fluid">
        <!-- Brand -->
        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="<?php url('examination/'); ?>">SCORE LIST <small>(รายชื่อ)</small></a>
        <?php require_once 'parts/usermenu.common.php'; ?>
    <!-- Page content -->
    <div class="container-fluid pb-8 pt-5 pt-md-8">
      <div class="row">
        <div class="col-xl-12">
              <div class="card shadow">
                <div class="card-header bg-transparent">
                  <div class="row align-items-center">
                    <div class="col">
                      <h6 class="text-uppercase text-muted ls-1 mb-1">รายชื่อ</h6>
                      <h2 class="mb-0">SCORELIST</h2>
                    </div>
                  </div>
                </div>
                <div class="card-body">
                <div class="table-responsive">
                  <table class="table align-items-center">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">Subject</th>
                            <th scope="col">Score</th>
                            <th scope="col">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                          $stm = $_DB->prepare("SELECT * FROM session_score JOIN subjects ON session_score.subject_id = subjects.subject_id JOIN users ON session_score.uid = users.uid WHERE session_score.session_id = :session ORDER BY users.stu_id ASC");
                          $stm->bindParam(":session", $_GET['session_id']);
                          $stm->execute();
                          while($rows = $stm->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                        <tr id="score-<?php echo $rows['score_id']; ?>">
                            <th scope="row">
                              <span class="mb-0 text-sm"><?php echo $rows['stu_id']; ?></span>
                            </th>
                            <td>
                              <span class="mb-0 text-sm"><?php echo $rows['full_name']; ?></span>
                            </td>
                            <td>
                              <span class="mb-0 text-sm"><?php echo $rows['subject_title']; ?></span>
                            </td>
                            <td>
                              <?php echo $rows['score']; ?>/<?php echo $rows['score_full']; ?>
                            </td>
                            <td>
                              <?php echo $rows['finish_tile']; ?>
                            </td>
                        </tr>
                        <?php }?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
        </div>
      </div>
    </div>