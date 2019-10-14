function f_alert(alert_data) {
    if (alert_data['text'] != undefined) {
        if (alert_data['text'] != "") {
            var class_this = 'el_alert_ok';
            if(alert_data['type'] != undefined){
                class_this = 'el_alert_' + alert_data['type'];
            }
            document.getElementById('el_article_alert').classList.remove("el_alert_ok");
            document.getElementById('el_article_alert').classList.remove("el_alert_attent");
            document.getElementById('el_article_alert').classList.remove("el_alert_warning");
            document.getElementById('el_article_alert').classList.add(class_this);
            document.getElementById('el_article_alert').innerHTML = alert_data['text'];
            document.getElementById('el_article_alert').style.height = 'auto';
            document.getElementById('el_article_alert').style.padding = '2px';
        }
    }
}

function f_remove_alert(){
    document.getElementById('el_article_alert').style.height = '0px';
    document.getElementById('el_article_alert').style.padding = '0px';
}
