        
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
<?php
    $post = [];

    $stm = $_DB->prepare("SELECT articles.atid,articles.title,articles.content,articles.poston,subjects.subject_title,users.full_name FROM articles JOIN subjects ON articles.subject = subjects.subject_id JOIN users ON articles.uid = users.uid WHERE articles.public = 1 ORDER BY articles.atid DESC");
    $stm->execute();
    while ($rows = $stm->fetch(PDO::FETCH_ASSOC)) {
        array_push($post, $rows);
    }
    $data = json_encode($post);
?>
    <script>
        var postLdata = <?php echo $data; ?>;

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
    <script src="<?php echo $_G['furl']; ?>assets/js/apps.js"></script>
</body>
</html>
