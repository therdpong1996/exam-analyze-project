
    <main class="profile-page">
        <section class="section-profile-cover section-shaped my-0">
        <!-- Circles background -->
        <div class="shape shape-style-1 shape-primary alpha-4">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        </div>
        <!-- SVG separator -->
        <div class="separator separator-bottom separator-skew">
            <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
            <polygon class="fill-white" points="2560 0 2560 100 0 100"></polygon>
            </svg>
        </div>
        </section>
        <section class="section">
            <div class="container mt--500">
                <div class="text-center mb-5">
                    <h1 class="text-white">Computerized Adaptive Testing</h1>
                    <h4 class="text-muted">บทความสำหรับการศึกษา</h4>
                </div>

                <div class="row">
<?php
    $stm = $_DB->prepare("SELECT articles.atid,articles.title,articles.content,articles.poston,subjects.subject_title,users.full_name FROM articles JOIN subjects ON articles.subject = subjects.subject_id JOIN users ON articles.uid = users.uid WHERE articles.public = 1 ORDER BY articles.atid DESC LIMIT 20");
    $stm->execute();
    while ($rows = $stm->fetch(PDO::FETCH_ASSOC)) {
?>
                    <div class="col-12 col-md-6 mb-4">
                        <div class="card shadow">
                            <div class="card-header">
                            <?php echo $rows['title']; ?>
                            </div>
                            <div class="card-body">
                                <p class="text-muted"><?php echo iconv_substr(strip_tags($rows['content']), 0,300, "UTF-8"); ?>...</p>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-6">
                                        <a class="btn btn-success" href="<?php furl('article/'.$rows['atid']); ?>">อ่านเพิ่มเติม</a>
                                    </div>
                                    <div class="col-6 d-flex align-items-center justify-content-end">
                                        <small>โดย: <?php echo $rows['full_name']; ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
<?php } ?>
                </div>
                
            </div>
        </section>
    </main>
