function jc_view_justify_element() {
    var obj_element = document.getElementById('element');
    switch (obj_element.value) {
        case 'ddl':
            document.getElementById('row_dataset_name').style.display = 'inline-block';
            document.getElementById('row_dataset_field').style.display = 'inline-block';
            document.getElementById('row_ddl_ds_name').style.display = 'inline-block';
            document.getElementById('row_ddl_ds_value').style.display = 'inline-block';
            document.getElementById('row_ddl_ds_show').style.display = 'inline-block';
            document.getElementById('row_ddl_ds_blank').style.display = 'inline-block';
            document.getElementById('row_check').style.display = 'inline-block';
            document.getElementById('row_blur').style.display = 'inline-block';
            document.getElementById('row_action').style.display = 'none';
            break;
        case 'button':
            document.getElementById('row_dataset_name').style.display = 'none';
            document.getElementById('row_dataset_field').style.display = 'none';
            document.getElementById('row_ddl_ds_name').style.display = 'none';
            document.getElementById('row_ddl_ds_value').style.display = 'none';
            document.getElementById('row_ddl_ds_show').style.display = 'none';
            document.getElementById('row_ddl_ds_blank').style.display = 'none';
            document.getElementById('row_check').style.display = 'none';
            document.getElementById('row_blur').style.display = 'none';
            document.getElementById('row_action').style.display = 'inline-block';
            break;
        default:
            document.getElementById('row_dataset_name').style.display = 'inline-block';
            document.getElementById('row_dataset_field').style.display = 'inline-block';
            document.getElementById('row_ddl_ds_name').style.display = 'none';
            document.getElementById('row_ddl_ds_value').style.display = 'none';
            document.getElementById('row_ddl_ds_show').style.display = 'none';
            document.getElementById('row_ddl_ds_blank').style.display = 'none';
            document.getElementById('row_check').style.display = 'inline-block';
            document.getElementById('row_blur').style.display = 'inline-block';
            document.getElementById('row_action').style.display = 'none';
    }
}