<?php
// HARDCODED KOLOMNAMEN VERVANGEN
// Undefined offset: 1 in C:\xampp\htdocs\www\kickerproject.nl\aff\model\model_functions_put.php on line 201
// include by api.php
// div functions used by model code, part put
function f_m_put_data($model_name, $button_id){
  // read specs and insert/update/delete date
  $result["alert"]["text"] = "";
  $result["javascript"] = "";
  $rows = f_m_get_txt_data("model/models/put/mp_" . $model_name . ".txt", "*", "button_id='$button_id'", "", "", $model_name);
  for($i = 0; $i < count($rows); $i++){
    switch($rows[$i]["type"]){
      case "mysql":
        $result["alert"] = s_m_put_mysql_data($model_name, $rows[$i]["action"], $rows[$i]["source_name"], $rows[$i]["ref_id_db"], $rows[$i]["ref_id_form"], $rows[$i]["store_id_db"], $rows[$i]["store_id_form"]);
        break;
      case "txt":
        $result["alert"] = s_m_put_txt_data($model_name, $rows[$i]["action"], "model/data/d_" . $rows[$i]["source_name"], $rows[$i]["ref_id_db"], $rows[$i]["ref_id_form"], $rows[$i]["store_id_db"], $rows[$i]["store_id_form"]);
        break;
      case "next_view":
        // maybe there is an id in a session
        $id = "";
        if(isset($_SESSION["id"])){
          $id = $_SESSION["id"];
        }
        $result["javascript"] .= "f_get_content('" . $rows[$i]["next_view"] . "', '$id')";
        if($rows[$i]["alert"] != ""){
          $result["alert"]["text"] = $rows[$i]["alert"];
        }
        break;
      case "php":
        $filename_php = "model/php/put/pp_" . $rows[$i]["source_name"];
        if(file_exists($filename_php)){
          $function_name = substr($rows[$i]["source_name"], 0, strlen($rows[$i]["source_name"]) - 4);
          if(!function_exists ($function_name)){
            include($filename_php);
          }
          // reset whole browser, how to...
          $result = call_user_func($function_name, $rows, $result);
        }
        break;
      // more is posible, for example api's that get json data from another application
    }
    // no break, you can write more actions on one button
  }
  return $result;
}

// put mysql ---------------------------------------------------------------------------------------------------------
function s_m_put_mysql_data($model_name, $action, $tablename, $ref_id_db, $ref_id_form, $store_id_db, $store_id_form){
  $alert = "";

  $ref_data = s_m_put_get_view_data($ref_id_form);
  $store_data = s_m_put_get_view_data($store_id_form);
  $store_data_string = implode(",", s_m_put_get_view_data($store_id_form));
  $ref_id_db_arr = explode(",", $ref_id_db);
  $store_id_db_arr = explode(",", $store_id_db);
  switch($action){
    case "insert":
      $query = "INSERT INTO $tablename ($store_id_db) VALUES ($store_data_string)";
      $alert["text"] = "Data inserted";
      break;
    case "update":
      $set_value = s_m_put_get_set_value($store_id_db_arr, $store_data);
      $condition = s_m_put_get_condition($ref_id_db_arr, $ref_data);
      $query = "UPDATE $tablename SET $set_value WHERE $condition;";
      $alert["text"] = "Data updated";
      break;
    case "delete":
      $condition = s_m_put_get_condition($ref_id_db_arr, $ref_data);
      $query = "DELETE FROM $tablename WHERE $condition;";
      $alert["text"] = "Data deleted";
      break;
  }
  // you have to build the mysql action yourself
  // note: you should use PDO, for security

  return $alert;
}

// put txt ---------------------------------------------------------------------------------------------------------
function s_m_put_txt_data($model_name, $action, $tablename, $ref_id_db, $ref_id_form, $store_id_db, $store_id_form){
  // insert, update or delete data in a textfile, where columns must be separeted by ;
  $alert["text"] = "";

  // read whole file
  $rows = f_m_get_txt($tablename, $model_name, $model_name);
  if(count($rows) < 1){
    $alert["text"] = "System error (f101)";
    return $alert;
  }
  // first row are colums
  $columnames = f_m_get_column_names($rows);
  // split the rows into rows and coluns
  $rows = s_m_put_txt_data_get_rows_fast($rows, $columnames);
  // prepare: get data from form and make array's
  $ref_data = s_m_put_get_view_data($ref_id_form);
  $store_data = s_m_put_get_view_data($store_id_form);
  $ref_id_db_arr = explode(",", $ref_id_db);
  $store_id_db_arr = explode(",", $store_id_db);
  switch($action){
    case "insert":
      $rows = s_m_put_txt_data_insert($rows, $columnames, $store_data);
      $alert["text"] = "Data inserted";
      break;
    case "update":
      $rows = s_m_put_txt_data_update($rows, $ref_id_db_arr, $ref_data, $store_id_db_arr, $store_data);
      $alert["text"] = "Data updated";
      break;
    case "delete":
      $rows = s_m_put_txt_data_delete($rows, $columnames, $ref_id_db_arr, $ref_data);
      $alert["text"] = "Data deleted";
      break;
  }
  // if the file is a view or model, line out
  if((substr($tablename, 0, 11) == "view/views/") or (substr($tablename, 0, 13) == "model/models/")){
    $width = s_m_put_txt_data_order_max_width($columnames, $rows);
    $columnames = s_m_put_txt_data_order_columnnames($columnames, $width, $tablename);
    $rows = s_m_put_txt_data_order_rows($rows, $width);
  }
  // save columnames and rows
  s_m_put_txt_data_store($model_name, $tablename, $rows, $columnames);
  // return reply
  return $alert;
}

function s_m_put_txt_data_get_rows_fast($rows, $columnames){
  // input: rows with strings with ; that must be divide into columns
  $result = array();
  $n_r = 0;
  for($r = 1; $r < count($rows); $r++){
    // last row can be empty, so break
    if(trim($rows[$r]) == ''){
      break;
    }
    $fields = explode(";", $rows[$r]);
    for($k = 0; $k < count($columnames); $k++){
      $result[$n_r][$columnames[$k]] = trim($fields[$k]);
    }
    $n_r++;
  }
  return $result;
}

function s_m_put_txt_data_insert($rows, $columnames, $store_data){
  // insert data to $rows
  // only the data in $store_data
  // (not index by column name but column number!)
  $row_nr = count($rows);
  for($i = 0; $i < count($columnames); $i++){
    $this_value = "";
    if(isset($store_data[$i])){
      $this_value = $store_data[$i];
    }
    $rows[$row_nr][$columnames[$i]] = $this_value;
  }
  return $rows;
}

function s_m_put_txt_data_update($rows, $ref_id_db_arr, $ref_data, $store_id_db_arr, $store_data){
  // update the data when $ref_id_db is $ref_data
  // if update is needed, use column names in $store_id_db_arr to overwrit with $store_data
  for($r = 0; $r < count($rows); $r++){
    $update_this = 1;
    for($k = 0; $k < count($ref_id_db_arr); $k++){
      // notice: only = is supported
      if($rows[$r][$ref_id_db_arr[$k]] != $ref_data[$k]){
        $update_this = 0;
        break;
      }
    }
    if($update_this == 1){
      // yes, update (some) columns of this row
      for($k = 0; $k < count($store_id_db_arr); $k++){
        $rows[$r][$store_id_db_arr[$k]] = $store_data[$k];
      }
    }
  }
  return $rows;
}

function s_m_put_txt_data_delete($rows, $columnames, $ref_id_db_arr, $ref_data){
  // update the data when $ref_id_db is $ref_data
  $new_rows = array();
  $n = 0;
  for($r = 0; $r < count($rows); $r++){
    $delete_this = 1;
    for($k = 0; $k < count($ref_id_db_arr); $k++){
      if($rows[$r][$ref_id_db_arr[$k]] != $ref_data[$k]){
        $delete_this = 0;
        break;
      }
    }
    if($delete_this == 0){
      for($k = 0; $k < count($columnames); $k++){
        $new_rows[$n][$columnames[$k]] = $rows[$r][$columnames[$k]];
      }
      $n++;
    }
  }
  return $new_rows;
}

function s_m_put_txt_data_order_max_width($columnnames, $rows){
  // detect max width per column
  $width = array();
  for($k = 0; $k < count($columnnames); $k++){
    $width[$k] = strlen($columnnames[$k]) + 1;
  }
  for($r = 0; $r < count($rows); $r++){
    $k = 0;
    foreach($rows[$r] as $row){
      $width[$k] = max($width[$k], strlen($row) + 1);
      $k++;
    }
  }
  return $width;
}

function s_m_put_txt_data_order_rows($rows, $width){
  // make a view or model more readable by inserting space, to line out the seraration ;
  // $width is containing the width for eacht column
  if(count($rows) == 0){
    echo "Error, no rows";
    die();
  }
  // add space
  $rows_new = array();
  for($r = 0; $r < count($rows); $r++){
    $k = 0;
    foreach ($rows[$r] as $key => $value) {
      $rows_new[$r][$key] = $rows[$r][$key] . str_repeat(" ", $width[$k] - strlen($rows[$r][$key]));
      $k++;
    }
  }
  return $rows_new;
}

function s_m_put_txt_data_order_columnnames($columnnames, $width, $tablename){
  // make a view or model more readable by inserting space, to line out the seraration ;
  if(count($columnnames) == 0){
    echo "Error, no columnnames in $tablename";
    die();
  }
  // make columnnames same as rows
  $columnnames_new = array();
  for($k = 0; $k < count($columnnames); $k++){
    $columnnames_new[$k] = $columnnames[$k] . str_repeat(" ",  $width[$k] - strlen($columnnames[$k]));
  }
  return $columnnames_new;
}

function s_m_put_txt_data_store($model_name, $filename, $rows, $columnames){
  // does save $columnames and $rows to file $filename
  // open in write mode
  $open = @fopen($filename, "w");
  if(!$open){
    $reply["alert"]["text"] = "System error (f102, $filename)";
    f_return_json($reply);
  }
  // write the column names
  fwrite($open, implode(";", $columnames) . "\r\n");
  for($k = 0; $k < count($columnames); $k++){
    // must be trimt
    $columnames[$k] = trim($columnames[$k]);
  }
  // write the rows
  for($r = 0; $r < count($rows); $r++){
    for($k = 0; $k < count($columnames); $k++){
      // replace ; by |, needed for storing in csv files
      $rows[$r][$columnames[$k]] = str_replace(";", "|", $rows[$r][$columnames[$k]]);
    }
    fwrite($open, implode(";", $rows[$r]) . "\r\n");
  }
  fclose($open);
}

// general put functions -----------------------------------------------------------------------------------------------
function s_m_put_get_view_data($ids_form){
  // get data out of a form (id's stored in $ids_form, separated by ,
  $result = array();
  $ids_form_arr = explode(",", $ids_form);
  for($i = 0; $i < count($ids_form_arr); $i++){
    $result[$i] = "";
    if(isset($_POST[$ids_form_arr[$i]])){
      $result[$i] = $_POST[$ids_form_arr[$i]];
    }
  }
  return $result;
}

function s_m_put_get_set_value($store_id_db_arr, $store_data){
  // make set string for mysql query
  $result = "";
  for($i = 0; $i < count($store_id_db_arr); $i++){
    $result .= $store_id_db_arr[$i] . "=" . $store_data[$i] . ",";
  }
  return substr($result, 0, strlen($result) - 1);
}

function s_m_put_get_condition($ref_id_db_arr, $ref_data){
  // make where string for mysql query
  $where = "";
  for($i = 0; $i < count($ref_id_db_arr); $i++){
    $where .= $ref_id_db_arr[$i] . "='" . $ref_data[$i] . "'' AND ";
  }
  return substr($where, 0, strlen($where) - 5);
}
