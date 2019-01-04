$(document).ready(function () {
    $('.dataTable').DataTable();
});

setInterval(() => {
    doesConnectionExist();
}, 10000);

function doesConnectionExist() {
    var xhr = new XMLHttpRequest();
    var file = "https://www.google.co.th/favicon.ico";
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