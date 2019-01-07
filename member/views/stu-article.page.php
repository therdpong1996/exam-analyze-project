<!-- Main content -->
<div class="main-content">
    <!-- Top navbar -->
    <nav class="navbar bg-gradient-primary navbar-top navbar-expand-md navbar-dark" id="navbar-main">
        <div class="container-fluid">
        <!-- Brand -->
        <?php
            $stms = $_DB->prepare("SELECT * FROM subjects WHERE subject_id = :sid");
            $stms->bindParam(":sid", $_GET['sub_id']);
            $stms->execute();
            $subject = $stms->fetch(PDO::FETCH_ASSOC);
        ?>
        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="#"><?php __($subject['subject_title']); ?></a>
        <?php require_once 'parts/usermenu.common.php'; ?>
        <div class="container-fluid pb-8 pt-5 pt-md-8">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="text-center">
                                <h2><?php __($subject['subject_title']); ?></h2>
                            </div>
                            <hr>
                            <h4>คำอธิบาย</h4>
                            <p class="text-muted"><?php __($subject['subject_detail']); ?></p>
                            <h4>ผู้สอน</h4>
                            <ul>
                            <?php
                                $stmp = $_DB->prepare("SELECT full_name FROM users WHERE uid IN (SELECT DISTINCT(uid) FROM subject_owner WHERE subject_id = :sid)");
                                $stmp->bindParam(":sid", $_GET['sub_id']);
                                $stmp->execute();
                                while ($pro = $stmp->fetch(PDO::FETCH_ASSOC)) {
                            ?>
                                <li><?php __($pro['full_name']); ?></li>
                            <?php } ?>
                            </ul>
                            <h4>บทความ</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <td width="10%">Week</td>
                                            <td>Articles</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $stma = $_DB->prepare("SELECT atid,order,title FROM articles WHERE subject = :sid ORDER BY order ASC)");
                                            $stma->bindParam(":sid", $_GET['sub_id']);
                                            $stma->execute();
                                            while ($ar = $stma->fetch(PDO::FETCH_ASSOC)) {
                                        ?>
                                        <tr>
                                            <td><?php __($ar['order']); ?></td>
                                            <td><a href="<?php furl('article/'.$ar['atid'].'/'.$ar['title']); ?>"><?php __($ar['title']); ?></a></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>