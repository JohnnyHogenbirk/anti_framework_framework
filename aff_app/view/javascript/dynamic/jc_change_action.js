function jc_change_action() {
    var obj_element = document.getElementById('action');
    switch (obj_element.value) {
        case '1':
            document.getElementById('row_column_new').style.display = 'inline-block';
            document.getElementById('row_field_value').style.display = 'inline-block';
            document.getElementById('row_action_view_add').style.display = 'inline-block';
            document.getElementById('row_action_view_del').style.display = 'none';
            document.getElementById('row_action_model_get_add').style.display = 'none';
            document.getElementById('row_action_model_get_del').style.display = 'none';
            document.getElementById('row_action_model_put_add').style.display = 'none';
            document.getElementById('row_action_model_put_del').style.display = 'none';
            break;
        case '2':
            document.getElementById('row_column_new').style.display = 'none';
            document.getElementById('row_field_value').style.display = 'none';
            document.getElementById('row_action_view_add').style.display = 'none';
            document.getElementById('row_action_view_del').style.display = 'inline-block';
            document.getElementById('row_action_model_get_add').style.display = 'none';
            document.getElementById('row_action_model_get_del').style.display = 'none';
            document.getElementById('row_action_model_put_add').style.display = 'none';
            document.getElementById('row_action_model_put_del').style.display = 'none';
            break;
        case '3':
            document.getElementById('row_column_new').style.display = 'inline-block';
            document.getElementById('row_field_value').style.display = 'inline-block';
            document.getElementById('row_action_view_add').style.display = 'none';
            document.getElementById('row_action_view_del').style.display = 'none';
            document.getElementById('row_action_model_get_add').style.display = 'inline-block';
            document.getElementById('row_action_model_get_del').style.display = 'none';
            document.getElementById('row_action_model_put_add').style.display = 'none';
            document.getElementById('row_action_model_put_del').style.display = 'none';
            break;
        case '4':
            document.getElementById('row_column_new').style.display = 'none';
            document.getElementById('row_field_value').style.display = 'none';
            document.getElementById('row_action_view_add').style.display = 'none';
            document.getElementById('row_action_view_del').style.display = 'none';
            document.getElementById('row_action_model_get_add').style.display = 'none';
            document.getElementById('row_action_model_get_del').style.display = 'inline-block';
            document.getElementById('row_action_model_put_add').style.display = 'none';
            document.getElementById('row_action_model_put_del').style.display = 'none';
            break;
        case '5':
            document.getElementById('row_column_new').style.display = 'inline-block';
            document.getElementById('row_field_value').style.display = 'inline-block';
            document.getElementById('row_action_view_add').style.display = 'none';
            document.getElementById('row_action_view_del').style.display = 'none';
            document.getElementById('row_action_model_get_add').style.display = 'none';
            document.getElementById('row_action_model_get_del').style.display = 'none';
            document.getElementById('row_action_model_put_add').style.display = 'inline-block';
            document.getElementById('row_action_model_put_del').style.display = 'none';
            break;
        case '6':
            document.getElementById('row_column_new').style.display = 'none';
            document.getElementById('row_field_value').style.display = 'none';
            document.getElementById('row_action_view_add').style.display = 'none';
            document.getElementById('row_action_view_del').style.display = 'none';
            document.getElementById('row_action_model_get_add').style.display = 'none';
            document.getElementById('row_action_model_get_del').style.display = 'none';
            document.getElementById('row_action_model_put_add').style.display = 'none';
            document.getElementById('row_action_model_put_del').style.display = 'inline-block';
            break;
        case '7':
            document.getElementById('row_column_new').style.display = 'none';
            document.getElementById('row_field_value').style.display = 'none';
            document.getElementById('row_action_view_add').style.display = 'none';
            document.getElementById('row_action_view_del').style.display = 'none';
            document.getElementById('row_action_model_get_add').style.display = 'none';
            document.getElementById('row_action_model_get_del').style.display = 'none';
            document.getElementById('row_action_model_put_add').style.display = 'none';
            document.getElementById('row_action_model_put_del').style.display = 'none';
            break;
    }
}