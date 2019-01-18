<!-- Main content -->
<div class="main-content">
    <!-- Top navbar -->
    <nav class="navbar bg-gradient-primary navbar-top navbar-expand-md navbar-dark" id="navbar-main">
        <div class="container-fluid">
        <!-- Brand -->
        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="#">Article <small>(บทความ)</small></a>
        <?php require_once 'parts/usermenu.common.php'; ?>
    <!-- Page content -->
    <div class="container-fluid pb-8 pt-5 pt-md-8">
        <div class="row">
            <div class="col-xl-12">
                <div class="card shadow">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                                <h2 class="mb-0"><?php __($article['title']); ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php __($article['content']); ?>
                    </div>
                    <div class="card-footer">
                        <small>โดย: <?php __($article['full_name']); ?></small>
                    </div>
                </div>
            </div>
        </div>
    </div>