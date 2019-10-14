<?php
// call by view_functions, by a sys_view
// ouput: delete a model, type get
function sys_model_get_rows_del($rows, $result_total){
  $id_model = $_POST["ds_id"];
  $result = f_m_get_txt_data("model/data/d_sys_model.txt", "", "id='$id_model'", "", "", "form_row1");
  $ref_id_db = "id";
  $ref_id_form = "id";
  $_POST["id"] = $id_model;
  s_m_put_txt_data("put_sys_model_get_rows_del", "delete", "model/data/d_sys_model.txt", $ref_id_db, $ref_id_form, "", "");
  unlink($result[0]["map_file"]);
  return $result_total;
}