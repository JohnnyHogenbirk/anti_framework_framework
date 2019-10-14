<?php
// call by view_functions, by a sys_view
// ouput: add a row to a model, type get
function sys_model_get_rows_add($rows, $result_total){
  $id = $_POST["ds_id"];
  // add row to this form view, get file firs
  $result = f_m_get_txt_data("model/data/d_sys_model.txt", "", "id='$id'", "", "", "form_rows1");
  $form_map_file = $result[0]["map_file"];
  // read it
  $result = f_m_get_txt_data($form_map_file, "", "", "order", "","form_rows2");
  // find last row
  $id_last = -1;
  for($i = 0; $i < count($result); $i++){
    $id_last = max($id_last, $result[$i]["id"]);
  }
  // add one
  $id_last++;
  // simulate a post
  $_POST["id_org"] = $id_last;
  $_POST["type"] = "txt";
  $_POST["source_name"] = "new.txt";
  $_POST["columns"] = "*";
  $store_id_db =   "id,type,source_name,columns";
  $store_id_form = "id_org,type,source_name,columns";
  s_m_put_txt_data("put_sys_model_get_row_add", "insert", $form_map_file, "", "", $store_id_db, $store_id_form);
  $_SESSION["id"] = "$id-$id_last";
  return $result_total;
}