<?php
// call by view_functions, by a sys_view
// div actions:
/*
1;View
2;Overview
3;Overview+views
4;Model get
5;Model put
*/
// ouput: changed views of models, return only an alert, result actions
function sys_gen($rows, $result_total){
  // $ids is combi: id form # id row
  $action = $_POST["action"];
  $name = f_filter_characters(strtolower($_POST["name"]), "0123456789_abcdefghijklmnopqrstuvwxyz");
  $head_text = $_POST["head_text"];
  $items = $_POST["items"];
  $datasource = $_POST["datasource"];
  $a_buttons = $_POST["a_buttons"];
  $model_get_type = $_POST["model_get_type"];
  $model_get_source = $_POST["model_get_source"];
  $alert = "";

  switch($action){
    case "1":
      // create view v_$name
      if(file_exists("view/views/v_$name.txt")){
        return "View allready exists";
      }
      $alert = sys_gen__add_view("view", $name, $head_text, $items, $datasource, $a_buttons);
      break;
    case "2":
      // create view v_$name
      if(file_exists("view/views/v_$name.txt")){
        return "View allready exists";
      }
      $alert = sys_gen__add_view("overview", $name, $head_text, $items, $datasource, $a_buttons);
      break;
    case "3":
      // create view v_$name
      if(file_exists("view/views/v_$name.txt")){
        return "View allready exists";
      }
      $a_buttons = "1";
      $alert = sys_gen__add_view("overview", $name, $head_text, $items, "yes", $a_buttons);
      if($alert != ""){
        break;
      }
      $a_buttons = "1,2";
      $alert = sys_gen__add_view("view", $name . "_add", $head_text . " add", $items, "no", $a_buttons);
      if($alert != ""){
        break;
      }
      $a_buttons = "1,2,2";
      $alert = sys_gen__add_view("view", $name . "_change", $head_text . " change", $items, "yes", $a_buttons);
      break;
    case "4":
      // create model get mg_$name
      $alert = sys_gen__add_model_get($name, $model_get_type, $model_get_source);
      break;
    case "5":
      // create model punt pg_$name
      // loop $a_buttons
      //   1: update, sql
      //   2: back + update, sql
      //   1: back + update, sql + delete
      break;
  }
  $result_total["alert"]["text"] .= $alert;
  return $result_total;
}

function sys_gen__add_view($type, $name, $head_text, $items, $datasource, $a_buttons){
  // file view *********************************************************************************************************
  $column = f_m_get_columns_view_model("model/data/d_sys_view.txt", "");
  // make rows
  $rows = array();
  $row_nr = 0;
  if($head_text != ""){
    $rows[$row_nr]["id"] = $row_nr + 1;
    $rows[$row_nr]["order"] = $row_nr + 1;
    $rows[$row_nr]["type"] = "row";
    $rows[$row_nr]["element"] = "head_text";
    $rows[$row_nr]["display"] = "block";
    $rows[$row_nr]["row_id"] = "";
    $rows[$row_nr]["title"] = $head_text;
    $rows[$row_nr] = sys_gen__add_rows($rows[$row_nr], $column);
    $row_nr++;
  }
  // rows from $items
  $items_arr = explode(",", $items);
  for($i = 0; $i < count($items_arr); $i++){
    $items_arr[$i] = trim($items_arr[$i]);
    if($items_arr[$i] != ""){
      // id, without space en special character
      $id = f_filter_characters(strtolower($items_arr[$i]), "0123456789_abcdefghijklmnopqrstuvwxyz");
      $rows[$row_nr]["id"] = $row_nr + 1;
      $rows[$row_nr]["order"] = $row_nr + 1;
      if($type == "view"){
        $rows[$row_nr]["type"] = "row";
        $rows[$row_nr]["element"] = "textbox";
        $rows[$row_nr]["display"] = "block";
      }else{
        $rows[$row_nr]["type"] = "column";
        $rows[$row_nr]["element"] = "field";
        $rows[$row_nr]["display"] = "table-cell";
        $rows[$row_nr]["width"] = 150;
      }
      $rows[$row_nr]["row_id"] = $id;
      $rows[$row_nr]["title"] = $items_arr[$i];
      if(($datasource == "yes") or ($type == "overview")){
        $rows[$row_nr]["dataset_name"] = $name;
        $rows[$row_nr]["dataset_field"] = $id;
      }
      $rows[$row_nr] = sys_gen__add_rows($rows[$row_nr], $column);
      $row_nr++;
    }
  }

  // buttons from $a_buttons
  $a_buttons_arr = explode(",", $a_buttons);
  $button_nr = 1;
  for($i = 0; $i < count($a_buttons_arr); $i++){
    $a_buttons_arr[$i] = trim($a_buttons_arr[$i]);
    if($a_buttons_arr[$i] != ""){
      $rows[$row_nr]["id"] = $row_nr + 1;
      $rows[$row_nr]["order"] = $row_nr + 1;
      $rows[$row_nr]["type"] = "row";
      $rows[$row_nr]["element"] = "button";
      $rows[$row_nr]["display"] = "inline-block";
      $rows[$row_nr]["row_id"] = "button" . $button_nr;
      $rows[$row_nr]["title"] = "XXX";
      $rows[$row_nr] = sys_gen__add_rows($rows[$row_nr], $column);
      $row_nr++;
      $button_nr++;
    }
  }

  // take care of ; under ;
  $width = s_m_put_txt_data_order_max_width($column, $rows);
  $column = s_m_put_txt_data_order_columnnames($column, $width, $name);
  $rows = s_m_put_txt_data_order_rows($rows, $width);
  // store file
  s_m_put_txt_data_store("sys_gen", "view/views/v_$name.txt", $rows, $column);

  // add to sys_view
  $store_id_db = "id,view_name,map_file,sys,description";
  $store_id_form = "id,view_name,map_file,sys,description";
  $_POST["id"] = f_auto_nr_maak();
  $_POST["view_name"] = $name;
  $_POST["map_file"] = "view/views/v_$name.txt";
  $_POST["sys"] = "no";
  $_POST["description"] = $name;
  s_m_put_txt_data("sys_gen", "insert", "model/data/d_sys_view.txt", "", "", $store_id_db, $store_id_form);

  // file model get ****************************************************************************************************
  if(($datasource == "yes") or ($type == "overview")){
    sys_gen__add_model_get($name, "mysql", "");
  }
  // file model put ****************************************************************************************************
  sys_gen__add_model_put($name, $a_buttons);
  return "";
}

function sys_gen__add_model_get($name, $model_get_type, $model_get_source){
  // get the column names
  $column = f_m_get_columns_view_model("model/data/d_sys_model.txt", "get_put='get'");
  // make one row
  $rows = array();
  $rows[0]["id"] = 1;
  $rows[0]["order"] = 1;
  $rows[0]["type"] = $model_get_type;
  $rows[0]["source_name"] = $model_get_source;

  $rows[0] = sys_gen__add_rows($rows[0], $column);

  // take care of ; under ;
  $width = s_m_put_txt_data_order_max_width($column, $rows);
  $column = s_m_put_txt_data_order_columnnames($column, $width, $name);
  $rows = s_m_put_txt_data_order_rows($rows, $width);
  // store file
  s_m_put_txt_data_store("sys_gen", "model/models/get/mg_$name.txt", $rows, $column);

  // add to sys_model
  $store_id_db = "id,model_name,get_put,map_file,description";
  $store_id_form = "id,model_name,get_put,map_file,description";
  $_POST["id"] = f_auto_nr_maak();
  $_POST["model_name"] = $name;
  $_POST["get_put"] = "get";
  $_POST["map_file"] = "model/models/get/mg_$name.txt";
  $_POST["description"] = $name;
  s_m_put_txt_data("sys_gen", "insert", "model/data/d_sys_model.txt", "", "", $store_id_db, $store_id_form);
  return "";
}

function sys_gen__add_model_put($name, $a_buttons){
  if(trim($a_buttons) != ""){
    // get the column names
    $column = f_m_get_columns_view_model("model/data/d_sys_model.txt", "get_put='put'");
    // make rows
    // buttons from $a_buttons
    $a_buttons_arr = explode(",", $a_buttons);
    $rows = array();
    $button_nr = 1;
    $row_nr = 0;
    for($i = 0; $i < count($a_buttons_arr); $i++){
      $a_buttons_arr[$i] = trim($a_buttons_arr[$i]);
      if($a_buttons_arr[$i] > 0){
        for($p = 0; $p < $a_buttons_arr[$i]; $p++){
          $rows[$row_nr]["id"] = $row_nr + 1;
          $rows[$row_nr]["order"] = $row_nr + 1;
          $rows[$row_nr]["button_id"] = "button" . $button_nr;
          $rows[$row_nr]["action"] = "next_view";
          $rows[$row_nr]["type"] = "mysql";
          $rows[$row_nr] = sys_gen__add_rows($rows[$row_nr], $column);
          $row_nr++;
        }
        $button_nr++;
      }
    }

    // take care of ; under ;
    $width = s_m_put_txt_data_order_max_width($column, $rows);
    $column = s_m_put_txt_data_order_columnnames($column, $width, $name);
    $rows = s_m_put_txt_data_order_rows($rows, $width);
    // store file
    s_m_put_txt_data_store("sys_gen", "model/models/put/mp_$name.txt", $rows, $column);

    // add to sys_model
    $store_id_db = "id,model_name,get_put,map_file,description";
    $store_id_form = "id,model_name,get_put,map_file,description";
    $_POST["id"] = f_auto_nr_maak();
    $_POST["model_name"] = $name;
    $_POST["get_put"] = "put";
    $_POST["map_file"] = "model/models/put/mp_$name.txt";
    $_POST["description"] = $name;
    s_m_put_txt_data("sys_gen", "insert", "model/data/d_sys_model.txt", "", "", $store_id_db, $store_id_form);
  }
  return "";
}

function sys_gen__add_rows($row, $column){
  $row_new = array();
  for($i = 0; $i < count($column); $i++){
    if(!isset($row[$column[$i]])){
      $row_new[$column[$i]] = "";
    }else{
      $row_new[$column[$i]] = $row[$column[$i]];
    }
  }
  return $row_new;
}
