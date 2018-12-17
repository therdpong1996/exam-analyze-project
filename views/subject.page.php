<!-- Main content -->
<div class="main-content">
    <!-- Top navbar -->
    <nav class="navbar bg-gradient-primary navbar-top navbar-expand-md navbar-dark" id="navbar-main">
      <div class="container-fluid">
        <!-- Brand -->
        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="<?php url('subject/'); ?>">Subject <small>(รายวิชา)</small></a>
        <?php require_once 'parts/usermenu.common.php'; ?>
    <!-- Page content -->
    <div class="container-fluid pb-8 pt-5 pt-md-8">
      <div class="row">
        <div class="col-xl-12">
          <?php if (isset($_GET['add'])) { ?>
              <div class="card shadow">
                <div class="card-header bg-transparent">
                  <div class="row align-items-center">
                    <div class="col">
                      <h6 class="text-uppercase text-muted ls-1 mb-1">เพิ่มรายชื่อวิชา</h6>
                      <h2 class="mb-0">Add Subject</h2>
                    </div>
                  </div>
                </div>
                <div class="card-body">
                  <form action="javascript:void(0)" id="add-subject-form">
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="uid" value="<?php echo $user_row['uid']; ?>">
                    <div class="form-group row">
                      <label class="col-sm-2 col-form-label" for="subject_title">ชื่อรายวิชา</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="subject_title" name="subject_title" required>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-2 col-form-label" for="subject_detail">รายละเอียดรายวิชา <small class="text-muted">(Option)</small></label>
                      <div class="col-sm-10">
                        <textarea class="form-control" id="subject_detail" name="subject_detail" rows="5"></textarea>
                      </div>
                    </div>
                    <div class="form-group row">
                      <div class="col-sm-2"></div>
                      <div class="col-sm-10">
                        <button type="submit" id="subject-add" class="btn btn-success">บันทึก</button>
                        <a href="<?php url('subject/'); ?>" class="btn btn-danger">ยกเลิก</a>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
          <?php } elseif (isset($_GET['edit']) and isset($_GET['subject_id'])) {
          $stm = $_DB->prepare('SELECT * FROM subjects WHERE subject_id = :subject_id');
          $stm->bindParam(':subject_id', $_GET['subject_id'], PDO::PARAM_INT);
          $stm->execute();
          $row = $stm->fetch(PDO::FETCH_ASSOC);
          ?>
              <div class="card shadow">
                <div class="card-header bg-transparent">
                  <div class="row align-items-center">
                    <div class="col">
                      <h6 class="text-uppercase text-muted ls-1 mb-1">แก้ไขรายชื่อวิชา</h6>
                      <h2 class="mb-0"><?php echo $row['subject_title']; ?></h2>
                    </div>
                  </div>
                </div>
                <div class="card-body">
                  <form action="javascript:void(0)" id="edit-subject-form">
                  <input type="hidden" name="action" value="edit">
                  <input type="hidden" name="subject_id" value="<?php echo $row['subject_id']; ?>">
                    <div class="form-group row">
                      <label class="col-sm-2 col-form-label" for="subject_title">ชื่อรายวิชา</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="subject_title" name="subject_title" value="<?php echo $row['subject_title']; ?>">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-2 col-form-label" for="subject_detail">รายละเอียดรายวิชา <small class="text-muted">(Option)</small></label>
                      <div class="col-sm-10">
                        <textarea class="form-control" id="subject_detail" name="subject_detail" rows="5"><?php echo $row['subject_detail']; ?></textarea>
                      </div>
                    </div>
                    <div class="form-group row">
                      <div class="col-sm-2"></div>
                      <div class="col-sm-10">
                        <button type="submit" id="subject-save" class="btn btn-success">บันทึก</button>
                        <a href="<?php url('subject/'); ?>" class="btn btn-danger">ยกเลิก</a>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
          <?php } else { ?>
              <a class="btn btn-success mb-3" href="?add"><span class="btn-inner--icon"><i class="ni ni-fat-add"></i></span> เพิ่มรายวิชา</a>
              <div class="card shadow">
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
                            <th scope="col">Amount of Examination</th>
                            <th scope="col">Invite Code</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                          $stm1 = $_DB->prepare("SELECT subject_id FROM subject_owner WHERE uid = :uid");
                          $stm1->bindParam(":uid", $user_row['uid']);
                          $stm1->execute();
                          while ($orows = $stm1->fetch(PDO::FETCH_ASSOC)) {
                            $stm = $_DB->prepare('SELECT * FROM subjects WHERE subject_id = :id ORDER BY subject_createtime DESC');
                            $stm->bindParam(":id", $orows['subject_id']);
                            $stm->execute();
                            while ($rows = $stm->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                        <tr id="subject-<?php echo $rows['subject_id']; ?>">
                            <th scope="row">
                              <span class="mb-0 text-sm"><?php echo $rows['subject_title']; ?></span>
                            </th>
                            <?php
                              $stmt = $_DB->prepare('SELECT COUNT(examination_id) AS exam FROM examinations WHERE examination_subject = :subject');
                              $stmt->bindParam(':subject', $rows['subject_id']);
                              $stmt->execute();
                              $row = $stmt->fetch(PDO::FETCH_ASSOC);
                            ?>
                            <td>
                              <?php echo $row['exam']; ?>
                            </td>
                            <td>
                              <?php echo $rows['subject_invite_code']; ?>
                            </td>
                            <td class="text-right">
                                <a href="?edit&subject_id=<?php echo $rows['subject_id']; ?>" class="btn btn-info btn-sm">Edit</a> 
                                <button id="delete-btn-<?php echo $rows['subject_id']; ?>" onclick="subject_delete(<?php echo $rows['subject_id']; ?>)" class="btn btn-danger btn-sm">Delete</button>
                            </td>
                        </tr>
                        <?php } } ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          <?php } ?>
        </div>
      </div>
    </div>