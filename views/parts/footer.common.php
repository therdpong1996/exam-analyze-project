

  <script src="<?php echo $_G['url']; ?>assets/js/argon.js?v=1.0.0"></script>

  
  <?php 
    if (isset($_SESSION['auth'])) {
      if ($user_row['role'] == 1) {
  ?>
  	<script src="<?php echo $_G['url']; ?>js/role_1.apps.js?v=1.0.0"></script>
  <?php } elseif ($user_row['role'] == 2) { ?>
  	<script src="<?php echo $_G['url']; ?>js/role_2.apps.js?v=1.0.0"></script>
  <?php } elseif ($user_row['role'] == 3) { ?>
  	<script src="<?php echo $_G['url']; ?>js/role_3.apps.js?v=1.0.0"></script>
  <?php } else { ?>
  	<script src="<?php echo $_G['url']; ?>js/noauth.apps.js?v=1.0.1"></script>
  <?php } } ?>
</body>
</html>