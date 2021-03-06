$('#add-form-article').on('submit', function () {
  var oldtext = $('#add-article-btn').html();
  $('#add-article-btn').html('<i class="fa fa-spinner fa-spin"></i> Process..');
  var sData = $(this).serialize();
  $.ajax({
      type: "POST",
      url: weburl + "ajax/article",
      data: sData,
      dataType: "json"
    })
    .done(function (response) {
      if (response.state) {
        swal({
          title: 'SUCCESS',
          text: response.msg,
          type: 'success',
          showCancelButton: false,
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'Yes'
        }).then(function (result) {
          if (result.value) {
            window.location.href = window.location.href;
          }
        });
      } else {
        $('#add-article-btn').html(oldtext);
        swal(
          'SORRY',
          response.msg,
          'error'
        );
      }
    });
});

asavedraft = function () {
  $('input#article_type').val('draft');
  var oldtext = $('#save-article-with-draft-btn').html();
  $('#save-article-with-draft-btn').html('<i class="fa fa-spinner fa-spin"></i> Process..');
  var sData = $('form#add-form-article').serialize();
  $.ajax({
      type: "POST",
      url: weburl + "ajax/article",
      data: sData,
      dataType: "json"
    })
    .done(function (response) {
      if (response.state) {
        swal({
          title: 'SUCCESS',
          text: response.msg,
          type: 'success',
          showCancelButton: false,
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'Yes'
        }).then(function (result) {
          if (result.value) {
            window.location.href = window.location.href;
          }
        });
      } else {
        $('#save-article-with-draft-btn').html(oldtext);
        swal(
          'SORRY',
          response.msg,
          'error'
        );
      }
    });
}

$('#add-subject-form').on('submit', function () {
  var oldtext = $('#subject-add').html();
  $('#subject-add').html('<i class="fa fa-spinner fa-spin"></i> Process..');
  var sData = $(this).serialize();
  $.ajax({
      type: "POST",
      url: weburl + "ajax/subject",
      data: sData,
      dataType: "json"
    })
    .done(function (response) {
      if (response.state) {
        swal({
          title: 'SUCCESS',
          text: response.msg,
          type: 'success',
          showCancelButton: false,
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'Yes'
        }).then(function (result) {
          if (result.value) {
            window.location.href = weburl + "subject/";
          }
        });
      } else {
        $('#subject-add').html(oldtext);
        swal(
          'SORRY',
          response.msg,
          'error'
        );
      }
    });
});

$('#add-session-form').on('submit', function () {
  var oldtext = $('#session-add').html();
  $('#session-add').html('<i class="fa fa-spinner fa-spin"></i> Process..');

  var imadt = $('#adaptimport').val();

  if (imadt == 'none') {
    alert('กรุณาเลือกข้อมูลการ Import');
    return;
  }
  var sData = $(this).serialize();
  $.ajax({
      type: "POST",
      url: weburl + "ajax/session",
      data: sData,
      dataType: "json"
    })
    .done(function (response) {
      if (response.state) {
        swal({
          title: 'SUCCESS',
          text: response.msg,
          type: 'success',
          showCancelButton: false,
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'Yes'
        }).then(function (result) {
          if (result.value) {
            window.location.href = weburl + "session/";
          }
        });
      } else {
        $('#session-add').html(oldtext);
        swal(
          'SORRY',
          response.msg,
          'error'
        );
      }
    });
});

$('#add-examination-form').on('submit', function () {
  var oldtext = $('#exam-add').html();
  $('#exam-add').html('<i class="fa fa-spinner fa-spin"></i> Process..');
  var sData = $(this).serialize();
  $.ajax({
      type: "POST",
      url: weburl + "ajax/examination",
      data: sData,
      dataType: "json"
    })
    .done(function (response) {
      if (response.state) {
        swal({
          title: 'SUCCESS',
          text: response.msg,
          type: 'success',
          showCancelButton: false,
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'Yes'
        }).then(function (result) {
          if (result.value) {
            window.location.href = weburl + "examination/";
          }
        });
      } else {
        $('#exam-add').html(oldtext);
        swal(
          'SORRY',
          response.msg,
          'error'
        );
      }
    });
});

$('#edit-form-article').on('submit', function () {
  var oldtext = $('#save-article-btn').html();
  $('#save-article-btn').html('<i class="fa fa-spinner fa-spin"></i> Process..');
  var sData = $(this).serialize();
  $.ajax({
      type: "POST",
      url: weburl + "ajax/article",
      data: sData,
      dataType: "json"
    })
    .done(function (response) {
      if (response.state) {
        swal({
          title: 'SUCCESS',
          text: response.msg,
          type: 'success',
          showCancelButton: false,
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'Yes'
        }).then(function (result) {
          if (result.value) {
            window.location.href = window.location.href;
          }
        });
      } else {
        $('#save-article-btn').html(oldtext);
        swal(
          'SORRY',
          response.msg,
          'error'
        );
      }
    });
});

esavedraft = function () {
  $('input#article_type').val('draft');
  var oldtext = $('#save-article-with-draft-btn').html();
  $('#save-article-with-draft-btn').html('<i class="fa fa-spinner fa-spin"></i> Process..');
  var sData = $('form#edit-form-article').serialize();
  $.ajax({
      type: "POST",
      url: weburl + "ajax/article",
      data: sData,
      dataType: "json"
    })
    .done(function (response) {
      if (response.state) {
        swal({
          title: 'SUCCESS',
          text: response.msg,
          type: 'success',
          showCancelButton: false,
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'Yes'
        }).then(function (result) {
          if (result.value) {
            window.location.href = window.location.href;
          }
        });
      } else {
        $('#save-article-with-draft-btn').html(oldtext);
        swal(
          'SORRY',
          response.msg,
          'error'
        );
      }
    });
}

$('#edit-subject-form').on('submit', function () {
  var oldtext = $('#subject-save').html();
  $('#subject-save').html('<i class="fa fa-spinner fa-spin"></i> Process..');
  var sData = $(this).serialize();
  $.ajax({
      type: "POST",
      url: weburl + "ajax/subject",
      data: sData,
      dataType: "json"
    })
    .done(function (response) {
      if (response.state) {
        swal({
          title: 'SUCCESS',
          text: response.msg,
          type: 'success',
          showCancelButton: false,
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'Yes'
        }).then(function (result) {
          if (result.value) {
            window.location.href = weburl + "subject/";
          }
        });
      } else {
        $('#subject-save').html(oldtext);
        swal(
          'SORRY',
          response.msg,
          'error'
        );
      }
    });
});

$('#edit-session-form').on('submit', function () {
  var oldtext = $('#session-save').html();
  $('#session-save').html('<i class="fa fa-spinner fa-spin"></i> Process..');
  var sData = $(this).serialize();
  $.ajax({
      type: "POST",
      url: weburl + "ajax/session",
      data: sData,
      dataType: "json"
    })
    .done(function (response) {
      if (response.state) {
        swal({
          title: 'SUCCESS',
          text: response.msg,
          type: 'success',
          showCancelButton: false,
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'Yes'
        }).then(function (result) {
          if (result.value) {
            window.location.href = weburl + "session/";
          }
        });
      } else {
        $('#session-save').html(oldtext);
        swal(
          'SORRY',
          response.msg,
          'error'
        );
      }
    });
});

$('#edit-examination-form').on('submit', function () {
  var oldtext = $('#exam-save').html();
  $('#exam-save').html('<i class="fa fa-spinner fa-spin"></i> Process..');
  var sData = $(this).serialize();
  $.ajax({
      type: "POST",
      url: weburl + "ajax/examination",
      data: sData,
      dataType: "json"
    })
    .done(function (response) {
      if (response.state) {
        swal({
          title: 'SUCCESS',
          text: response.msg,
          type: 'success',
          showCancelButton: false,
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'Yes'
        }).then(function (result) {
          if (result.value) {
            window.location.href = weburl + "examination/";
          }
        });
      } else {
        $('#exam-save').html(oldtext);
        swal(
          'SORRY',
          response.msg,
          'error'
        );
      }
    });
});

$('#exam-form').on('submit', function () {
  var oldtext = $('#exam-add').html();
  $('#exam-add').html('<i class="fa fa-spinner fa-spin"></i> Process..');
  checked = $("input[type=checkbox]:checked").length;
  if (checked <= 1) {
    alert("You must check at least one checkbox.");
    return;
  }

  var sData = $(this).serialize();
  $.ajax({
      type: "POST",
      url: weburl + "ajax/examination_qa",
      data: sData,
      dataType: "json"
    })
    .done(function (response) {
      if (response.state) {
        swal({
          title: 'SUCCESS',
          text: response.msg,
          type: 'success',
          showCancelButton: false,
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'Yes'
        }).then(function (result) {
          if (result.value) {
            window.location.href = weburl + 'examination/qa/?examination_id=' + response.eid + '&n=' + response.n;
          }
        });
      } else {
        $('#exam-add').html(oldtext);
        swal(
          'SORRY',
          response.msg,
          'error'
        );
      }
    });

});

delete_exam = function (qa_id) {
  if (qa_id == 0) {
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
  }).then(function (result) {
    if (result.value) {
      var oldtext = $('#delete-btn').html();
      $('#delete-btn').html('<i class="fa fa-spinner fa-spin"></i>');
      $.ajax({
          type: "POST",
          url: weburl + "ajax/examination_qa",
          data: {
            qa_id: qa_id,
            action: "delete"
          },
          dataType: "json"
        })
        .done(function (response) {
          if (response.state) {
            window.location.href = weburl + 'examination/qa/?examination_id=' + examination_id;
          } else {
            $('#delete-btn').html(oldtext);
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

article_delete = function (atid) {
  swal({
    title: 'Are you sure?',
    text: "You won't be able to revert this!",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, delete it!'
  }).then(function (result) {
    if (result.value) {
      var oldtext = $('#delete-btn' + atid).html();
      $('#delete-btn' + atid).html('<i class="fa fa-spinner fa-spin"></i>');
      $.ajax({
          type: "POST",
          url: weburl + "ajax/article",
          data: {
            atid: atid,
            action: "delete"
          },
          dataType: "json"
        })
        .done(function (response) {
          if (response.state) {
            $('#article-' + atid).remove();
          } else {
            $('#delete-btn' + atid).html(oldtext);
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

subject_delete = function (subject_id) {
  swal({
    title: 'Are you sure?',
    text: "You won't be able to revert this!",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, delete it!'
  }).then(function (result) {
    if (result.value) {
      var oldtext = $('#delete-btn' + subject_id).html();
      $('#delete-btn' + subject_id).html('<i class="fa fa-spinner fa-spin"></i>');
      $.ajax({
          type: "POST",
          url: weburl + "ajax/subject",
          data: {
            subject_id: subject_id,
            action: "delete"
          },
          dataType: "json"
        })
        .done(function (response) {
          if (response.state) {
            $('#subject-' + subject_id).remove();
          } else {
            $('#delete-btn' + subject_id).html(oldtext);
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

session_delete = function (session_id) {
  swal({
    title: 'Are you sure?',
    text: "You won't be able to revert this!",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, delete it!'
  }).then(function (result) {
    if (result.value) {
      var oldtext = $('#delete-btn' + session_id).html();
      $('#delete-btn' + session_id).html('<i class="fa fa-spinner fa-spin"></i>');
      $.ajax({
          type: "POST",
          url: weburl + "ajax/session",
          data: {
            session_id: session_id,
            action: "delete"
          },
          dataType: "json"
        })
        .done(function (response) {
          if (response.state) {
            $('#session-' + session_id).remove();
          } else {
            $('#delete-btn' + session_id).html(oldtext);
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

examination_delete = function (examination_id) {
  swal({
    title: 'Are you sure?',
    text: "You won't be able to revert this!",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, delete it!'
  }).then(function (result) {
    if (result.value) {
      var oldtext = $('#delete-btn' + examination_id).html();
      $('#delete-btn' + examination_id).html('<i class="fa fa-spinner fa-spin"></i>');
      $.ajax({
          type: "POST",
          url: weburl + "ajax/examination",
          data: {
            examination_id: examination_id,
            action: "delete"
          },
          dataType: "json"
        })
        .done(function (response) {
          if (response.state) {
            $('#examination-' + examination_id).remove();
          } else {
            $('#delete-btn' + examination_id).html(oldtext);
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

$('#session_exam').on('change', function () {
  $('#adaptimport').val(0);
  $('button.session-btn').attr("disabled", "disabled").addClass("disabled");
  $('#import-content').html('<p><i class="fa fa-spinner fa-spin"></i> Loading...</p>');
  var examid = $(this).val();
  $.ajax({
    type: "POST",
    url: weburl + "ajax/search_adaptive",
    data: {
      examid: examid
    },
    dataType: "json",
    success: function (response) {
      if (response.state) {
        $('#import-content').html(response.msg);
        $('#adap-detail').append(response.adapdetail);
        $('#ause').hide();
      } else {
        $('#adaptimport').val(0);
        $('button.session-btn').removeAttr("disabled").removeClass("disabled");
        $('#import-content').html('');
        $('#anouse').show();
        $('#ause').html('').hide();
      }
    }
  });
});

function addAdaptid(id) {
  if (id != 0) {
    $('#adaptive-number').fadeIn(200);
    $('#session_adap').removeAttr("disabled");
    $('#anouse').hide();
    $('#ause').show();
  } else {
    $('#adaptive-number').fadeOut(200);
    $('#session_adap').attr("disabled", "disabled").addClass("disabled");
    $('#anouse').show();
    $('#ause').hide();
  }
  $('#adaptimport').val(id);
  $('#import-content').html('');
  $('button.session-btn').removeAttr("disabled").removeClass("disabled");
};

function subject_signout(sid) {
  swal({
    title: 'Are you sure?',
    text: "You won't be able to revert this!",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, signout it!'
  }).then(function (result) {
    if (result.value) {
      var oldtext = $('#signout-btn' + sid).html();
      $('#signout-btn' + sid).html('<i class="fa fa-spinner fa-spin"></i>');

      $.ajax({
          type: "POST",
          url: weburl + "ajax/signout_subject",
          data: {
            subject_id: sid,
          },
          dataType: "json"
        })
        .done(function (response) {
          if (response.state) {
            $('#subject-' + sid).remove();
          } else {
            $('#signout-btn' + sid).html(oldtext);
            swal(
              'ERROR',
              response.msg,
              'error'
            );
          }
        });
    }
  });
}