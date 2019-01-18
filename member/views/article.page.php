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
                        <label class="col-sm-2 col-form-label" for="article_subject">รายวิชา</label>
                        <div class="col-sm-10">
                            <select class="form-control" id="article_subject" name="article_subject" required>
                                <?php
                                    $stm1 = $_DB->prepare("SELECT subject_id FROM subject_owner WHERE uid = :uid");
                                    $stm1->bindParam(":uid", $user_row['uid']);
                                    $stm1->execute();
                                    while ($orows = $stm1->fetch(PDO::FETCH_ASSOC)) {
                                    $stm = $_DB->prepare('SELECT * FROM subjects WHERE subject_id = :id');
                                    $stm->bindParam(':id', $orows['subject_id']);
                                    $stm->execute();
                                    while ($rows = $stm->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                                    <option value="<?php echo $rows['subject_id']; ?>"><?php echo $rows['subject_title']; ?></option>
                                <?php } } ?>
                            </select>
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
                        <label class="col-sm-2 col-form-label" for="article_poston">เผยแพร่เมื่อ</label>
                        <div class="col-sm-10">
                            <div class="input-group input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                </div>
                                <input class="form-control datepicker" id="article_poston" name="article_poston" value="<?php __(date('Y/m/d')); ?>" type="text">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label" for="article_order">ลำดับ</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="article_order" name="article_order">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2" for="article_public">สาธารณะ</label>
                        <div class="col-sm-10">
                            <div class="custom-control custom-checkbox mb-3">
                            <input class="custom-control-input" id="article_public" value="1" name="article_public" type="checkbox">
                            <label class="custom-control-label" for="article_public">เปิด</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-10">
                        <button type="submit" id="add-article-btn" class="btn btn-success">บันทึก</button>
                        </div>
                    </div>
                    </form>
                </div>
                </div>
            <?php 
                } elseif (isset($_GET['edit'])) {
                    $stm = $_DB->prepare('SELECT * FROM articles WHERE atid = :atid');
                    $stm->bindParam(':atid', $_GET['article_id'], PDO::PARAM_INT);
                    $stm->execute();
                    $row = $stm->fetch(PDO::FETCH_ASSOC);
            ?>
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
                    <input type="hidden" id="article-id" name="article_id" value="<?php __($row['atid']); ?>">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label" for="article_title">หัวข้อ</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="article_title" name="article_title" value="<?php __($row['title']); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label" for="article_subject">รายวิชา</label>
                        <div class="col-sm-10">
                            <select class="form-control" id="article_subject" name="article_subject" required>
                                <?php
                                    $stm1 = $_DB->prepare("SELECT subject_id FROM subject_owner WHERE uid = :uid");
                                    $stm1->bindParam(":uid", $user_row['uid']);
                                    $stm1->execute();
                                    while ($orows = $stm1->fetch(PDO::FETCH_ASSOC)) {
                                    $stm = $_DB->prepare('SELECT * FROM subjects WHERE subject_id = :id');
                                    $stm->bindParam(':id', $orows['subject_id']);
                                    $stm->execute();
                                    while ($rows = $stm->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                                    <option value="<?php echo $rows['subject_id']; ?>" <?php echo ($rows['subject_id'] == $row['subject']) ? 'selected' : ''; ?>><?php echo $rows['subject_title']; ?></option>
                                <?php } } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label" for="article_content">เนื้อหา</label>
                        <div class="col-sm-10">
                            <textarea class="form-control summernote" id="article_content" name="article_content" required rows="3"><?php __($row['content']); ?></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label" for="article_tag">แท็ก</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="article_tag" name="article_tag" data-role="tagsinput" value="<?php __($row['tags']); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label" for="article_poston">เผยแพร่เมื่อ</label>
                        <div class="col-sm-10">
                            <div class="input-group input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                </div>
                                <input class="form-control datepicker" id="article_poston" name="article_poston" value="<?php echo date('Y/m/d', strtotime($row['poston'])); ?>" type="text">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label" for="article_order">ลำดับ</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="article_order" name="article_order" value="<?php __($row['a_order']); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2" for="article_public">สาธารณะ</label>
                        <div class="col-sm-10">
                            <div class="custom-control custom-checkbox mb-3">
                            <input class="custom-control-input" id="article_public" value="1" name="article_public" type="checkbox" <?php echo $row['public'] == 1 ? 'checked' : ''; ?>>
                            <label class="custom-control-label" for="article_public">เปิด</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-10">
                        <button type="submit" id="save-article-btn" class="btn btn-success">บันทึก</button>
                        </div>
                    </div>
                    </form>
                </div>
                </div>
            <?php } else { 
                $stmj = $_DB->prepare("SELECT subject_title FROM subjects WHERE subject_id = :sid");
                $stmj->bindParam(":sid", $_GET['sub_id']);
                $stmj->execute();
                $subj = $stmj->fetch(PDO::FETCH_ASSOC);
            ?>
                <a class="btn btn-success mb-3" href="?add"><span class="btn-inner--icon"><i class="ni ni-fat-add"></i></span> เขียนบทความ</a>
                <div class="card shadow">
                <div class="card-header bg-transparent">
                    <div class="row align-items-center">
                    <div class="col">
                        <h6 class="text-uppercase text-muted ls-1 mb-1">รายการบทความ</h6>
                        <h2 class="mb-0">Article ของ <?php __($subj['subject_title']); ?></h2>
                    </div>
                    </div>
                </div>
                <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-items-center table-sortable sort-table">
                    <thead class="thead-light">
                        <tr>
                            <th></th>
                            <th scope="col">Week</th>
                            <th scope="col">Title</th>
                            <th scope="col">Writer</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody id="article-content">
                            <?php
                                $stm = $_DB->prepare('SELECT articles.atid,articles.title,articles.reads,articles.poston,articles.public,articles.a_order,users.full_name FROM articles JOIN users ON articles.uid = users.uid WHERE articles.subject = :sub_id ORDER BY articles.a_order ASC');
                                $stm->bindParam(":sub_id", $_GET['sub_id']);
                                $stm->execute();
                                while ($rows = $stm->fetch(PDO::FETCH_ASSOC)) {
                            ?>
                            <tr atid="<?php echo $rows['atid']; ?>" id="article-<?php echo $rows['atid']; ?>">
                                <td>
                                    <i class="fa fa-arrows-alt"></i>
                                </td>
                                <td>
                                    <?php echo $rows['a_order']; ?>
                                </td>
                                <td>
                                    <?php echo $rows['title']; ?>
                                </td>
                                <td>
                                    <?php echo $rows['full_name']; ?>
                                </td>
                                <td class="text-right">
                                    <a class="btn btn-primary btn-sm" href="<?php url('post/'.$rows['atid']); ?>">Read</a>
                                    <a href="?edit&article_id=<?php echo $rows['atid']; ?>" class="btn btn-info btn-sm">Edit</a> 
                                    <button id="delete-btn-<?php echo $rows['atid']; ?>" onclick="article_delete(<?php echo $rows['atid']; ?>)" class="btn btn-danger btn-sm">Delete</button>
                                </td>
                            </tr>
                        <?php } ?>
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

        $('.table-sortable tbody').sortable({
            handle: 'i'
        });

        var Arorder = [];

        $('.table-sortable tbody').sortable().bind('sortupdate', function() {
            $('#overlay-loading').fadeIn(200);
            Arorder = [];
            $('.table-sortable tbody tr').each(function() {
                Arorder.push($(this).attr('atid'))
            });

            $.ajax({
                url: weburl + 'ajax/order_article',
                type: 'POST',
                dataType: 'html',
                data: {atid: Arorder, subject: '<?php echo $_GET['sub_id']; ?>'}
            })
            .done(function(response) {
                $('#article-content').html(response)
                $('#overlay-loading').fadeOut(200);
                $('.table-sortable tbody').sortable({
                    handle: 'i'
                });
            });
        });
    </script>