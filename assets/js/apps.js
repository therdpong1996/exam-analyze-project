function readArticle(atid) {
    $("html, body").animate({
        scrollTop: 0
    }, 300);
    window.location.hash = '#' + atid;
    $('#content-rows').fadeOut(200);
    var index = findChartIndex(atid);
    setTimeout(() => {
        $('#content-rows').html('<button class="btn btn-info" onclick="initialApp()"><i class="fa fa-arrow-left"></i> กลับ</button><div class="card shadow mt-3"><div class="card-header"><h2 class="mb-0">' + postLdata[index].title + '</h2></div><div class="card-body">' + postLdata[index].content + '</div><div class="card-footer"><div class="row"><div class="col-6"></div><div class="col-6 text-right"><small>โดย: ' + postLdata[index].full_name + '</small></div></div></div></div>')
        $('#content-rows').fadeIn(200);
    }, 200);
}

function findChartIndex(articleid) {
    var index = 0;
    for (x in postLdata) {
        if (postLdata[x].atid == articleid) {
            return index;
        } else {
            index++;
        }
    }
}

function initialApp() {
    removeHash();
    $("html, body").animate({
        scrollTop: 0
    }, 300);
    $('#content-rows').hide();
    $('#content-rows').html('');
    for (x in postLdata) {
        $('#content-rows').append('<div class="card shadow mb-5"><div class="card-header"><h2 class="mb-0">' + postLdata[x].title + '</h2></div><div class="card-body">' + strip_html_tags(postLdata[x].content).substring(0, 1000) + '...</div><div class="card-footer"><div class="row"><div class="col-6"><button class="btn btn-primary" onclick="readArticle(' + postLdata[x].atid + ')">อ่านเพิ่มเติม</button></div><div class="col-6 text-right"><small>โดย: ' + postLdata[x].full_name + '</small></div></div></div></div>')
    }
    $('#content-rows').fadeIn(200);
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