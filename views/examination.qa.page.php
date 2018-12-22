<!-- Main content -->
<div class="main-content">
    <!-- Top navbar -->
    <nav class="navbar bg-gradient-primary navbar-top navbar-expand-md navbar-dark" id="navbar-main">
        <div class="container-fluid">
        <!-- Brand -->
        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block"><?php echo $exam['examination_title']; ?> <small>(<?php echo $exam['subject_title']; ?>)</small></a>
    <?php require_once 'parts/usermenu.common.php'; ?>
    <?php
        if (isset($_GET['n'])) {
            $n = $_GET['n'];
        } else {
            $stm = $_DB->prepare('SELECT qa_id FROM q_and_a WHERE qa_subject = :subject AND qa_exam = :exam ORDER BY qa_id ASC LIMIT 1');
            $stm->bindParam(':subject', $exam['examination_subject']);
            $stm->bindParam(':exam', $exam['examination_id']);
            $stm->execute();
            $exam_n = $stm->fetch(PDO::FETCH_ASSOC);
            $n = $exam_n['qa_id'];
        }

        $stmt = $_DB->prepare('SELECT * FROM q_and_a WHERE qa_subject = :subject AND qa_exam = :exam AND qa_id = :n LIMIT 1');
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
            <label id="excel-btn" class="btn btn-outline-warning mb-2 text-left btn-block">
                <span id="zip-text"><i class="fa fa-upload"></i> Import Excel</span> <input class="form-control" style="display: none;" type="file" name="excel_file" id="excel_file" accept="application/vnd.ms-excel,text/xls,text/xlsx,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
            </label>
            <a href="?examination_id=<?php echo $exam['examination_id']; ?>&n=new" class="new-exam btn text-left btn-outline-success mb-2 btn-block"><i class="fa fa-plus"></i> เพิ่มข้อใหม่</a>
            <div class="list-exam-sortable exam-scollbar pr-2">
            <?php

                $stmt = $_DB->prepare('SELECT * FROM q_and_a WHERE qa_subject = :subject AND qa_exam = :exam AND qa_delete = 0 ORDER BY qa_order ASC');
                $stmt->bindParam(':subject', $exam['examination_subject']);
                $stmt->bindParam(':exam', $exam['examination_id']);
                $stmt->execute();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <a style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; cursor: move;" href="?examination_id=<?php echo $exam['examination_id']; ?>&n=<?php echo $row['qa_id']; ?>" class="btn text-left list-exam-item btn-outline-primary mb-2 btn-block <?php echo $row['qa_id'] == $n ? 'active' : ''; ?>" exam-id="<?php echo $row['qa_id']; ?>"><span id="exam-order"><?php echo $row['qa_order']; ?></span>.<?php echo strip_tags($row['qa_question']); ?> </a>
            <?php
                }
                if ($n == 'new') {
                    ?>
                <a style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" href="" class="btn new-exam text-left btn-outline-primary mb-1 btn-block active">New Question</a>
            <?php
                }
            ?>
            </div>
        </div>
        <script type="text/javascript">
            var examination_id = '<?php echo $exam['examination_id']; ?>';
            var Examorder = [];
            $('.list-exam-sortable').sortable({
                placeholderClass: 'list-exam-item',
                items: ':not(.new-exam)'
            });
            $('.list-exam-sortable').sortable().bind('sortupdate', function() {
                $('#overlay-loading').fadeIn(200);
                Examorder = [];
                $('.list-exam-item').each(function() {
                    Examorder.push($(this).attr('exam-id'))
                });
                $.ajax({
                    url: weburl + 'ajax/order_exam',
                    type: 'POST',
                    dataType: 'json',
                    data: {qa_id: Examorder}
                })
                .always(function(response) {
                    window.location.href = window.location.href;
                });
            });
        </script>
        <div class="col-xl-10" id="exam-content">
            <div class="card shadow mb-3 card-qa">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            <strong>Edit Question</strong>
                        </div>
                        <div class="col-6 text-right">
                            <button id="delete-btn" class="btn btn-danger btn-sm" onclick="delete_exam(<?php echo isset($exam_row['qa_id']) ? $exam_row['qa_id'] : '0'; ?>);">ลบ</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="javascript:void(0)" id="exam-form">
                    <input type="hidden" name="action" value="<?php echo isset($exam_row['qa_id']) ? 'edit' : 'add'; ?>">
                    <input type="hidden" name="qa_id" value="<?php echo isset($exam_row['qa_id']) ? $exam_row['qa_id'] : '0'; ?>">
                    <input type="hidden" name="qa_subject" value="<?php echo isset($exam['examination_subject']) ? $exam['examination_subject'] : '0'; ?>">
                    <input type="hidden" name="qa_exam" value="<?php echo isset($exam['examination_id']) ? $exam['examination_id'] : '0'; ?>">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label" for="question">คำถาม ?</label>
                        <div class="col-sm-10">
                            <textarea class="form-control summernote" id="question" name="question" required placeholder="คำถาม" rows="3"><?php echo isset($exam_row['qa_question']) ? $exam_row['qa_question'] : ''; ?></textarea>
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
                            <input type="text" class="form-control mb-2" name="choice_1" required placeholder="ตัวเลือกที่ 1" value="<?php echo isset($exam_row['qa_choice_1']) ? $exam_row['qa_choice_1'] : ''; ?>">
                        </div>
                        <div class="col-3">
                            <div class="custom-control custom-checkbox mb-2">
                                <input class="custom-control-input" id="choice_1" type="checkbox" name="true[]" value="1" <?php echo in_array(1, $answer_arr) ? 'checked' : ''; ?>>
                                <label class="custom-control-label" for="choice_1"></label>
                            </div>
                        </div>
                        <div class="col-9">
                            <input type="text" class="form-control mb-2" name="choice_2" required placeholder="ตัวเลือกที่ 2" value="<?php echo isset($exam_row['qa_choice_2']) ? $exam_row['qa_choice_2'] : ''; ?>">
                        </div>
                        <div class="col-3">
                            <div class="custom-control custom-checkbox mb-2">
                                <input class="custom-control-input" id="choice_2" type="checkbox" name="true[]" value="2" <?php echo in_array(2, $answer_arr) ? 'checked' : ''; ?>>
                                <label class="custom-control-label" for="choice_2"></label>
                            </div>
                        </div>
                        <div class="col-9">
                            <input type="text" class="form-control mb-2" name="choice_3" required placeholder="ตัวเลือกที่ 3" value="<?php echo isset($exam_row['qa_choice_3']) ? $exam_row['qa_choice_3'] : ''; ?>">
                        </div>
                        <div class="col-3">
                            <div class="custom-control custom-checkbox mb-2">
                                <input class="custom-control-input" id="choice_3" type="checkbox" name="true[]" value="3" <?php echo in_array(3, $answer_arr) ? 'checked' : ''; ?>>
                                <label class="custom-control-label" for="choice_3"></label>
                            </div>
                        </div>
                        <div class="col-9">
                            <input type="text" class="form-control mb-2" name="choice_4" required placeholder="ตัวเลือกที่ 4" value="<?php echo isset($exam_row['qa_choice_4']) ? $exam_row['qa_choice_4'] : ''; ?>">
                        </div>
                        <div class="col-3">
                            <div class="custom-control custom-checkbox mb-2">
                                <input class="custom-control-input" id="choice_4" type="checkbox" name="true[]" value="4" <?php echo in_array(4, $answer_arr) ? 'checked' : ''; ?>>
                                <label class="custom-control-label" for="choice_4"></label>
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-10">
                        <button type="submit" id="exam-add" class="btn btn-success">บันทึก</button>
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
        $('.summernote').summernote({
        placeholder: 'Hello bootstrap 4',
        tabsize: 2,
        height: 200
        });
        $(function() {
                $('#excel_file').bind("change", function() {
                    $('#overlay-loading').fadeIn(200);
                    var formData = new FormData();
                    formData.append("file", document.getElementById('excel_file').files[0]);
                    formData.append("subject", "<?php echo isset($exam['examination_subject']) ? $exam['examination_subject'] : '0'; ?>");
                    formData.append("exam" ,"<?php echo isset($exam['examination_id']) ? $exam['examination_id'] : '0'; ?>");

                    $.ajax({
                        url: weburl + "ajax/excel_import",
                        type: 'post',
                        data: formData,
                        dataType: 'json',
                        async: true,
                        processData: false,  // tell jQuery not to process the data
                        contentType: false,   // tell jQuery not to set contentType
                        success : function(response) {
                            $('#overlay-loading').fadeOut(200);
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
