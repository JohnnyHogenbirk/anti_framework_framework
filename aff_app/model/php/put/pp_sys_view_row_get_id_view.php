<?php
// call by view_functions, by a sys_view
// ouput: session with id form the form
function sys_view_row_get_id_view($rows, $result_total){
  // $ids is combi: id form # id row
  $ids = $_POST["id"];
  $id_arr = explode("-", $ids);
  $id_view = $id_arr[0];
  $_SESSION["id"] = $id_view;
  return $result_total;
}