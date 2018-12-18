<!-- Main content -->
<div class="main-content">
    <!-- Top navbar -->
    <nav class="navbar bg-gradient-primary navbar-top navbar-expand-md navbar-dark" id="navbar-main">
        <div class="container-fluid">
        <!-- Brand -->
        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="<?php url('admin/setting'); ?>">Website Setting <small>(ตั้งค่า)</small></a>
        <?php require_once 'parts/usermenu.common.php'; ?>
        
        <div class="container-fluid pb-5 pt-5 pt-md-8">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card shadow">
                        <div class="card-body">
                            <form action="javascript:void(0)" id="setting-form">
                                <h2>ตั้งค่าทั่วไป</h2>
                                <hr>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="init_regis">ระบบสมัครสมาชิก</label>
                                    <div class="col-sm-10">
                                        <label class="custom-toggle">
                                            <input type="checkbox" class="setting_sw" id="init_regis" name="init_regis" value="1" <?php __($_GS['init_regis']==1?'checked':''); ?>>
                                            <span class="custom-toggle-slider rounded-circle"></span>
                                        </label><br>
                                        <small class="text-muted">เปิด-ปิด ระบบสมัครสมาชิกเว็บไซต์</small>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="init_article">ระบบบทความ</label>
                                    <div class="col-sm-10">
                                        <label class="custom-toggle">
                                            <input type="checkbox" class="setting_sw" id="init_article" name="init_article" value="1" <?php __($_GS['init_article']==1?'checked':''); ?>>
                                            <span class="custom-toggle-slider rounded-circle"></span>
                                        </label><br>
                                        <small class="text-muted">เปิด-ปิด การเพิ่ม แก้ไข และลบบทความ</small>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="init_subject">ระบบรายวิชา</label>
                                    <div class="col-sm-10">
                                        <label class="custom-toggle">
                                            <input type="checkbox" class="setting_sw" id="init_subject" name="init_subject" value="1" <?php __($_GS['init_subject']==1?'checked':''); ?>>
                                            <span class="custom-toggle-slider rounded-circle"></span>
                                        </label><br>
                                        <small class="text-muted">เปิด-ปิด การเพิ่ม แก้ไข และลบรายวิชา</small>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="init_exam">ระบบคลังข้อสอบ</label>
                                    <div class="col-sm-10">
                                        <label class="custom-toggle">
                                            <input type="checkbox" class="setting_sw" id="init_exam" name="init_exam" value="1" <?php __($_GS['init_exam']==1?'checked':''); ?>>
                                            <span class="custom-toggle-slider rounded-circle"></span>
                                        </label><br>
                                        <small class="text-muted">เปิด-ปิด การเพิ่ม แก้ไข และลบคลังข้อสอบ</small>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="init_session">ระบบเซสชั่น</label>
                                    <div class="col-sm-10">
                                        <label class="custom-toggle">
                                            <input type="checkbox" class="setting_sw" id="init_session" name="init_session" value="1" <?php __($_GS['init_session']==1?'checked':''); ?>>
                                            <span class="custom-toggle-slider rounded-circle"></span>
                                        </label><br>
                                        <small class="text-muted">เปิด-ปิด การเพิ่ม แก้ไข และลบเซสชั่น</small>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="init_testing">ระบบการทดสอบ</label>
                                    <div class="col-sm-10">
                                        <label class="custom-toggle">
                                            <input type="checkbox" class="setting_sw" id="init_testing" name="init_testing" value="1" <?php __($_GS['init_testing']==1?'checked':''); ?>>
                                            <span class="custom-toggle-slider rounded-circle"></span>
                                        </label><br>
                                        <small class="text-muted">เปิด-ปิด ระบบการทดสอบของนักศึกษา</small>
                                    </div>
                                </div>
                                <h2>ตั้งค่า Adaptive</h2>
                                <hr>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label" for="init_graph">ระบบ train data and  graph</label>
                                    <div class="col-sm-9">
                                        <label class="custom-toggle">
                                            <input type="checkbox" class="setting_sw" id="init_graph" name="init_graph" value="1" <?php __($_GS['init_graph']==1?'checked':''); ?>>
                                            <span class="custom-toggle-slider rounded-circle"></span>
                                        </label><br>
                                        <small class="text-muted">เปิด-ปิด ระบบการทดสอบของนักศึกษา</small>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button id="setting-btn" class="btn btn-primary btn-block btn-lg" type="submit"><i class="fas fa-save"></i> บันทึก</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $('#setting-form').on('submit', function () {
                var oldtext = $('#setting-btn').html();
                $('#setting-btn').html('<i class="fa fa-spinner fa-spin"></i> Process..');
                var setData = $(this').serialize();

                $.ajax({
                    type: "POST",
                    url: weburl + "ajax/admin_setting",
                    data: setData,
                    dataType: "json",
                    success: function (response) {
                        window.location.href = window.location.href
                    }
                });
            })
        </script>