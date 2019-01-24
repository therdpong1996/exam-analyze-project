<?php

    @session_start();
    require_once 'member/control/init.php';

    $meta['title'] = 'Computerized Adaptive Testing';

    include_once 'views/header.php';
    include_once 'views/navbar.php';
?>

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
            <div class="container text-center mt--500">
                <h1 class="text-white">You are offline</h1>
                <h3 class="text-muted">Please connect the internet for access content</h3>
                <small class="text-muted">คุณกำลังออฟไลน์! โปรดเชื่อมต่ออินเทอร์เน็ตเพื่อเข้าถึงเนื้อหา</small>
                <div class="mb-5">
                    <a href="<?php furl(null); ?>" class="btn btn-warning btn-lg">กลับไปหน้าแรก</a>
                </div>
            </div>
        </section>
    </main>

<?php
    include_once 'views/footer.php';