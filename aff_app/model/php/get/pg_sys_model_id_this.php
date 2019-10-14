<?php
// call by view_functions, by a sys_view
// ouput: dataset, by a sys_view: only one row, with the index ds_id
function sys_model_id_this($blank_row, $result_org, $view_name){
  $result[0]["ds_id"] = $_GET["id"];
  return $result;
}