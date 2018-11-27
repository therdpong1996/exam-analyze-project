// ----------------------------------LOGIN--------------------------
$('#login-form').on('submit', function(){
    var oldtext = $('#login-btn').html();
    $('#login-btn').html('<i class="fa fa-spinner fa-spin"></i> Process..');
    var data = $(this).serialize();
    
    $.ajax({
      type: "POST",
      url: weburl + "ajax/login",
      data: data,
      dataType: "json"
    })
    .done(function(response){
      if(response.state){
        swal({
          title: 'SUCCESS',
          text: response.msg,
          type: 'success',
          showCancelButton: false,
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'Yes'
        }).then(function(result){
          if (result.value) {
            window.location.href = weburl + "dashboard/";
          }
        });
      }else{
        $('#login-btn').html(oldtext);
        swal(
          'SORRY',
          response.msg,
          'error'
        );
      }
  });
});

  // ----------------------------------REGISTER------------------------
  $('#password').on('keyup', function(){
    var password = $(this).val();
    $('#password_chk').html(checkPassStrength(password));
  });

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
      };

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
    });
  });

  $('#register-form').on('submit', function(){
    var oldtext = $('#register-btn').html();
    $('#register-btn').html('<i class="fa fa-spinner fa-spin"></i> Process..');
    var data = $(this).serialize();

    $.ajax({
      type: "POST",
      url: weburl + "ajax/register",
      data: data,
      dataType: "json"
    })
    .done(function(response){
      if(response.state){
        swal({
          title: 'SUCCESS',
          text: response.msg,
          type: 'success',
          showCancelButton: false,
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'Yes'
        }).then(function(result){
          if (result.value) {
            window.location.href = weburl + "dashboard/";
          }
        });
      }else{
        $('#register-btn').html(oldtext);
        swal(
          'ERROR',
          response.msg,
          'error'
        );
      }
    });
  });

  $('#verify_stu').on('click', function(){
    var oldtext = $(this).html();
    $(this).html('<i class="fa fa-spinner fa-spin"></i>');
    var stu_id = $('#stu_id').val();
    
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
      $('#verify_stu').html(oldtext);
      if(response.state){
        $('#fullname').val(response.full_name);
        $('#email').val(response.email);
      }else{
        alert(response.msg);
        return;
      }
    });

  });