<?php
// call by view_functions, by a sys_view
// ouput: dataset for ddl, with the column names of a dataset, choosen in v_sys_view_row
function sys_model_ddl_field($blank_row, $result_org, $view_name){
  $choosen_ds = $result_org["data"]["sys_view_row"][0]["ddl_ds_name"];
  // read the data
  $dataset = s_v_get_view_dataset($result_org,"model/models/get/mg_$choosen_ds.txt", "", $choosen_ds);
  //if(count($dataset) == 0){
    // no data, no columns, give blanco return
    $columns[0]["id"] = "";
    return $columns;
  //}
  $columns = array();
  $n = 0;
  foreach ($dataset[0] as $key => $value){
    $columns[$n]["id"] = $key;
    $n++;
  }
  return $columns;
}