<?php
// call by view_functions, by a sys_view
// ouput: add a row to a model, type put
function sys_model_put_rows_add($rows, $result_total){
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
  $_POST["button_id"] = "buttonX";
  $_POST["action"] = "next";
  $_POST["type"] = "next_view";
  $store_id_db =   "id,button_id,action,type";
  $store_id_form = "id_org,button_id,action,type";
  s_m_put_txt_data("put_sys_model_put_row_add", "insert", $form_map_file, "", "", $store_id_db, $store_id_form);
  $_SESSION["id"] = "$id-$id_last";
  return $result_total;
}