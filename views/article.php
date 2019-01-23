
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
                    <h1 class="text-white"><?php echo $row['title']; ?></h1>
                    <h5 class="text-muted">รายวิชา: <?php echo $row['subject_title']; ?> โดย: <?php echo $row['full_name']; ?></h5>
                </div>
                <a href="<?php furl(null); ?>" class="btn btn-warning mb-3">กลับ</a>
                <div class="card shadow">
                    <div class="card-body">
                        <?php echo $row['content']; ?>
                    </div>
                    <div class="card-footer">
                        <?php
                            $tags = explode(',', $row['tags']);
                            foreach ($tags as $tag) {
                        ?>
                            <a class="badge badge-primary" href="<?php furl('tags/'.urlencode(strtolower($tag))); ?>"><?php echo $tag ;?></a>
                        <?php
                            }
                        ?>
                    </div>
                </div>
                
            </div>
        </section>
    </main>
