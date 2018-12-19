<body class="bg-default">
    <div class="main-content">
    <!-- Navbar -->
    <nav class="navbar navbar-top navbar-horizontal navbar-expand-md navbar-dark">
        <div class="container px-4">
        <a class="navbar-brand" href="../index.html">
            <img src="../assets/img/brand/white.png" />
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-collapse-main" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbar-collapse-main">
            <!-- Collapse header -->
            <div class="navbar-collapse-header d-md-none">
            <div class="row">
                <div class="col-6 collapse-brand">
                <a href="../index.html">
                    <img src="../assets/img/brand/blue.png">
                </a>
                </div>
                <div class="col-6 collapse-close">
                <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbar-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle sidenav">
                    <span></span>
                    <span></span>
                </button>
                </div>
            </div>
            </div>
            <!-- Navbar items -->
            <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link nav-link-icon" href="<?php echo $_G['url']; ?>login/">
                <i class="ni ni-key-25"></i>
                <span class="nav-link-inner--text">Login</span>
                </a>
            </li>
            </ul>
        </div>
        </div>
    </nav>
    <!-- Header -->
    <div class="header bg-gradient-primary py-7 py-lg-8">
        <div class="separator separator-bottom separator-skew zindex-100">
        <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
            <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
        </svg>
        </div>
    </div>
    <!-- Page content -->
    <div class="container mt--8 pb-5">
        <!-- Table -->
        <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card bg-secondary shadow border-0">
            <div class="card-header bg-transparent pb-2 text-center">
                <h1>Create new account</h1>
                <small>สมัครสมาชิก</small>
            </div>
            <div class="card-body px-lg-5 py-lg-5">
                <form role="form" action="javascript:void(0)" id="register-form">
                <div class="form-group">
                    <div class="input-group input-group-alternative">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-id-badge"></i></span>
                    </div>
                    <input class="form-control" placeholder="Username" id="username" name="username" type="text" required minlength="6" maxlength="15">
                    </div>
                    <small class="font-weight-700 text-danger font-italic" id="username_chk"></small>
                </div>
                <div class="form-group">
                    <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                        <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                    </div>
                    <input class="form-control" placeholder="Password" id="password" name="password" type="password" required>
                    </div>
                    <div class="text-muted font-italic"><small>password strength: <span id="password_chk"><span class="text-danger font-weight-700">weak</span></span></small></div>
                </div>
                <div class="form-group">
                    <div class="row">
                    <div class="col-10">
                        <div class="input-group input-group-alternative mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                        </div>
                        <input class="form-control" placeholder="Student ID" id="stu_id" name="student_id" type="text" required>
                        </div>
                    </div>
                    <div class="col-2">
                        <button class="btn btn-warning" type="button" id="verify_stu"><i class="fas fa-search"></i></button>
                    </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group input-group-alternative mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                    </div>
                    <input class="form-control" placeholder="Full Name" id="fullname" name="fullname" type="text" required>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group input-group-alternative mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                    </div>
                    <input class="form-control" placeholder="Email" id="email" name="email" type="email" required>
                    </div>
                </div>
                <div class="row my-4">
                <div class="col-12">
                    <div class="custom-control custom-control-alternative custom-checkbox">
                        <input class="custom-control-input" id="customCheckRegister" name="customCheckRegister" type="checkbox">
                        <label class="custom-control-label" for="customCheckRegister">
                        <span class="text-muted">I agree with the <a href="#!">Privacy Policy</a></span>
                        </label>
                    </div>
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" id="register-btn" class="btn btn-primary mt-4 disabled" disabled >Create account</button>
                  <div class="mt-3"><small class="text-muted">*โปรดตรวจสอบรหัสนักศึกษาก่อนทุกครั้ง</small></div>
                </div>
                </form>
            </div>
            </div>
        </div>
        </div>
    </div>
    </div>