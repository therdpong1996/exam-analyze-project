
    $('#add-subject-form').on('submit', function(){
      var sData = $(this).serialize();
      $.ajax({
        type: "POST",
        url: weburl + "ajax/subject",
        data: sData,
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
              window.location.href = weburl + "subject/";
            }
          });
        }else{
          swal(
            'SORRY',
            response.msg,
            'error'
          );
        }
      });
    });

    $('#add-session-form').on('submit', function(){
      var sData = $(this).serialize();
      $.ajax({
        type: "POST",
        url: weburl + "ajax/session",
        data: sData,
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
              window.location.href = weburl + "session/";
            }
          });
        }else{
          swal(
            'SORRY',
            response.msg,
            'error'
          );
        }
      });
    });

    $('#add-examination-form').on('submit', function(){
      var sData = $(this).serialize();
      $.ajax({
        type: "POST",
        url: weburl + "ajax/examination",
        data: sData,
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
              window.location.href = weburl + "examination/";
            }
          });
        }else{
          swal(
            'SORRY',
            response.msg,
            'error'
          );
        }
      });
    });

    $('#edit-subject-form').on('submit', function(){
      var sData = $(this).serialize();
      $.ajax({
        type: "POST",
        url: weburl + "ajax/subject",
        data: sData,
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
              window.location.href = weburl + "subject/";
            }
          });
        }else{
          swal(
            'SORRY',
            response.msg,
            'error'
          );
        }
      });
    });

    $('#edit-session-form').on('submit', function(){
      var sData = $(this).serialize();
      $.ajax({
        type: "POST",
        url: weburl + "ajax/session",
        data: sData,
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
              window.location.href = weburl + "session/";
            }
          });
        }else{
          swal(
            'SORRY',
            response.msg,
            'error'
          );
        }
      });
    });

    $('#edit-examination-form').on('submit', function(){
      var sData = $(this).serialize();
      $.ajax({
        type: "POST",
        url: weburl + "ajax/examination",
        data: sData,
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
              window.location.href = weburl + "examination/";
            }
          });
        }else{
          swal(
            'SORRY',
            response.msg,
            'error'
          );
        }
      });
    });

    $('#exam-form').on('submit', function(){
      checked = $("input[type=checkbox]:checked").length;

      if(!checked) {
        alert("You must check at least one checkbox.");
        return false;
      }
      
      var sData = $(this).serialize();
      $.ajax({
        type: "POST",
        url: weburl + "ajax/examination_qa",
        data: sData,
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
              window.location.href = window.location.href;
            }
          });
        }else{
          swal(
            'SORRY',
            response.msg,
            'error'
          );
        }
      });
    });

    delete_exam = function(qa_id){
      if(qa_id == 0){
        return;
      }

      swal({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then(function(result){
        if (result.value) {
          $.ajax({
            type: "POST",
            url: weburl + "ajax/examination_qa",
            data: {qa_id: qa_id, action: "delete"},
            dataType: "json"
          })
          .done(function(response){
            if(response.state){
              window.location.href = window.location.href;
            }else{
              swal(
                'ERROR',
                response.msg,
                'error'
              );
            }
          });
        }
      });
    };

    subject_delete = function(subject_id){
      swal({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then(function(result){
        if (result.value) {
          $.ajax({
            type: "POST",
            url: weburl + "ajax/subject",
            data: {subject_id: subject_id, action: "delete"},
            dataType: "json"
          })
          .done(function(response){
            if(response.state){
              $('#subject-'+subject_id).remove();
            }else{
              swal(
                'ERROR',
                response.msg,
                'error'
              );
            }
          });
        }
      });
    };

    session_delete = function(session_id){
      swal({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then(function(result){
        if (result.value) {
          $.ajax({
            type: "POST",
            url: weburl + "ajax/session",
            data: {session_id: session_id, action: "delete"},
            dataType: "json"
          })
          .done(function(response){
            if(response.state){
              $('#session-'+session_id).remove();
            }else{
              swal(
                'ERROR',
                response.msg,
                'error'
              );
            }
          });
        }
      });
    };

    examination_delete = function(examination_id){
      swal({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then(function(result){
        if (result.value) {
          $.ajax({
            type: "POST",
            url: weburl + "ajax/examination",
            data: {examination_id: examination_id, action: "delete"},
            dataType: "json"
          })
          .done(function(response){
            if(response.state){
              $('#examination-'+examination_id).remove();
            }else{
              swal(
                'ERROR',
                response.msg,
                'error'
              );
            }
          });
        }
      });
    };