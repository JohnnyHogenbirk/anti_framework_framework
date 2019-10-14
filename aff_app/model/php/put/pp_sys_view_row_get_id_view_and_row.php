<?php
// call by view_functions, by a sys_view
// ouput: session with id form the form
function sys_view_row_get_id_view_and_row($rows, $result_total){
  // $ids is combi: id form - id row
  $_SESSION["id"] = $_POST["id"];
  return $result_total;
}