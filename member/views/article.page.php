<!-- Main content -->
<div class="main-content">
    <!-- Top navbar -->
    <nav class="navbar bg-gradient-primary navbar-top navbar-expand-md navbar-dark" id="navbar-main">
        <div class="container-fluid">
        <!-- Brand -->
        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="<?php url('article/'); ?>">Article <small>(บทความ)</small></a>
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
                        <h6 class="text-uppercase text-muted ls-1 mb-1">เขียนบทความใหม่</h6>
                        <h2 class="mb-0">New Article</h2>
                    </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="javascript:void(0)" id="add-article">
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="uid" value="<?php echo $user_row['uid']; ?>">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label" for="article_title">หัวข้อ</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="article_title" name="article_title" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label" for="article_content">เนื้อหา</label>
                        <div class="col-sm-10">
                            <textarea class="form-control summernote" id="article_content" name="article_content" required rows="3"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label" for="article_tag">แท็ก</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="article_tag" name="article_tag" data-role="tagsinput" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label" for="article_publist">เผยแพร่</label>
                        <div class="col-sm-10">
                            <div class="input-group input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                </div>
                                <input class="form-control datepicker" id="article_publist" name="article_publist" placeholder="Select date" type="text">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-10">
                        <button type="submit" id="add-article-btn" class="btn btn-success">บันทึก</button>
                        <a href="<?php url('article/'); ?>" class="btn btn-danger">ยกเลิก</a>
                        </div>
                    </div>
                    </form>
                </div>
                </div>
            <?php } elseif (isset($_GET['edit'])) { ?>
                <div class="card shadow">
                <div class="card-header bg-transparent">
                    <div class="row align-items-center">
                    <div class="col">
                        <h6 class="text-uppercase text-muted ls-1 mb-1">แก้ไขบทความ</h6>
                        <h2 class="mb-0" id="article-title"></h2>
                    </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="javascript:void(0)" id="edit-article">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" id="article-id" name="article_id" value="">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label" for="article_title">หัวข้อ</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="article_title" name="article_title" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label" for="article_content">เนื้อหา</label>
                        <div class="col-sm-10">
                            <textarea class="form-control summernote" id="article_content" name="article_content" required rows="3"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label" for="article_tag">แท็ก</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="article_tag" name="article_tag" data-role="tagsinput" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label" for="article_publist">เผยแพร่</label>
                        <div class="col-sm-10">
                            <div class="input-group input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                </div>
                                <input class="form-control datepicker" id="article_publist" name="article_publist" placeholder="Select date" type="text">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-10">
                        <button type="submit" id="save-article-btn" class="btn btn-success">บันทึก</button>
                        <a href="<?php url('article/'); ?>" class="btn btn-danger">ยกเลิก</a>
                        </div>
                    </div>
                    </form>
                </div>
                </div>
            <?php } else { ?>
                <a class="btn btn-success mb-3" href="?add"><span class="btn-inner--icon"><i class="ni ni-fat-add"></i></span> เขียนบทความ</a>
                <div class="card shadow">
                <div class="card-header bg-transparent">
                    <div class="row align-items-center">
                    <div class="col">
                        <h6 class="text-uppercase text-muted ls-1 mb-1">รายการบทความ</h6>
                        <h2 class="mb-0">Article</h2>
                    </div>
                    </div>
                </div>
                <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-items-center">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">Title</th>
                            <th scope="col">Read</th>
                            <th scope="col">Publish time</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                    </table>
                </div>
                </div>
            </div>
            <?php } ?>
        </div>
        </div>
    </div>
    <script type="text/javascript">
        $('.summernote').summernote({
            placeholder: 'ข้อความ',
            tabsize: 2,
            height: 300
        });
    </script>