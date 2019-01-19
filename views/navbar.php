
    <header class="header-global">
        <nav id="navbar-main" class="navbar navbar-main navbar-expand-lg navbar-cat navbar-light headroom">
        <div class="container">
            <a class="navbar-brand mr-lg-5" href="<?php echo $_G['furl']; ?>">
            <img src="<?php furl('assets/img/brand/white.png'); ?>">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar_global" aria-controls="navbar_global" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="navbar-collapse collapse" id="navbar_global">
            <div class="navbar-collapse-header">
                <div class="row">
                <div class="col-6 collapse-brand">
                    <a href="<?php echo $_G['furl']; ?>">
                    <img src="<?php furl('assets/img/brand/blue.png'); ?>">
                    </a>
                </div>
                <div class="col-6 collapse-close">
                    <a href="<?php url(null); ?>" class="btn d-lg-none btn-primary btn-sm">เข้าสู่ระบบ</a>
                </div>
                </div>
            </div>
            <ul class="navbar-nav align-items-lg-center ml-lg-auto">
                <li class="nav-item d-lg-block ml-lg-4">
                <a href="<?php url(null); ?>" class="btn btn-neutral btn-icon">
                    <span class="btn-inner--icon">
                    <i class="fa fa-user mr-2"></i>
                    </span>
                    <span class="nav-link-inner--text">เข้าสู่ระบบ</span>
                </a>
                </li>
            </ul>
            </div>
        </div>
        </nav>
    </header>
