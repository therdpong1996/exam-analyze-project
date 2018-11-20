
  <script src="<?php echo $_G['url'];?>assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="<?php echo $_G['url'];?>assets/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
  <!-- Optional JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
  <script src="<?php echo $_G['url'];?>assets/js/argon.js?v=1.0.0"></script>
  <script src="<?php echo $_G['url'];?>assets/js/sweetalert2.all.min.js?v=1.0.0"></script>
  <script src="<?php echo $_G['url'];?>assets/dist/easytimer.min.js"></script>
  <script src="<?php echo $_G['url'];?>assets/dist/js.cookie-2.2.0.min.js"></script>

  <?php if($user_row['role'] == 1){ ?>
  	<script src="<?php echo $_G['url'];?>js/role_1.apps.js?v=1.0.0"></script>
  <?php }elseif($user_row['role'] == 2){ ?>
  	<script src="<?php echo $_G['url'];?>js/role_2.apps.js?v=1.0.0"></script>
  <?php }elseif($user_row['role'] == 3){ ?>
  	<script src="<?php echo $_G['url'];?>js/role_3.apps.js?v=1.0.0"></script>
  <?php }else{ ?>
  	<script src="<?php echo $_G['url'];?>js/noauth.apps.js?v=1.0.0"></script>
  <?php } ?>
</body>
</html>