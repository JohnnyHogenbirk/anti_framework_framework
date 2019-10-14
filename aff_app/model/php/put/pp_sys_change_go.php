<?php
// call by view_functions, by a sys_view
// div actions:
/*
1;Views, add column
2;Views, delete column
3;Get models, add column
4;Get models, delete column
5;Put models, add column
6;Put models, delete column
7;Rewrite all forms and datasets
 */
// ouput: changed views of models, return only an alert, result actions
function sys_change_go($rows, $result_total){
  // $ids is combi: id form # id row
  $action = $_POST["action"];
  $column_new = $_POST["column_new"];
  $std_value = $_POST["field_value"];
  $alert = "";
  switch($action){
    case "1":
      //                                            $target_file, $target_columnname, $type, $column, $pos, $std_value
      $alert .= sys_change_go__add_column("d_sys_view.txt", "map_file", "", $column_new, $_POST["action_view_add"], $std_value);
      break;
    case "2":
      $alert .= sys_change_go__delete_column("d_sys_view.txt", "map_file", "", $_POST["action_view_del"]);
      break;
    case "3":
      $alert .= sys_change_go__add_column("d_sys_model.txt", "map_file", "get", $column_new, $_POST["action_model_get_add"], $std_value);
      break;
    case "4":
      $alert .= sys_change_go__delete_column("d_sys_model.txt", "map_file", "get", $_POST["action_model_get_del"]);
      break;
    case "5":
      $alert .= sys_change_go__add_column("d_sys_model.txt", "map_file", "put", $column_new, $_POST["action_model_put_add"], $std_value);
      break;
    case "6":
      $alert .= sys_change_go__delete_column("d_sys_model.txt", "map_file", "put", $_POST["action_model_put_del"]);
      break;
    case "7":
      $alert .= sys_change_go__rewrite("d_sys_view.txt", "map_file");
      $alert .= sys_change_go__rewrite("d_sys_model.txt", "map_file");
      break;
  }
  $result_total["alert"]["text"] .= $alert;
  return $result_total;
}

function sys_change_go__add_column($target_file, $target_columnname, $type, $column, $pos, $std_value){
  // get all views
  $where = "";
  if($type == ""){
    $where = "get_put='$type'";
  }
  $result = f_m_get_txt_data("model/data/$target_file", "", $where, "", "", "sys_view_rows1");
  for($i = 0; $i < count($result); $i++){
    // get all data
    $rows = f_m_get_txt($result[$i][$target_columnname], "", "change_go");
    $columnames = f_m_get_column_names($rows);
    // then read the data, with check on where and the columns that must be taken
    $rows = s_m_get_txt_data_data_read($rows, $columnames, array(), "*", "");

    // add column in thirst row
    $columnames_new = array();
    $n = 0;
    for($k = 0; $k < count($columnames); $k++){
      if(($pos == "first") and ($k == 0)){
        $columnames_new[$n] = $column;
        $n++;
      }
      $columnames_new[$n] = $columnames[$k];
      $n++;
      if($pos == $columnames[$k]){
        $columnames_new[$n] = $column;
        $n++;
      }
    }
    // add blank row
    $rows_new = array();
    for($r = 0; $r < count($rows); $r++){
      for($k = 0; $k < count($columnames); $k++){
        if(($pos == "first") and ($k == 0)){
          $rows_new[$r][$column] = $std_value;
        }
        if(!isset($rows[$r][$columnames[$k]])){
          $rows[$r][$columnames[$k]] = "";
        }
        $rows_new[$r][$columnames[$k]] = $rows[$r][$columnames[$k]];
        if($pos == $columnames[$k]){
          $rows_new[$r][$column] = $std_value;
        }
      }
    }
    // take care of ; under ;
    $width = s_m_put_txt_data_order_max_width($columnames_new, $rows_new);
    $columnames_new = s_m_put_txt_data_order_columnnames($columnames_new, $width, $result[$i][$target_columnname]);
    $rows_new = s_m_put_txt_data_order_rows($rows_new, $width);
    // store file
    s_m_put_txt_data_store("change_go_save", $result[$i][$target_columnname], $rows_new, $columnames_new);
  }
  return "";
}

function sys_change_go__delete_column($target_file, $target_columnname, $type, $column){
  // get all views
  $where = "";
  if($type == ""){
    $where = "get_put='$type'";
  }
  $result = f_m_get_txt_data("model/data/$target_file", "", $where, "", "", "sys_view_rows1");
  for($i = 0; $i < count($result); $i++){
    // get all data
    $rows = f_m_get_txt($result[$i][$target_columnname], "", "change_go");
    $columnames = f_m_get_column_names($rows);
    // then read the data, with check on where and the columns that must be taken
    $rows = s_m_get_txt_data_data_read($rows, $columnames, array(), "*", "");

    // add column in thirst row
    $columnames_new = array();
    $n = 0;
    for($k = 0; $k < count($columnames); $k++){
      if($column != $columnames[$k]){
        $columnames_new[$n] = $columnames[$k];
        $n++;
      }
    }
    // add blank row
    $rows_new = array();
    for($r = 0; $r < count($rows); $r++){
      for($k = 0; $k < count($columnames); $k++){
        if(!isset($rows[$r][$columnames[$k]])){
          $rows[$r][$columnames[$k]] = "";
        }
        if($column != $columnames[$k]){
          $rows_new[$r][$columnames[$k]] = $rows[$r][$columnames[$k]];
        }
      }
    }
    // take care of ; under ;
    $width = s_m_put_txt_data_order_max_width($columnames_new, $rows_new);
    $columnames_new = s_m_put_txt_data_order_columnnames($columnames_new, $width, $result[$i][$target_columnname]);
    $rows_new = s_m_put_txt_data_order_rows($rows_new, $width);
    // store file
    s_m_put_txt_data_store("change_go_save", $result[$i][$target_columnname], $rows_new, $columnames_new);
  }
  return "";
}

function sys_change_go__rewrite($target_file, $target_columnname){
  $alert = "";
  // get all views
  $result = f_m_get_txt_data("model/data/$target_file", "", "", "", "", "sys_view_rows1");
  for($i = 0; $i < count($result); $i++){
    // get all data
    $rows = f_m_get_txt($result[$i][$target_columnname], "", "change_go");
    if(count($rows) == 0){
      $alert .= "File " . $result[$i][$target_columnname] . " does not have rows or not exist<br>";
    }
    $columnames = f_m_get_column_names($rows);
    // then read the data, with check on where and the columns that must be taken
    $rows = s_m_get_txt_data_data_read($rows, $columnames, array(), "*", "");

    // take care of ; under ;
    $width = s_m_put_txt_data_order_max_width($columnames, $rows);
    $columnames = s_m_put_txt_data_order_columnnames($columnames, $width, $result[$i][$target_columnname]);
    $rows = s_m_put_txt_data_order_rows($rows, $width);
    // store file
    s_m_put_txt_data_store("change_go_save", $result[$i][$target_columnname], $rows, $columnames);
  }
  return $alert;
}
