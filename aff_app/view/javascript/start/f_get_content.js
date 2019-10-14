// get new page or data
function f_get_content(view_name, id) {
    // get structure and data from server
    var oReq = new XMLHttpRequest();
    oReq.onerror = f_get_content_error;
    oReq.onload = f_get_content_catch_reply;
    var vars = 'api.php?view_name=' + view_name;
    if (id != '') {
        vars += '&id=' + id;
    }
    oReq.open("get", vars, true);
    oReq.send(null);
}

function f_get_content_error() {
    // error handling, pretty basic
    alert("Error, do you have a good internet connection? Try again.");
}

function f_get_content_catch_reply() {
    // catch reply by server
    var response_text, el_alert_content, view_name, data, view, content;
    try {
        response_text = JSON.parse(this.responseText);
    } catch (e) {
        document.getElementById('el_article_content').innerHTML = e + '<br><br>' + this.responseText;
        return;
    }

    // check if a alert must be given and give if not empty
    f_alert(response_text['alert']);

    // show form and overview (if there is)
    view_name = response_text['view_name'];
    view = response_text['view'];
    data = response_text['data'];

    content = f_show_form(view_name, view, data);
    content += f_show_overview(view, data);
    // because of fixed en height combi some breaks, to show content good
    document.getElementById('el_article_content').innerHTML = content + '<br><br><br><br>';

    // var js
    f_add_js_style(response_text['javascript'], 'script');

    // check start with js
    var r;
    for (r = 0; r < view.length; r++) {
        if (view[r]['start'] != '') {
            f_add_js_style(view[r]['start'].substring(0, view[r]['start'].length - 3) + '()', 'script');
        }
    }
}
