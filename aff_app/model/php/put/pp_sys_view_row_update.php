<?php
// call by view_functions, by a sys_view
// ouput: update a row in a view
function sys_view_row_update($rows, $result_total){
  // $ids is combi: id form # id row
  $ids = $_POST["id"];
  $id_arr = explode("-", $ids);
  $id_view = $id_arr[0];
  $id_row = $id_arr[1];
  $result = f_m_get_txt_data("model/data/d_sys_view.txt", "", "id='$id_view'", "", "", "form_row1");
  $ref_id_db = "id";
  $ref_id_form = "id_org";
  $_POST["id_org"] = $id_row;
  // get columnnames
  $column = f_m_get_columns_view_model("model/data/d_sys_view.txt", "");
  // first always the same
  $store_id_db =   "id,";
  $store_id_form = "id_org,";
  // add others
  $column_str = "";
  for($i = 1; $i < count($column); $i++){
    $column_str .= $column[$i] . ",";
  }
  $column_str = substr($column_str, 0, strlen($column_str) - 1);
  $store_id_db .= $column_str;
  $store_id_form .= $column_str;
  s_m_put_txt_data("put_sys_view_row_update", "update", $result[0]["map_file"], $ref_id_db, $ref_id_form, $store_id_db, $store_id_form);
  return $result_total;
}