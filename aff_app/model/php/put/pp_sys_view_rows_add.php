<?php
// call by view_functions, by a sys_view
// ouput: add a row in a view
function sys_view_rows_add($rows, $result_total){
  $id = $_POST["view_id"];
  // add row to this form view, get file firs
  $result = f_m_get_txt_data("model/data/d_sys_view.txt", "", "id='$id'", "", "", "form_rows1");
  $form_map_file = $result[0]["map_file"];
  // read it
  $result = f_m_get_txt_data($form_map_file, "", "", "order", "","form_rows2");
  // find last row
  $id_last = -1;
  $order_last = -1;
  for($i = 0; $i < count($result); $i++){
    $id_last = max($id_last, $result[$i]["id"]);
    $order_last = max($order_last, $result[$i]["order"]);
  }
  // add one
  $id_last++;
  $order_last++;
  // simulate a post
  $_POST["id_org"] = $id_last;
  $_POST["order"] = $order_last;
  $_POST["type"] = "row";
  $_POST["element"] = "hidden";
  $_POST["display"] = "none";
  $_POST["row_id"] = "new";
  $_POST["title"] = "New";
  $store_id_db =    "id,order,type,element,display,row_id,title";
  $store_id_form = "id_org,order,type,element,display,row_id,title";
  s_m_put_txt_data("put_sys_view_row_add", "insert", $form_map_file, "", "", $store_id_db, $store_id_form);
  $_SESSION["id"] = "$id-$id_last";
  return $result_total;
}