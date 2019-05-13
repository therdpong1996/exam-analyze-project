$(document).ready(function () {
    $('.dataTable').DataTable();
});

setInterval(() => {
    doesConnectionExist();
}, 10000);

function doesConnectionExist() {
    var xhr = new XMLHttpRequest();
    var file = "https://exam-analyze.herokuapp.com/member/assets/img/brand/blue.png";
    var randomNum = Math.round(Math.random() * 10000);

    xhr.open('HEAD', file + "?rand=" + randomNum, true);
    xhr.send();

    xhr.addEventListener("readystatechange", processRequest, false);

    function processRequest(e) {
        if (xhr.readyState == 4) {
            if (xhr.status >= 200 && xhr.status < 304) {
                $('#no-connecting').fadeOut(200);
            } else {
                $('#no-connecting').fadeIn(200);
            }
        }
    }
}

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}