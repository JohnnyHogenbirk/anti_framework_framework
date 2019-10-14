function jc_view_justify_type(){
    var obj_type = document.getElementById('type');
    var obj_element = document.getElementById('element');
    var obj_display = document.getElementById('display');
    switch(obj_type.value){
        case 'row':
            obj_element.options[0].hidden = false;
            obj_element.options[1].hidden = false;
            obj_element.options[2].hidden = false;
            obj_element.options[3].hidden = false;
            obj_element.options[4].hidden = false;
            obj_element.options[5].hidden = false;
            obj_element.options[6].hidden = false;
            obj_element.options[7].hidden = false;
            obj_element.options[8].hidden = true;
            obj_element.options[9].hidden = true;
            if((obj_element.value == 'field') || (obj_element.value == 'next_view')){
                obj_element.value = 'textbox';
            }
            obj_display.options[0].hidden = false;
            obj_display.options[1].hidden = false;
            obj_display.options[2].hidden = false;
            obj_display.options[3].hidden = true;
            obj_display.value = 'block';
            show_all();
            document.getElementById('row_check').style.display = 'inline-block';
            document.getElementById('row_blur').style.display = 'inline-block';
            break;
        case 'column':
            obj_element.options[0].hidden = true;
            obj_element.options[1].hidden = true;
            obj_element.options[2].hidden = true;
            obj_element.options[3].hidden = true;
            obj_element.options[4].hidden = true;
            obj_element.options[5].hidden = true;
            obj_element.options[6].hidden = true;
            obj_element.options[7].hidden = true;
            obj_element.options[8].hidden = false;
            obj_element.options[9].hidden = false;
            if((obj_element.value != 'field') || (obj_element.value != 'next_view')){
                obj_element.value = 'field';
            }
            obj_display.options[0].hidden = true;
            obj_display.options[1].hidden = true;
            obj_display.options[2].hidden = true;
            obj_display.options[3].hidden = false;
            obj_display.value = 'table-cell';
            show_all();
            document.getElementById('row_check').style.display = 'none';
            document.getElementById('row_blur').style.display = 'none';
            break;
        case 'js':
            document.getElementById('row_element').style.display = 'none';
            document.getElementById('row_display').style.display = 'none';
            document.getElementById('row_row_id').style.display = 'none';
            document.getElementById('row_title').style.display = 'none';
            document.getElementById('row_dataset_name').style.display = 'none';
            document.getElementById('row_dataset_field').style.display = 'none';
            document.getElementById('row_ddl_ds_name').style.display = 'none';
            document.getElementById('row_ddl_ds_value').style.display = 'none';
            document.getElementById('row_ddl_ds_show').style.display = 'none';
            document.getElementById('row_ddl_ds_blank').style.display = 'none';
            document.getElementById('row_check').style.display = 'none';
            document.getElementById('row_blur').style.display = 'none';
            document.getElementById('row_start').style.display = 'inline-block';
            document.getElementById('row_action').style.display = 'none';
            document.getElementById('row_width').style.display = 'none';
            break;
    }
}
function show_all(){
    document.getElementById('row_element').style.display = 'inline-block';
    document.getElementById('row_display').style.display = 'inline-block';
    document.getElementById('row_row_id').style.display = 'inline-block';
    document.getElementById('row_title').style.display = 'inline-block';
    document.getElementById('row_dataset_name').style.display = 'inline-block';
    document.getElementById('row_dataset_field').style.display = 'inline-block';
    document.getElementById('row_start').style.display = 'none';
    document.getElementById('row_width').style.display = 'inline-block';
    document.getElementById('row_action').style.display = 'inline-block';
    jc_view_justify_element();
}