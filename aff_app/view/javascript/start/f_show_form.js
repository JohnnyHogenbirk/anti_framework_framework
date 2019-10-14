function f_show_form(view_name, view, data) {
    var content = '';
    var r, i;
    var this_data, this_data_ddl, ds_name, ds_value, ds_show;
    var this_display, this_blur, this_width;
    content += '<form id="form" enctype="multipart/form-data" accept-charset="utf-8">';
    for (r = 0; r < view.length; r++) {
        if (view[r]['type'] == 'row') {
            this_display = view[r]['display'];
            // special, inline-blok on next row
            if (this_display == 'inline-next') {
                this_display = 'inline-block';
                content += '<br>';
            }
            this_blur = view[r]['blur'];
            if (this_blur != "") {
                this_blur = this_blur.substring(0, this_blur.length - 3) + '()';
            }
            this_width = '';
            if (view[r]['width'] != '') {
                this_width = 'width:' + view[r]['width'] + 'px;';
            }
            switch (view[r]['element']) {
                case 'head_text':
                    content += '<div class="head_text" style="display:' + this_display + ';">' + view[r]['title'] + '</div>';
                    break;
                case 'lable_text':
                    this_data = f_show_form_check_exist(data, view[r]['dataset_name'], 0, view[r]['dataset_field']);
                    content += f_show_form_start_row(this_display, view[r]['row_id'], view[r]['title']);
                    content += '<span class="lable_value">' + this_data + '</span></div>';
                    content += '</div>';
                    break;
                case 'lable_value':
                    this_data = f_show_form_check_exist(data, view[r]['dataset_name'], 0, view[r]['dataset_field']);
                    content += f_show_form_start_row(this_display, view[r]['row_id'], view[r]['title']);
                    content += '<span class="lable_value">' + this_data + '</span></div>';
                    content += '<input type="hidden" id="' + view[r]['row_id'] + '" name="' + view[r]['row_id'] + '" value="' + this_data + '">';
                    content += '</div>';
                    break;
                case 'ddl':
                    this_data = f_show_form_check_exist(data, view[r]['dataset_name'], 0, view[r]['dataset_field']);
                    this_data_ddl = f_show_form_check_exist(data, view[r]['ddl_ds_name'], 0, view[r]['ddl_ds_value']);
                    content += f_show_form_start_row(this_display, view[r]['row_id'], view[r]['title']);
                    if (this_data_ddl == '') {
                        content += '<span class="lable_value">' + this_data + '</span>';
                    } else {
                        content += '<select class="select" style="' + this_width + '" onchange="' + this_blur + '" id="' + view[r]['row_id'] + '" name="' + view[r]['row_id'] + '">';
                        ds_name = view[r]['ddl_ds_name'];
                        ds_value = view[r]['ddl_ds_value'];
                        ds_show = view[r]['ddl_ds_show'];
                        for (i = 0; i < data[ds_name].length; i++) {
                            if (data[ds_name][i][ds_value] == this_data) {
                                content += '<option selected value="' + data[ds_name][i][ds_value] + '">' + data[ds_name][i][ds_show] + '</option>';
                            } else {
                                content += '<option value="' + data[ds_name][i][ds_value] + '">' + data[ds_name][i][ds_show] + '</option>';
                            }
                        }
                        content += '</select>';
                    }
                    content += '</div>';
                    break;
                case 'textarea':
                    this_data = f_show_form_check_exist(data, view[r]['dataset_name'], 0, view[r]['dataset_field']);
                    content += f_show_form_start_row(this_display, view[r]['row_id'], view[r]['title']);
                    content += '<textarea class="textarea" style="' + this_width + '" onblur="' + this_blur + '" id="' + view[r]['row_id'] + '" name="' + view[r]['row_id'] + '">' + this_data + '</textarea></div>';
                    break;
                case 'textbox':
                    this_data = f_show_form_check_exist(data, view[r]['dataset_name'], 0, view[r]['dataset_field']);
                    content += f_show_form_start_row(this_display, view[r]['row_id'], view[r]['title']);
                    content += '<input class="textbox" style="' + this_width + '" type="text" onblur="' + this_blur + '" id="' + view[r]['row_id'] + '" name="' + view[r]['row_id'] + '" value="' + this_data + '"></div>';
                    break;
                case 'password':
                    content += f_show_form_start_row(this_display, view[r]['row_id'], view[r]['title']);
                    content += '<input class="textbox" type="password" style="' + this_width + '" onblur="' + this_blur + '" id="' + view[r]['row_id'] + '" name="' + view[r]['row_id'] + '"></div>';
                    break;
                case 'hidden':
                    this_data = f_show_form_check_exist(data, view[r]['dataset_name'], 0, view[r]['dataset_field']);
                    content += '<input type="hidden" id="' + view[r]['row_id'] + '" name="' + view[r]['row_id'] + '" value="' + this_data + '"></div>';
                    break;
                case 'button':
                    // model_name, button_id
                    content += '<div class="form_button" style="display:' + this_display + '" id="row_' + view[r]['row_id'] + '" >';
                    content += '<div class="button" style="' + this_width + '" onclick="f_remove_alert();f_form_submit();f_send_data(\'' + view_name + '\', \'' + view[r]['row_id'] + '\')">' + view[r]['title'] + '</div></div>';
                    break;
            }
        }
    }
    content += '<input type="hidden" id="f_view_name" name="f_view_name">';
    content += '<input type="hidden" id="f_button_id" name="f_button_id">';
    content += '</form>';

    return content;
}

function f_show_form_start_row(this_display, row_id, title) {
    var content = '<div class="form_row" style="display:' + this_display + '" id="row_' + row_id + '" >';
    content += '<span class="row_title" id="row_title_' + row_id + '" >' + title + '</span><br>';
    return content;

}

function f_show_form_check_exist(data, index1, index2, index3) {
    var result = '';
    if (data[index1] != undefined) {
        if (data[index1][index2] != undefined) {
            if (data[index1][index2][index3] != undefined) {
                result = data[index1][index2][index3];
            }
        }
    }
    return result;
}
