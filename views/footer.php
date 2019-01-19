        
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
        var weburl = '<?php echo $_G['furl']; ?>';

        function readArticle(atid){
            var index = findChartIndex(atid);
            $.ajax({
                type: "GET",
                url: weburl + "static/post.data.json",
                dataType: "json",
                success: function (response) {
                    $('#content-rows').append('<div class="card shadow mb-3"><div class="card-header"><h2 class="mb-0">'+response[index].title+'</h2></div><div class="card-body">'+response[index].content+'</div><div class="card-footer"><div class="row"><div class="col-6"></div><div class="col-6 text-right"><small>โดย: '+response[index].full_name+'</small></div></div></div></div>')
                }
            });
        }

        function findChartIndex(atid){
            var index = 0;
            $.ajax({
                type: "GET",
                url: weburl + "static/post.data.json",
                dataType: "json",
                success: function (response) {
                    for(x in response){
                        if (response[x].atid == atid){
                            return index;
                        }else{
                            index++;
                        }
                    }
                }
            });
        }
        
        function initialApp() {
            $.ajax({
                type: "GET",
                url: weburl + "static/post.data.json",
                dataType: "json",
                success: function (response) {
                    for(x in response){
                        $('#content-rows').append('<div class="card shadow mb-3"><div class="card-header"><h2 class="mb-0">'+response[x].title+'</h2></div><div class="card-body">'+strip_html_tags(response[x].content).substring(0,1000)+'...</div><div class="card-footer"><div class="row"><div class="col-6"><button class="btn btn-primary" onclick="readArticle('+response[x].atid+')">อ่านเพิ่มเติม</button></div><div class="col-6 text-right"><small>โดย: '+response[x].full_name+'</small></div></div></div></div>')
                    }
                }
            });
        }

        initialApp();


        function strip_html_tags(str){
            if ((str===null) || (str===''))
                return false;
            else
            str = str.toString();
            return str.replace(/<[^>]*>/g, '');
        }

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
