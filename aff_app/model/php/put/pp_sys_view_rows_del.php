<?php
// call by view_functions, by a sys_view
// ouput: delete a view
function sys_view_rows_del($rows, $result_total){
  $id_view = $_POST["view_id"];
  $result = f_m_get_txt_data("model/data/d_sys_view.txt", "", "id='$id_view'", "", "", "form_row1");
  $ref_id_db = "id";
  $ref_id_form = "id";
  $_POST["id"] = $id_view;
  s_m_put_txt_data("put_sys_view_rows_delete", "delete", "model/data/d_sys_view.txt", $ref_id_db, $ref_id_form, "", "");
  unlink($result[0]["map_file"]);
  return $result_total;
}