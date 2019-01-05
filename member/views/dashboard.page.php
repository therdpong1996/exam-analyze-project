<!-- Main content -->
<div class="main-content">
    <!-- Top navbar -->
    <nav class="navbar bg-gradient-primary navbar-top navbar-expand-md navbar-dark" id="navbar-main">
        <div class="container-fluid">
        <!-- Brand -->
        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="#">Timeline</a>
        <?php require_once 'parts/usermenu.common.php'; ?>
        
        <div class="container-fluid pb-8 pt-5 pt-md-8">
            <div class="row">
                <div class="col-xl-12">
                    <?php
                        $stm1 = $_DB->prepare("SELECT subject_id FROM subject_owner WHERE uid = :uid");
                        $stm1->bindParam(":uid", $user_row['uid']);
                        $stm1->execute();
                        while ($orows = $stm1->fetch(PDO::FETCH_ASSOC)) {
                            $stm = $_DB->prepare('SELECT * FROM timeline WHERE subject_id = :id AND for_time = :role');
                            $stm->bindParam(':id', $orows['subject_id']);
                            $stm->bindParam(':role', $user_row['role']);
                            $stm->execute();
                            while ($rows = $stm->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <div class="card shadow mb-4">
                        <div class="card-body text-center">
                            <?php print_r($rows); ?>
                        </div>
                    </div>
                    <?php } } ?>
                </div>
            </div>
        </div>