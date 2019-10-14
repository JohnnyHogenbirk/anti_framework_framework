function f_send_data(view_name, button_id) {
    var oReq, form_object;

    if (!f_form_submit()) {
        return false;
    }
    oReq = new XMLHttpRequest();
    oReq.onload = f_send_data_get_reply;
    oReq.onerror = f_send_data_error;
    form_object = document.getElementById('form');
    document.getElementById('f_view_name').value = view_name;
    document.getElementById('f_button_id').value = button_id;
    oReq.open("post", "api.php", true);
    oReq.send(new FormData(form_object));
}

function f_send_data_get_reply() {
    var response_text;

    try {
        response_text = JSON.parse(this.responseText);
    } catch (e) {
        document.getElementById('el_article_content').innerHTML = e + '<br><br>' + this.responseText;
        return;
    }
    // reply
    f_alert(response_text['alert']);
    // var js
    f_add_js_style(response_text['javascript'], 'script');
}

function f_send_data_error() {
    // error handling, pretty basic
    alert("Error, do you have a good internet connection? Try again.");
}
