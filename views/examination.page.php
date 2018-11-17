<!-- Main content -->
<div class="main-content">
    <!-- Top navbar -->
    <nav class="navbar bg-gradient-primary navbar-top navbar-expand-md navbar-dark" id="navbar-main">
      <div class="container-fluid">
        <!-- Brand -->
        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="<?php url('examination/'); ?>">Examination <small>(ข้อสอบ)</small></a>
        <?php require_once 'parts/usermenu.common.php'; ?>
    <!-- Page content -->
    <div class="container-fluid pb-8 pt-5 pt-md-8">
      <div class="row">
        <div class="col-xl-12">
          <?php if(isset($_GET['add'])){ ?>
              <div class="card shadow">
                <div class="card-header bg-transparent">
                  <div class="row align-items-center">
                    <div class="col">
                      <h6 class="text-uppercase text-muted ls-1 mb-1">เพิ่มข้อสอบ</h6>
                      <h2 class="mb-0">Add Examination</h2>
                    </div>
                  </div>
                </div>
                <div class="card-body">
                  <form action="javascript:void(0)" id="add-examination-form">
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="uid" value="<?php echo $user_row['uid']; ?>">
                    <div class="form-group row">
                      <label class="col-sm-2 col-form-label" for="examination_title">ชื่อข้อสอบ</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="examination_title" name="examination_title" required>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-2 col-form-label" for="examination_subject">รายวิชา</label>
                      <div class="col-sm-10">
                        <select class="form-control" id="examination_subject" name="examination_subject" required>
                            <?php
                                $stm = $_DB->prepare("SELECT * FROM subjects WHERE subject_owner = :uid");
                                $stm->bindParam(':uid', $user_row['uid']);
                                $stm->execute();
                                while($rows = $stm->fetch(PDO::FETCH_ASSOC)){
                            ?>
                                <option value="<?php echo $rows['subject_id']; ?>"><?php echo $rows['subject_title']; ?></option>
                            <?php
                                }
                            ?>
                        </select>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-2 col-form-label" for="examination_detail">รายละเอียดข้อสอบ <small class="text-muted">(Option)</small></label>
                      <div class="col-sm-10">
                        <textarea class="form-control" id="examination_detail" name="examination_detail" rows="5"></textarea>
                      </div>
                    </div>
                    <div class="form-group row">
                      <div class="col-sm-2"></div>
                      <div class="col-sm-10">
                        <button type="submit" class="btn btn-success">บันทึก</button>
                        <a href="<?php url('examination/'); ?>" class="btn btn-danger">ยกเลิก</a>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
          <?php
                }elseif(isset($_GET['edit']) and isset($_GET['examination_id'])){
                  $stm = $_DB->prepare("SELECT * FROM examinations WHERE examination_id = :examination_id");
							    $stm->bindParam(':examination_id', $_GET['examination_id'], PDO::PARAM_INT);
							    $stm->execute();
							    $row = $stm->fetch(PDO::FETCH_ASSOC);
          ?>
              <div class="card shadow">
                <div class="card-header bg-transparent">
                  <div class="row align-items-center">
                    <div class="col">
                      <h6 class="text-uppercase text-muted ls-1 mb-1">แก้ไขข้อสอบ</h6>
                      <h2 class="mb-0"><?php echo $row['examination_title']; ?></h2>
                    </div>
                  </div>
                </div>
                <div class="card-body">
                  <form action="javascript:void(0)" id="edit-examination-form">
                  <input type="hidden" name="action" value="edit">
                  <input type="hidden" name="examination_id" value="<?php echo $row['examination_id']; ?>">
                  <input type="hidden" name="examination_owner" value="<?php echo $row['examination_owner']; ?>">
                    <div class="form-group row">
                      <label class="col-sm-2 col-form-label" for="examination_title">ชื่อข้อสอบ</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="examination_title" name="examination_title" value="<?php echo $row['examination_title']; ?>">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-2 col-form-label" for="examination_subject">รายวิชา</label>
                      <div class="col-sm-10">
                        <select class="form-control" id="examination_subject" name="examination_subject" required>
                            <?php
                                $stm = $_DB->prepare("SELECT * FROM subjects WHERE subject_owner = :uid");
                                $stm->bindParam(':uid', $user_row['uid']);
                                $stm->execute();
                                while($rows = $stm->fetch(PDO::FETCH_ASSOC)){
                            ?>
                                <option value="<?php echo $rows['subject_id']; ?>" <?php echo ($rows['subject_id'] == $row['examination_subject'])?'selected':''; ?>><?php echo $rows['subject_title']; ?></option>
                            <?php
                                }
                            ?>
                        </select>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-2 col-form-label" for="examination_detail">รายละเอียดรายวิชา <small class="text-muted">(Option)</small></label>
                      <div class="col-sm-10">
                        <textarea class="form-control" id="examination_detail" name="examination_detail" rows="5"><?php echo $row['examination_detail']; ?></textarea>
                      </div>
                    </div>
                    <div class="form-group row">
                      <div class="col-sm-2"></div>
                      <div class="col-sm-10">
                        <button type="submit" class="btn btn-success">บันทึก</button>
                        <a href="<?php url('examination/'); ?>" class="btn btn-danger">ยกเลิก</a>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
          <?php }else{ ?>
              <a class="btn btn-success mb-3" href="?add"><span class="btn-inner--icon"><i class="ni ni-fat-add"></i></span> เพิ่มข้อสอบ</a>
              <div class="card shadow">
                <div class="card-header bg-transparent">
                  <div class="row align-items-center">
                    <div class="col">
                      <h6 class="text-uppercase text-muted ls-1 mb-1">รายชื่อข้อสอบ</h6>
                      <h2 class="mb-0">Examination</h2>
                    </div>
                  </div>
                </div>
                <div class="card-body">
                <div class="table-responsive">
                  <table class="table align-items-center">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">Title</th>
                            <th scope="col">Subject</th>
                            <th scope="col">Amount of Test</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                          $stm = $_DB->query('SELECT * FROM examinations JOIN subjects ON examinations.examination_subject = subjects.subject_id ORDER BY examination_createtime DESC');
                          while($rows = $stm->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                        <tr id="examination-<?php echo $rows['examination_id']; ?>">
                            <th scope="row">
                              <span class="mb-0 text-sm"><?php echo $rows['examination_title']; ?></span>
                            </th>
                            <td>
                            <span class="mb-0 text-sm"><?php echo $rows['subject_title']; ?></span>
                            </td>
                            <?php
                              $stmt = $_DB->prepare("SELECT COUNT(qa_id) AS qac FROM q_and_a WHERE qa_subject = :subject AND qa_exam = :exam");
                              $stmt->bindParam(':subject', $rows['subject_id']);
                              $stmt->bindParam(':exam', $rows['examination_id']);
                              $stmt->execute();
                              $row = $stmt->fetch(PDO::FETCH_ASSOC);
                            ?>
                            <td>
                              <?php echo $row['qac']; ?>
                            </td>
                            <td class="text-right">
                                <a href="qa/?examination_id=<?php echo $rows['examination_id']; ?>&n=1" class="btn btn-warning btn-sm">Edit Question</a>
                                <a href="?edit&examination_id=<?php echo $rows['examination_id']; ?>" class="btn btn-info btn-sm">Edit</a> 
                                <button onclick="examination_delete(<?php echo $rows['examination_id']; ?>)" class="btn btn-danger btn-sm">Delete</button>
                            </td>
                        </tr>
                        <?php }?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          <?php } ?>
        </div>
      </div>
    </div>