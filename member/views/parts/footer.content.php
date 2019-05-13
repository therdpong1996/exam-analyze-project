<!-- Footer -->
<footer class="py-5">
  <div class="container">
    <div class="row align-items-center justify-content-xl-between">
      <div class="col-xl-6">
        <div class="copyright text-center text-xl-left text-muted">
          &copy; 2019 <a href="#" class="font-weight-bold ml-1" target="_blank">Compoterized Adaptive Testing</a>
        </div>
      </div>
      <div class="col-xl-6">
        <ul class="nav nav-footer justify-content-center justify-content-xl-end">
          <li class="nav-item">
            <a href="#" class="nav-link">Time: <?php echo date('d/m/Y H:i'); ?></a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link" target="_blank">About Us</a>
          </li>
          <li class="nav-item">
            <a href="https://github.com/creativetimofficial/argon-dashboard/blob/master/LICENSE.md" class="nav-link"
              target="_blank">MIT License</a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</footer>

<div class="overlay-loading" id="overlay-loading">
  <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
  <span class="sr-only">Loading...</span>
</div>

<div class="overlay-loading" id="no-connecting">
  <i class="fa fa-times fa-3x fa-fw"></i>
  <div class="mt-3 text-white">ไม่มีการเชื่อมต่อ Internet</div>
</div>
<?php 
    if (isset($_SESSION['auth']) and $user_row['role'] == 2 and !isset($_COOKIE['talert'])) {
?>
<div class="train-alert" id="train-alert">
  <div class="header">
    แจ้งเตือน
    <div class="close" onclick="setCookie('talert', 'true', 1); $('#train-alert').hide(200)"><i class="fa fa-times"></i></div>
  </div>
  <div class="content">
    <?php
      $stmtoo = $_DB->prepare("SELECT * FROM examinations WHERE (examination_train = 0 OR examination_train IS NULL) AND examination_subject IN (SELECT subject_id FROM subject_owner WHERE uid =:uid)");
      $stmtoo->bindParam(':uid', $_SESSION['uid']);
      $stmtoo->execute();
      while($rows = $stmtoo->fetch(PDO::FETCH_ASSOC)){
        
        $stmty = $_DB->prepare("SELECT COUNT(id) as c FROM answer_data WHERE examination = :examid");
        $stmty->bindParam(':examid', $rows['examination_id']);
        $stmty->execute();
        $dataNum = $stmty->fetch(PDO::FETCH_ASSOC);

        $stmtx = $_DB->prepare('SELECT COUNT(qa_id) AS qac FROM q_and_a WHERE qa_delete = 0 AND qa_exam = :exam');
        $stmtx->bindParam(':exam', $rows['examination_id']);
        $stmtx->execute();
        $ttNum = $stmtx->fetch(PDO::FETCH_ASSOC);

        $rate = ($ttNum['qac']*10);

        if($dataNum['c'] >= $rate){
?>
        ข้อสอบ <?php echo $rows['examination_title']; ?> มีข้อมูลเพียงพอสำหรับการวิเคราะห์ข้อมูล
        <a class="btn btn-success btn-sm" href="<?php url('examination/analyze/?examination_id='.$rows['examination_id'].'&overview'); ?>">วิเคราะห์ข้อมูล</a>
        <hr>
<?php
        }else{
?>
        ยินดีต้อนรับกลับ, <?php echo $_SESSION['username']; ?>
<?php
        }
      }
    ?>
  </div>
</div>
<?php } ?>