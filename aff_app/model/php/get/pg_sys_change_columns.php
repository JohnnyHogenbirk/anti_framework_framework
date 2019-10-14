<?php
// call by view_functions, by a sys_view
// ouput: dataset for ddl, with the column names of a view or model
function sys_change_columns($blank_row, $result_org, $view_name){
  // who is calling?
  if(strstr($view_name, "_view_")){
    // sys view for views
    $result = f_m_get_txt_data("model/data/d_sys_view.txt", "", "", "", "","sys_change_columns1a");
  }else{
    // sys view for models, get or put
    if(strstr($view_name, "_get_")){
      $result = f_m_get_txt_data("model/data/d_sys_model.txt", "", "get_put='get'", "", "","sys_change_columns1b");
    }else{
      $result = f_m_get_txt_data("model/data/d_sys_model.txt", "", "get_put='put'", "", "","sys_change_columns1c");
    }
  }
  if(count($result) == 0){
    echo "There are no rows ($view_name)!";
    die();
  }
  // get the first
  $result = f_m_get_txt_data($result[0]["map_file"], "", "", "", "","sys_change_columns2");
  if(count($result) == 0){
    // there must be a first row
    echo "Empty view/model! (" . $result[0]["map_file"] . ")";
    die();
  }
  // to hold the id from the form after jump to form_row, change id to id_org and add id with combi
  $columns = array();
  $n = 0;
  if(strstr($view_name, "_add")){
    $columns[$n]["id"] = "first";
    $columns[$n]["name"] = "First";
    $n++;
  }
  foreach ($result[0] as $key => $value) {
    $columns[$n]["id"] = $key;
    $columns[$n]["name"] = $key;
    $n++;
  }
  return $columns;
}