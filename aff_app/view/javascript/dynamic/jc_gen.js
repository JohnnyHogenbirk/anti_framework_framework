function jc_gen() {
    var obj_element = document.getElementById('action');
    switch (obj_element.value) {
        case '1':
        case '2':
        case '3':
            document.getElementById('row_head_text').style.display = 'block';
            document.getElementById('row_items').style.display = 'block';
            if(obj_element.value == '1'){
                /* datasource only important for view */
                document.getElementById('row_datasource').style.display = 'block';
            }else {
                /* overview has always datasource and combi also */
                document.getElementById('row_datasource').style.display = 'none';
            }
            if(obj_element.value == '3') {
                /* standard buttons when generate overview and two views */
                document.getElementById('row_a_buttons').style.display = 'none';
            }else{
                document.getElementById('row_a_buttons').style.display = 'block';
            }
            document.getElementById('row_model_get_type').style.display = 'none';
            document.getElementById('row_model_get_source').style.display = 'none';
            break;
        case '4':
            document.getElementById('row_head_text').style.display = 'none';
            document.getElementById('row_items').style.display = 'none';
            document.getElementById('row_datasource').style.display = 'none';
            document.getElementById('row_a_buttons').style.display = 'none';
            document.getElementById('row_model_get_type').style.display = 'block';
            document.getElementById('row_model_get_source').style.display = 'block';
            break;
        case '5':
            document.getElementById('row_head_text').style.display = 'none';
            document.getElementById('row_items').style.display = 'none';
            document.getElementById('row_datasource').style.display = 'none';
            document.getElementById('row_a_buttons').style.display = 'block';
            document.getElementById('row_model_get_type').style.display = 'none';
            document.getElementById('row_model_get_source').style.display = 'none';
            break;
    }
}