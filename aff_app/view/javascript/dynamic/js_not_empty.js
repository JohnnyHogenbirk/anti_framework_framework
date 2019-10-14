if(document.getElementById('#this_id') != undefined){
    if(document.getElementById('#this_id').style.display != 'none') {
        if (document.getElementById('#this_id').value == "") {
            alert_text = 'Row #this_name must be filled';
            alert_type = 'warning';
            alert_text_form_submit += 'Row #this_name must be filled<br>';
            alert_type_form_submit = 'warning';
            form_submit = false;
        }
    }
}
