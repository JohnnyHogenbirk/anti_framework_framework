function f_form_submit(){
    var form_submit = true;
    var alert_text_form_submit = '';
    var alert_type_form_submit = '';
    // checks

    if(alert_text_form_submit != '') {
        f_alert({text: alert_text_form_submit, type: alert_type_form_submit});
    }

    return form_submit;
}