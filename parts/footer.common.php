  <!-- Argon Scripts -->
  <!-- Core -->
  <script src="<?php echo $_G['url'];?>assets/vendor/jquery/dist/jquery.min.js"></script>
  <script src="<?php echo $_G['url'];?>assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Optional JS -->
  <script src="<?php echo $_G['url'];?>assets/vendor/chart.js/dist/Chart.min.js"></script>
  <script src="<?php echo $_G['url'];?>assets/vendor/chart.js/dist/Chart.extension.js"></script>
  <!-- Argon JS -->
  <script src="<?php echo $_G['url'];?>assets/js/argon.js?v=1.0.0"></script>

  <script>
    $('#password').on('keyup', function(){
      var password = $(this).val();
      $('#password_chk').html(checkPassStrength(password));
    })

    function scorePassword(pass) {
        var score = 0;
        if (!pass)
            return score;

        // award every unique letter until 5 repetitions
        var letters = new Object();
        for (var i=0; i<pass.length; i++) {
            letters[pass[i]] = (letters[pass[i]] || 0) + 1;
            score += 5.0 / letters[pass[i]];
        }

        // bonus points for mixing it up
        var variations = {
            digits: /\d/.test(pass),
            lower: /[a-z]/.test(pass),
            upper: /[A-Z]/.test(pass),
            nonWords: /\W/.test(pass),
        }

        variationCount = 0;
        for (var check in variations) {
            variationCount += (variations[check] == true) ? 1 : 0;
        }
        score += (variationCount - 1) * 10;

        return parseInt(score);
    }

    function checkPassStrength(pass) {
        var score = scorePassword(pass);
        if (score > 60)
            return "<span class=\"text-success font-weight-700\">strong</span>";
        if (score > 45)
            return "<span class=\"text-warning font-weight-700\">good</span>";
        if (score >= 30)
            return "<span class=\"text-danger font-weight-700\">weak</span>";

        return "<span class=\"text-danger font-weight-700\">weak</span>";
    }

    $('#username').on('change', function(){
      var username = $(this).val();

      if(stu_id.length <= 6){
        alert('Invalid Username');
        return;
      }

      $.ajax({
        type: "POST",
        url: weburl + "ajax/verify_username",
        data: {username: username},
        dataType: "json",
      }).done(function(response){
        if(response.state){
          $('#username_chk').html('username is already');
        }else{
          $('#username_chk').html('');
        }
      })
    })

    $('#register-form').on('submit', function(){
      var data = $(this).serialize();
      
      $.ajax({
        type: "POST",
        url: weburl + "ajax/register",
        data: data,
        dataType: "json"
      })
      .done(function(response){
        if(response.state){

        }else{
          
        }
      })
    });

    $('#verify_stu').on('click', function(){
      let stu_id = $('#stu_id').val();
      
      if(stu_id.length != 13){
        alert('Invalid Student ID');
        return;
      }

      $.ajax({
        type: "POST",
        url: weburl + "ajax/verify_student",
        data: {stu_id: stu_id},
        dataType: "json",
      })
      .done(function(response){
        if(response.state){
          $('#fullname').val(response.full_name);
          $('#email').val(response.email);
        }else{
          alert(response.msg)
          return;
        }
      });

    })
  </script>
</body>

</html>