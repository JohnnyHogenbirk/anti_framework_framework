<?php
// call by view_functions, by a sys_view
// ouput: dataset, two dimensional, one row of a model
// added: [n][id_org] and [n][id], is needed for store data and go back from row to overview with rows
function sys_model_row($blank_row, $result_org, $view_name){
  $ids = $_GET["id"];
  // $ids is combi: id form # id row
  $id_arr = explode("-", $ids);
  // get the id of the model and row
  $id_ds = $id_arr[0];
  $id_row = $id_arr[1];
  // get the model
  $result = f_m_get_txt_data("model/data/d_sys_model.txt", "", "id='$id_ds'", "", "","sys_model_row1");
  // get row that must be showed
  $result = f_m_get_txt_data($result[0]["map_file"], "", "id='$id_row'", "", "","sys_model_row2");
  // to hold the id from the form after jump to form_row, change id to id_org and add id with combi
  for($i = 0; $i < count($result); $i++){
    $result[$i]["id_org"] = $result[$i]["id"];
    $result[$i]["id"] = "$id_ds-$id_row";
  }
  return $result;
}