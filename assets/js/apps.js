firebase.initializeApp({
    apiKey: "AIzaSyB9qKRcxJkjhJAcuKLErhCF15o0ZZkEfNQ",
    authDomain: "cat-project-rmutl.firebaseapp.com",
    projectId: "cat-project-rmutl",
})
var db = firebase.firestore();
db.settings({
    timestampsInSnapshots: true
});
db.enablePersistence();

function readArticle(atid) {
    window.location.hash = '#' + atid;
    $('#content-rows').fadeOut(200);
    setTimeout(() => {
        db.collection("articles").doc(atid.toString()).get().then((querySnapshot) => {
            $('#content-rows').html('<button class="btn btn-info" onclick="initialApp()"><i class="fa fa-arrow-left"></i> กลับ</button><div class="card shadow mt-3"><div class="card-header"><h2 class="mb-0">' + querySnapshot.data().title + '</h2></div><div class="card-body">' + querySnapshot.data().content + '</div><div class="card-footer"><div class="row"><div class="col-6"></div><div class="col-6 text-right"><small>โดย: ' + querySnapshot.data().auther + '</small></div></div></div></div>')
            $('#content-rows').fadeIn(200);
        })
    }, 200);
}

function initialApp() {
    removeHash();
    $('#content-rows').hide();
    $('#content-rows').html('');
    db.collection("articles").get().then((querySnapshot) => {
        querySnapshot.forEach((doc) => {
            $('#content-rows').append('<div class="card shadow mb-5"><div class="card-header"><h2 class="mb-0">' + doc.data().title + '</h2></div><div class="card-body">' + strip_html_tags(doc.data().content).substring(0, 1000) + '...</div><div class="card-footer"><div class="row"><div class="col-6"><button class="btn btn-success" onclick="readArticle(' + doc.data().atid + ')">อ่านเพิ่มเติม</button></div><div class="col-6 text-right"><small>โดย: ' + doc.data().auther + '</small></div></div></div></div>')
        });
        $('#content-rows').fadeIn(300);
    });
}

if (window.location.hash == "#" || window.location.hash === "") {
    initialApp()
} else {
    readArticle(window.location.hash.replace("#", ""))
}

function removeHash() {
    var scrollV, scrollH, loc = window.location;
    if ("pushState" in history)
        history.pushState("", document.title, loc.pathname + loc.search);
    else {
        // Prevent scrolling by storing the page's current scroll offset
        scrollV = document.body.scrollTop;
        scrollH = document.body.scrollLeft;

        loc.hash = "";

        // Restore the scroll offset, should be flicker free
        document.body.scrollTop = scrollV;
        document.body.scrollLeft = scrollH;
    }
}

function strip_html_tags(str) {
    if ((str === null) || (str === ''))
        return false;
    else
        str = str.toString();
    return str.replace(/<[^>]*>/g, '');
}