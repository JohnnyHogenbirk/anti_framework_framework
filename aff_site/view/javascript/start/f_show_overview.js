function f_show_overview(view, data) {
    if (view == undefined) {
        return '';
    }
    var content = '';
    var r, k, this_data;
    var datasetname = '';
    var this_display = new Array();
    var this_link = new Array();
    var this_width = new Array();
    content += '<table cellspacing="0px" cellpadding="0px"><tr>';
    for (k = 0; k < view.length; k++) {
        if (view[k]['type'] == 'column') {
            this_link[k] = 'no';
            this_display[k] = view[k]['display'];
            // special
            if(this_display[k] == 'table-cell_link'){
                this_link[k] = 'yes';
                this_display[k] = 'table-cell';
            }
            this_width[k] = '';
            if (view[k]['width'] != '') {
                this_width[k] = 'width:' + view[k]['width'] + 'px;';
            }
            content += '<td class="overview_th" style="display:' + this_display[k] + ';' + this_width[k] + '">' + view[k]['title'] + '</td>';
            if (view[k]['dataset_name'] != '') {
                datasetname = view[k]['dataset_name'];
            }
        }
    }
    if (datasetname == '') {
        return '';
    }
    if (data[datasetname] == undefined) {
        return '';
    }
    for (r = 0; r < data[datasetname].length; r++) {
        content += '<tr>';
        for (k = 0; k < view.length; k++) {
            if (view[k]['type'] == 'column') {
                this_data = '';
                if (view[k]['element'] == 'field') {
                    this_data = data[datasetname][r][view[k]['dataset_field']];
                }
                if (view[k]['element'] == 'next_view') {
                    this_data = '<span class="overview_change" onclick=f_get_content(\'' + view[k]['action'] + '\',\'' + data[datasetname][r]['id'] + '\')>Change</span>';
                }
                if(this_link[k] == 'yes'){
                    this_data = '<a href="' + this_data + '" target="_blank">' + view[k]['title'] + '</a>';
                }
                content += '<td class="overview_td" style="display:' + this_display[k] + ';' + this_width[k] + '">' + this_data + '</td>';
            }
        }
        content += '</tr>';
    }
    content += '</table>';
    return content;
}

