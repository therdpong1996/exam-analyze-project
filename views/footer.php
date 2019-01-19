        
    <footer class="footer">
        <div class="container">
            <div class="row align-items-center justify-content-md-between">
                <div class="col-md-6">
                <div class="copyright">
                    &copy; 2019 <a href="#" class="font-weight-bold ml-1" target="_blank">Compoterized Adaptive Testing</a>
                </div>
                </div>
                <div class="col-md-6">
                <ul class="nav nav-footer justify-content-end">
                    <li class="nav-item">
                    <a href="https://www.creative-tim.com/presentation" class="nav-link" target="_blank">About Us</a>
                    </li>
                    <li class="nav-item">
                    <a href="https://github.com/creativetimofficial/argon-design-system/blob/master/LICENSE.md" class="nav-link" target="_blank">MIT License</a>
                    </li>
                </ul>
                </div>
            </div>
        </div>
    </footer>
    <!-- Core -->
    <script src="<?php echo $_G['furl']; ?>assets/vendor/jquery/jquery.min.js"></script>
    <script src="<?php echo $_G['furl']; ?>assets/vendor/popper/popper.min.js"></script>
    <script src="<?php echo $_G['furl']; ?>assets/vendor/bootstrap/bootstrap.min.js"></script>
    <script src="<?php echo $_G['furl']; ?>assets/vendor/headroom/headroom.min.js"></script>

    <script>
        $.load("/static/post.data.json", function (response, status, request) {
            console.log(response);
            console.log(status);
            console.log(request);
        });

        //Add this below content to your HTML page, or add the js file to your page at the very top to register service worker
        if (navigator.serviceWorker.controller) {
            console.log('[PWA] active service worker found, no need to register')
        } else {
        //Register the ServiceWorker
        navigator.serviceWorker.register('service-worker.js', {
            scope: './'
        }).then(function(reg) {
            console.log('Service worker has been registered for scope:'+ reg.scope);
        });
        }
    </script>
</body>
</html>
