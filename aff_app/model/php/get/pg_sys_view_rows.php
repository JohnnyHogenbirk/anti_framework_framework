<?php
// call by view_functions, by a sys_view
// ouput: dataset, two dimensional, all rows of a view
// added: [n][id_org] and [n][id], is needed for store data and go back from row to overview with rows
function sys_view_rows($blank_row, $result_org, $view_name){
  $id = $_GET["id"];
  // take the form, to get the map_file
  $result = f_m_get_txt_data("model/data/d_sys_view.txt", "", "id='$id'", "", "", "sys_view_rows1");
  // use map_file to read all the rows from the file
  // check
  if(!isset($result[0]["map_file"])){
    echo "Error, no id $id in d_sys_view";
    die();
  }
  $result = f_m_get_txt_data($result[0]["map_file"], "", "", "order", "","sys_view_rows2");
  // to hold the id from the form after jump to form_rows, change the id to id form - id row and save original id in id_org
  for($i = 0; $i < count($result); $i++){
    $result[$i]["id_org"] = $result[$i]["id"];
    $result[$i]["id"] = $id . "-" . $result[$i]["id"];
  }
  return $result;
}