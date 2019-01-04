<!-- Main content -->
<div class="main-content">
    <!-- Top navbar -->
    <nav class="navbar bg-gradient-primary navbar-top navbar-expand-md navbar-dark" id="navbar-main">
      <div class="container-fluid">
        <!-- Brand -->
        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="<?php url('session/'); ?>">Session <small>(เซสชั่น)</small></a>
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
                      <h6 class="text-uppercase text-muted ls-1 mb-1">เพิ่มเซสชั่น</h6>
                      <h2 class="mb-0">Add Session</h2>
                    </div>
                  </div>
                </div>
                <div class="card-body">
                  <form action="javascript:void(0)" id="add-session-form">
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="adaptimport" id="adaptimport" value="0">
                    <div class="form-group row">
                      <label class="col-sm-2 col-form-label" for="session_exam">ข้อสอบ</label>
                      <div class="col-sm-10">
                        <select class="form-control" id="session_exam" name="session_exam" required>
                            <option value="0" disabled selected>เลือกชุดข้อสอบ</option>
                            <?php
                                $stm1 = $_DB->prepare("SELECT subject_id FROM subject_owner WHERE uid = :uid");
                                $stm1->bindParam(":uid", $user_row['uid']);
                                $stm1->execute();
                                while ($orows = $stm1->fetch(PDO::FETCH_ASSOC)) {

                                  $stm = $_DB->prepare('SELECT * FROM examinations WHERE examination_subject = :id');
                                  $stm->bindParam(':id', $orows['subject_id']);
                                  $stm->execute();
                                  while ($rows = $stm->fetch(PDO::FETCH_ASSOC)) {
                            ?>
                                    <option value="<?php echo $rows['examination_id']; ?>"><?php echo $rows['examination_title']; ?></option>
                            <?php } } ?>
                        </select>
                        <div id="import-content" class="import-content mt-2"></div>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-2 col-form-label" for="session_adap">Adaptive</label>
                      <div class="col-sm-10">
                        <label class="custom-toggle">
                          <input id="session_adap" type="checkbox" name="session_adap" value="1" <?php echo $row['session_adap'] != 0 ? 'checked' : ''; ?> <?php echo $row['session_train'] == 0 ? 'disabled' : ''; ?>>
                          <span class="custom-toggle-slider rounded-circle"></span>
                        </label>
                        <p class="text-muted">ใช้งานได้เมื่อมีการ train data เรียบร้อยแล้ว</p>
                      </div>
                    </div>
                    <div class="form-group row" id="adaptive-number" <?php echo ($row['session_adap'] == 0)?'style="display:none;"':''; ?>>
                      <label class="col-sm-2 col-form-label" for="session_adap_number">จำนวนข้อ</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="session_adap_number" name="session_adap_number" value="">
                      </div>
                    </div>
                    <script>
                      $("input#session_adap").change(function() {
                          if(this.checked) {
                            $('#adaptive-number').fadeIn(200)
                          }else{
                            $('#adaptive-number').fadeOut(200)
                          }
                      });
                    </script>
                    <div class="form-group row">
                      <label class="col-sm-2 col-form-label" for="session_start">ระยะเวลา</label>
                      <div class="col-sm-10">
                        <div class="input-daterange datepicker row align-items-center">
                          <div class="col">
                              <div class="form-group">
                                  <div class="input-group">
                                      <div class="input-group-prepend">
                                          <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                      </div>
                                      <input class="form-control" placeholder="Start date" name="session_start" type="text" value="<?php __(date('Y/m/d')); ?>">
                                  </div>
                              </div>
                          </div>
                          <div class="col-1 text-center" style="margin-bottom: -45px; font-size: 24px;">ถึง</div>
                          <div class="col">
                              <div class="form-group">
                                  <div class="input-group">
                                      <div class="input-group-prepend">
                                          <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                      </div>
                                      <input class="form-control" placeholder="End date" name="session_end" type="text" value="<?php __(date('Y/m/d')); ?>">
                                  </div>
                              </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col">
                            <input type="time" class="form-control" name="session_start_time">
                          </div>
                          <div class="col-1"></div>
                          <div class="col">
                            <input type="time" class="form-control" name="session_end_time">
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-2 col-form-label" for="session_timeleft">เวลาในการทำ (นาที)</label>
                      <div class="col-sm-10">
                        <input type="number" class="form-control" id="session_timeleft" name="session_timeleft" required>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-2 col-form-label" for="session_password">รหัสผ่าน <small class="text-muted">(Option)</small></label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="session_password" name="session_password">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-2" for="session_solve">อนุญาตให้ดูข้อที่ถูกต้อง</label>
                      <div class="col-sm-10">
                        <div class="custom-control custom-checkbox mb-3">
                          <input class="custom-control-input" id="session_solve" value="1" name="session_solve" type="checkbox">
                          <label class="custom-control-label" for="session_solve">เปิด</label>
                        </div>
                      </div>
                    </div>
                    <div class="form-group row">
                      <div class="col-sm-2"></div>
                      <div class="col-sm-10">
                        <button type="submit" id="session-add" class="session-btn btn btn-success">บันทึก</button>
                        <a href="<?php url('session/'); ?>" class="btn btn-danger">ยกเลิก</a>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            <?php }elseif (isset($_GET['edit']) and isset($_GET['session_id'])) {

              $stm3 = $_DB->prepare('SELECT * FROM sessions WHERE session_id = :session_id LIMIT 1');
              $stm3->bindParam(':session_id', $_GET['session_id'], PDO::PARAM_INT);
              $stm3->execute();
              $row3 = $stm3->fetch(PDO::FETCH_ASSOC);

              $stmc1 = $_DB->prepare("SELECT examination_subject FROM examinations WHERE examination_id = :id");
              $stmc1->bindParam(":id", $row3['session_exam']);
              $stmc1->execute();
              $rowc1 = $stmc1->fetch(PDO::FETCH_ASSOC);

              $stmc2 = $_DB->prepare("SELECT uid FROM subject_owner WHERE subject_id = :id AND uid = :uid");
              $stmc2->bindParam(":id", $rowc1['examination_subject']);
              $stmc2->bindParam(":uid", $_SESSION['uid']);
              $stmc2->execute();
              $rowc2 = $stmc2->fetch(PDO::FETCH_ASSOC);

              if (empty($rowc2['uid'])) {
                  deniedpage();
              }

              $stm = $_DB->prepare('SELECT * FROM sessions JOIN examinations ON sessions.session_exam = examinations.examination_id WHERE session_id = :session_id');
              $stm->bindParam(':session_id', $_GET['session_id'], PDO::PARAM_INT);
              $stm->execute();
              $row = $stm->fetch(PDO::FETCH_ASSOC);
            ?>
              <div class="card shadow">
                <div class="card-header bg-transparent">
                  <div class="row align-items-center">
                    <div class="col">
                      <h6 class="text-uppercase text-muted ls-1 mb-1">แก้ไขเซสชั่น</h6>
                      <h2 class="mb-0"><?php echo $row['examination_title']; ?></h2>
                    </div>
                  </div>
                </div>
                <div class="card-body">
                  <form action="javascript:void(0)" id="edit-session-form">
                  <input type="hidden" name="action" value="edit">
                  <input type="hidden" name="session_id" value="<?php echo $row['session_id']; ?>">
                  <input type="hidden" name="adaptimport" id="adaptimport" value="<?php echo $row['session_adap']; ?>">
                    
                    <div class="form-group row">
                      <label class="col-sm-2 col-form-label" for="session_exam">ข้อสอบ</label>
                      <div class="col-sm-10">
                        <select class="form-control" id="session_exam" name="session_exam" required>
                            <?php
                                $stm1 = $_DB->prepare("SELECT subject_id FROM subject_owner WHERE uid = :uid");
                                $stm1->bindParam(":uid", $user_row['uid']);
                                $stm1->execute();
                                while ($orows = $stm1->fetch(PDO::FETCH_ASSOC)) {
                                    $stm = $_DB->prepare('SELECT * FROM examinations WHERE examination_subject = :id');
                                    $stm->bindParam(':id', $orows['subject_id']);
                                    $stm->execute();
                                    while ($rows = $stm->fetch(PDO::FETCH_ASSOC)) {
                            ?>
                                  <option value="<?php echo $rows['examination_id']; ?>" <?php echo ($rows['examination_id'] == $row['session_exam']) ? 'selected' : ''; ?>><?php echo $rows['examination_title']; ?></option>
                            <?php } } ?>
                        </select>
                        <?php if($row['session_adap'] != 0){
                              $stm5 = $_DB->prepare("SELECT adap_id,std_number,time_update,users.full_name FROM adaptive_table JOIN users ON adaptive_table.uid = users.uid WHERE adaptive_table.adap_id = :adap_id");
                              $stm5->bindParam(":adap_id", $row['session_adap']);
                              $stm5->execute();
                              $adapnow = $stm5->fetch(PDO::FETCH_ASSOC)
                        ?>
                        <small class="text-muted">Import ข้อมูลการวิเคราะห์ของ <?php __($adapnow['full_name']); ?> มีผู้ทดสอบจำนวน <?php __($adapnow['std_number']); ?> คน อัพเดทล่าสุดเมื่อ <?php __($adapnow['time_update']); ?></small>
                        <?php } ?>
                        <div id="import-content" class="import-content mt-2"></div>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-2 col-form-label" for="session_adap">Adaptive</label>
                      <div class="col-sm-10">
                        <label class="custom-toggle">
                          <input id="session_adap" type="checkbox" name="session_adap" value="1" <?php echo $row['session_adap_active'] == 1 ? 'checked' : ''; ?> <?php echo ($row['session_train'] == 0 and $row['session_adap'] == 0) ? 'disabled' : ''; ?>>
                          <span class="custom-toggle-slider rounded-circle"></span>
                        </label>
                        <p class="text-muted">ใช้งานได้เมื่อมีการ train data เรียบร้อยแล้ว</p>
                      </div>
                    </div>
                    <div class="form-group row" id="adaptive-number" <?php echo ($row['session_adap_active'] == 0)?'style="display:none;"':''; ?>>
                      <label class="col-sm-2 col-form-label" for="session_adap_number">จำนวนข้อ</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="session_adap_number" name="session_adap_number" value="<?php echo $row['session_adap_number']; ?>">
                      </div>
                    </div>
                    <script>
                      $("input#session_adap").change(function() {
                          if(this.checked) {
                            $('#adaptive-number').fadeIn(200)
                          }else{
                            $('#adaptive-number').fadeOut(200)
                          }
                      });
                    </script>
                    <div class="form-group row">
                      <label class="col-sm-2 col-form-label" for="session_start">ระยะเวลา</label>
                      <div class="col-sm-10">
                        <div class="input-daterange datepicker row align-items-center">
                          <div class="col">
                              <div class="form-group">
                                  <div class="input-group">
                                      <div class="input-group-prepend">
                                          <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                      </div>
                                      <input class="form-control" placeholder="Start date" name="session_start" type="text" value="<?php echo date('Y/m/d', strtotime($row['session_start'])); ?>">
                                  </div>
                              </div>
                          </div>
                          <div class="col-1 text-center" style="margin-bottom: -45px; font-size: 24px;">ถึง</div>
                          <div class="col">
                              <div class="form-group">
                                  <div class="input-group">
                                      <div class="input-group-prepend">
                                          <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                      </div>
                                      <input class="form-control" placeholder="End date" name="session_end" type="text" value="<?php echo date('Y/m/d', strtotime($row['session_end'])); ?>">
                                  </div>
                              </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col">
                            <input type="time" class="form-control" name="session_start_time" value="<?php echo date('H:i', strtotime($row['session_start'])); ?>">
                          </div>
                          <div class="col-1"></div>
                          <div class="col">
                            <input type="time" class="form-control" name="session_end_time" value="<?php echo date('H:i', strtotime($row['session_end'])); ?>">
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-2 col-form-label" for="session_timeleft">เวลาในการทำ (นาที)</label>
                      <div class="col-sm-10">
                        <input type="number" class="form-control" id="session_timeleft" name="session_timeleft" required value="<?php echo $row['session_timeleft']; ?>">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-2 col-form-label" for="session_password">รหัสผ่าน <small class="text-muted">(Option)</small></label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="session_password" name="session_password" value="<?php echo $row['session_password']; ?>">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-2" for="session_password">อนุญาตให้ดูข้อที่ถูกต้อง</label>
                      <div class="col-sm-10">
                        <div class="custom-control custom-checkbox mb-3">
                          <input class="custom-control-input" id="session_solve" value="1" name="session_solve" type="checkbox" <?php echo $row['session_solve'] == 1 ? 'checked' : ''; ?>>
                          <label class="custom-control-label" for="session_solve">เปิด</label>
                        </div>
                      </div>
                    </div>
                    <div class="form-group row">
                      <div class="col-sm-2"></div>
                      <div class="col-sm-10">
                        <button type="submit" id="session-save" class="session-btn btn btn-success">บันทึก</button>
                        <a href="<?php url('subject/'); ?>" class="btn btn-danger">ยกเลิก</a>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
          <?php } else { ?>
              <a class="btn btn-success mb-3" href="?add"><span class="btn-inner--icon"><i class="ni ni-fat-add"></i></span> เพิ่มเซสชั่น</a>
              <div class="card shadow">
                <div class="card-header bg-transparent">
                  <div class="row align-items-center">
                    <div class="col">
                      <h6 class="text-uppercase text-muted ls-1 mb-1">เซสชั่น</h6>
                      <h2 class="mb-0">Session</h2>
                    </div>
                  </div>
                </div>
                <div class="card-body">
                <div class="table-responsive">
                  <table class="table align-items-center">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">Examination</th>
                            <th scope="col">Start</th>
                            <th scope="col">End</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                          $stm1 = $_DB->prepare("SELECT subject_id FROM subject_owner WHERE uid = :uid");
                          $stm1->bindParam(":uid", $user_row['uid']);
                          $stm1->execute();
                          while ($orows = $stm1->fetch(PDO::FETCH_ASSOC)) {
                            $stm = $_DB->prepare('SELECT * FROM sessions JOIN examinations ON sessions.session_exam = examinations.examination_id JOIN subjects ON examinations.examination_subject = subjects.subject_id WHERE subjects.subject_id = :id ORDER BY sessions.session_start ASC');
                            $stm->bindParam(":id", $orows['subject_id']);
                            $stm->execute();
                            while ($rows = $stm->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                        <tr id="session-<?php echo $rows['session_id']; ?>">
                            <th scope="row">
                              <span class="mb-0 text-sm"><?php echo $rows['session_adap_active'] == 1 ? '<span class="badge badge-primary">Adaptive</span>' : ''; ?> <?php echo $rows['examination_title']; ?> <?php echo $rows['session_password'] != null ? '<i class="fas fa-key"></i>' : ''; ?> <small>[<?php echo $rows['subject_title']; ?>]</small></span>
                            </th>
                            <td>
                              <?php echo date('l d, M Y', strtotime($rows['session_start'])); ?>
                            </td>
                            <td>
                              <?php echo date('l d, M Y', strtotime($rows['session_end'])); ?>
                            </td>
                            <td class="text-right">
                                <a href="analyze/?session_id=<?php echo $rows['session_id']; ?>&overview" class="btn btn-success btn-sm">Analyze</a>
                                <a href="?edit&session_id=<?php echo $rows['session_id']; ?>" class="btn btn-info btn-sm">Edit</a> 
                                <button id="delete-btn-<?php echo $rows['session_id']; ?>" onclick="session_delete(<?php echo $rows['session_id']; ?>)" class="btn btn-danger btn-sm">Delete</button>
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