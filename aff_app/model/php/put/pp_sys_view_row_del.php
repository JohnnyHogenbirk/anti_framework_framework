<?php
// call by view_functions, by a sys_view
// ouput: delete a row in a view
function sys_view_row_del($rows, $result_total){
  $ids = $_POST["id"];
  // $ids is combi: id form # id row
  $id_arr = explode("-", $ids);
  $id_view = $id_arr[0];
  $id_row = $id_arr[1];
  $result = f_m_get_txt_data("model/data/d_sys_view.txt", "", "id='$id_view'", "", "", "form_row1");
  $ref_id_db = "id";
  $ref_id_form = "id_org";
  $_POST["id_org"] = $id_row;
  s_m_put_txt_data("put_sys_view_row_update", "delete", $result[0]["map_file"], $ref_id_db, $ref_id_form, "", "");
  return $result_total;
}