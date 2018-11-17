<!-- Main content -->
<div class="main-content">
    <!-- Top navbar -->
    <nav class="navbar bg-gradient-primary navbar-top navbar-expand-md navbar-dark" id="navbar-main">
      <div class="container-fluid">
        <!-- Brand -->
        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block"><?php echo $exam['examination_title'];?> <small>(<?php echo $exam['subject_title'];?>)</small></a>
    <?php require_once 'parts/usermenu.common.php'; ?>
    <?php
        $n = $_GET['n']-1;
        $stmt = $_DB->prepare("SELECT * FROM q_and_a WHERE qa_subject = :subject AND qa_exam = :exam ORDER BY qa_id ASC LIMIT :n, 1");
        $stmt->bindParam(':subject', $exam['examination_subject']);
        $stmt->bindParam(':exam', $exam['examination_id']);
        $stmt->bindParam(':n', $n, PDO::PARAM_INT);
        $stmt->execute();
        $exam_row = $stmt->fetch(PDO::FETCH_ASSOC);

        $answer_arr = explode(',', $exam_row['qa_choice_true']);
    ?>
    <!-- Page content -->
    <div class="container-fluid pb-5 pt-5 pt-md-8">
      <div class="row">
        <div class="col-xl-2">
            <?php
                $exami = 1;
                $stmt = $_DB->prepare("SELECT * FROM q_and_a WHERE qa_subject = :subject AND qa_exam = :exam ORDER BY qa_id ASC");
                $stmt->bindParam(':subject', $exam['examination_subject']);
                $stmt->bindParam(':exam', $exam['examination_id']);
                $stmt->execute();
                while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            ?>
                    <a style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" href="?examination_id=<?php echo $exam['examination_id']; ?>&n=<?php echo $exami;?>" class="btn btn-outline-primary mb-1 btn-block <?php echo ($exami==$n+1?'active':'');?>"><?php echo $exami;?>.<?php echo $row['qa_question'];?></a>
            <?php 
                    $exami++;
                }
                if($n+1 == $exami or $exami == 1){
            ?>
                    <a href="?examination_id=<?php echo $exam['examination_id']; ?>&n=<?php echo $exami;?>" class="btn btn-outline-primary mb-1 btn-block <?php echo ($exami==$n+1?'active':'');?>"><?php echo $exami;?></a>
            <?php
                }
            ?>
            <a href="?examination_id=<?php echo $exam['examination_id']; ?>&n=<?php echo $exami;?>" class="btn btn-outline-success mb-1 btn-block"><i class="fa fa-plus"></i></a>
            <label id="excel-btn" class="btn btn-outline-warning mb-1 btn-block">
                <span id="zip-text"><i class="fa fa-upload"></i> Import Excel</span> <input class="form-control" style="display: none;" type="file" name="excel_file" id="excel_file" accept="application/vnd.ms-excel,text/xls,text/xlsx,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
            </label>
        </div>
        <div class="col-xl-10" id="exam-content">
            <div class="card shadow mb-3 card-qa">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            <strong>#<?php echo (empty($_GET['n'])?'1':$_GET['n']);?></strong>
                        </div>
                        <div class="col-6 text-right">
                            <button class="btn btn-danger btn-sm" onclick="delete_exam(<?php echo (isset($exam_row['qa_id'])?$exam_row['qa_id']:'0'); ?>);">ลบ</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="javascript:void(0)" id="exam-form">
                    <input type="hidden" name="action" value="<?php echo (isset($exam_row['qa_id'])?'edit':'add'); ?>">
                    <input type="hidden" name="qa_id" value="<?php echo (isset($exam_row['qa_id'])?$exam_row['qa_id']:'0'); ?>">
                    <input type="hidden" name="qa_subject" value="<?php echo (isset($exam['examination_subject'])?$exam['examination_subject']:'0'); ?>">
                    <input type="hidden" name="qa_exam" value="<?php echo (isset($exam['examination_id'])?$exam['examination_id']:'0'); ?>">
                    <input type="hidden" name="qa_owner" value="<?php echo (isset($exam['examination_owner'])?$exam['examination_owner']:'0'); ?>">
                    <div class="form-group row">
                      <label class="col-sm-2 col-form-label" for="question">คำถาม ?</label>
                      <div class="col-sm-10">
                        <textarea class="form-control" id="question" name="question" required placeholder="คำถาม" rows="3"><?php echo (isset($exam_row['qa_question'])?$exam_row['qa_question']:''); ?></textarea>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-2 col-form-label mt-2">ตัวเลือก</label>
                      <div class="col-sm-10 row">
                        <div class="col-9"> </div>
                        <div class="col-3">
                            <small>ถูกต้อง</small>
                        </div>
                        <div class="col-9">
                            <input type="text" class="form-control mb-2" name="choice_1" required placeholder="ตัวเลือกที่ 1" value="<?php echo (isset($exam_row['qa_choice_1'])?$exam_row['qa_choice_1']:''); ?>">
                        </div>
                        <div class="col-3">
                            <div class="custom-control custom-checkbox mb-2">
                                <input class="custom-control-input" id="choice_1" type="checkbox" name="true[]" value="1" <?php echo (in_array(1, $answer_arr)?'checked':''); ?>>
                                <label class="custom-control-label" for="choice_1"></label>
                            </div>
                        </div>
                        <div class="col-9">
                            <input type="text" class="form-control mb-2" name="choice_2" required placeholder="ตัวเลือกที่ 2" value="<?php echo (isset($exam_row['qa_choice_2'])?$exam_row['qa_choice_2']:''); ?>">
                        </div>
                        <div class="col-3">
                            <div class="custom-control custom-checkbox mb-2">
                                <input class="custom-control-input" id="choice_2" type="checkbox" name="true[]" value="2" <?php echo (in_array(2, $answer_arr)?'checked':''); ?>>
                                <label class="custom-control-label" for="choice_2"></label>
                            </div>
                        </div>
                        <div class="col-9">
                            <input type="text" class="form-control mb-2" name="choice_3" required placeholder="ตัวเลือกที่ 3" value="<?php echo (isset($exam_row['qa_choice_3'])?$exam_row['qa_choice_3']:''); ?>">
                        </div>
                        <div class="col-3">
                            <div class="custom-control custom-checkbox mb-2">
                                <input class="custom-control-input" id="choice_3" type="checkbox" name="true[]" value="3" <?php echo (in_array(3, $answer_arr)?'checked':''); ?>>
                                <label class="custom-control-label" for="choice_3"></label>
                            </div>
                        </div>
                        <div class="col-9">
                            <input type="text" class="form-control mb-2" name="choice_4" required placeholder="ตัวเลือกที่ 4" value="<?php echo (isset($exam_row['qa_choice_4'])?$exam_row['qa_choice_4']:''); ?>">
                        </div>
                        <div class="col-3">
                            <div class="custom-control custom-checkbox mb-2">
                                <input class="custom-control-input" id="choice_4" type="checkbox" name="true[]" value="4" <?php echo (in_array(4, $answer_arr)?'checked':''); ?>>
                                <label class="custom-control-label" for="choice_4"></label>
                            </div>
                        </div>
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
        </div>
      </div>
    </div>
    <script type="text/javascript">
        $(function() {
                $('#excel_file').bind("change", function() {

                    var formData = new FormData();
                    formData.append("file", document.getElementById('excel_file').files[0]);
                    formData.append("subject", "<?php echo (isset($exam['examination_subject'])?$exam['examination_subject']:'0'); ?>");
                    formData.append("exam" ,"<?php echo (isset($exam['examination_id'])?$exam['examination_id']:'0'); ?>");
                    formData.append("qa_owner", "<?php echo (isset($exam['examination_owner'])?$exam['examination_owner']:'0'); ?>");

                    $.ajax({
                        url: weburl + "ajax/excel_import",
                        type: 'post',
                        data: formData,
                        dataType: 'json',
                        async: true,
                        processData: false,  // tell jQuery not to process the data
                        contentType: false,   // tell jQuery not to set contentType
                        success : function(response) {
                            if(response.state){
                              swal({
                                title: 'SUCCESS',
                                text: response.msg,
                                type: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Yes'
                              }).then((result) => {
                                if (result.value) {
                                  window.location.href = window.location.href;
                                }
                              })
                            }else{
                              swal(
                                'SORRY',
                                response.msg,
                                'error'
                              )
                            }
                        }
                    });
                });
        });
    </script>
