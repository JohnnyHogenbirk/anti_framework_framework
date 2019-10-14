<?php
// include by api.php
// div functions used by model code, part get
function f_m_get_mysql_data($tablename, $columns, $select, $sort, $blank_row, $view_name){
  // get data from a mysql table
  // add where
  if($select != ""){
    $select = "WHERE " . $select;
  }
  // add order by
  if($sort != ""){
    $sort = "ORDER BY " . $sort;
  }
  $query = "SELECT $columns FROM $tablename $select $sort";
  // in this demo there is no mysql database, so, you have to fill in this yourself

  return array();
}

function f_m_get_txt_data($filename, $columns, $where, $sort, $blank_row, $view_name){
  // read txt file, separated with ; and return rows (if there are)
  $rows = f_m_get_txt($filename, "", $view_name);
  if(count($rows) < 2){
    return array();
  }
  if($columns == ""){
    $columns = "*";
  }
  // first row are colums, put them in a array
  $columnames = f_m_get_column_names($rows);
  // split $where to array with columname number and value
  $where_arr = array();
  if($where != ""){
    $where_arr = s_m_get_txt_data_split_where($where, $columnames);
  }
  // then read the data, with check on where and the columns that must be taken
  $result = s_m_get_txt_data_data_read($rows, $columnames, $where_arr, $columns, $blank_row);
  // sort, if more then one row
  if((count($result) > 1) and ($sort != "")){
    $result = s_m_get_txt_data_sort($result, $sort);
  }
  return $result;
}

function s_m_get_txt_data_split_where($where, $columnames){
  // explode $where to multi array [n][{column number}] and [n][{column value}]
  // needed for s_m_get_txt_data_data_read
  $where_arr = array();
  if($where == ""){
    return $where_arr;
  }
  $where_org_arr = explode(",", $where);
  $n = 0;
  for($i = 0; $i < count($where_org_arr); $i++){
    // for now, for this demo, only = is posible
    $column_value = explode("=", $where_org_arr[$i]);
    // check if written good, must be column='value'
    if(count($column_value) == 2){
      // get the column number
      for($p = 0; $p < count($columnames); $p++){
        if($column_value[0] == $columnames[$p]){
          // yes, same column, so keep number and value
          $where_arr[$n]["column_nr"] = $p;
          $where_arr[$n]["value"] = str_replace("'", "", $column_value[1]);
          $n++;
          break;
        }
      }
    }
  }
  return $where_arr;
}

function s_m_get_txt_data_data_read($rows, $columnames, $where_arr, $columns, $blank_row){
  // make array's from strings ($rows) split by ;
  // $columnames is a array [0, 1, ...]  with columnnames
  // take only the rows with condition by $where_arr
  // take only the colums by $columns
  // $blank_row is for data in dll's: maybe the first row must be blank (then 'yes')
  // output: $result[n][{column name}]
  $result = array();
  // prepare to take column
  $take_this_column = array();
  $columns_take = "," . $columns . ",";
  for($k = 0; $k < count($columnames); $k++){
    // select columns clause
    $take_this_column[$k] = 1;
    // only when $colums is not *, check if the column must be taken
    if($columns != "*"){
      if(!strstr($columns_take, "," . $columnames[$k] . ",")){
        // no, so zero
        $take_this_column[$k] = 0;
      }
    }
  }
  $n_r = 0;
  // can be yes for data in dll
  if($blank_row == "yes"){
    for($k = 0; $k < count($columnames); $k++){
      // select columns clause
      if($take_this_column[$k] == 1){
        $result[$n_r][$columnames[$k]] = " ";
      }
    }
    $n_r++;
  }
  for($r = 1; $r < count($rows); $r++){
    // last row can be empty, so break
    if(trim($rows[$r]) == ''){
      break;
    }
    $fields = explode(";", $rows[$r]);
    // where clause
    $take_this = 1;
    for($p = 0; $p < count($where_arr); $p++){
      if($where_arr[$p]["value"] != trim($fields[$where_arr[$p]["column_nr"]])){
        // notice: in this version only = check
        $take_this = 0;
        break;
      }
    }
    if($take_this == 1){
      for($k = 0; $k < count($columnames); $k++){
        // select columns clause
        if($take_this_column[$k] == 1){
          $result[$n_r][$columnames[$k]] = str_replace("|", ";", trim($fields[$k]));
        }
      }
      $n_r++;
    }
  }
  return $result;
}

function s_m_get_txt_data_sort($result, $sort){
  // sort the data in multiple array
  // there can be more then one column to sort on
  $sort_arr = explode(",", $sort);
  for($i = 0; $i < count($sort_arr); $i++){
    $SortAux = array();
    foreach($result as $SO){
      $SortAux[] = $SO[$sort_arr[$i]];
    }
    $SortOrg = array_keys($result);
    array_multisort($SortAux, SORT_ASC, $SortOrg, $result);
  }
  return $result;
}

// general functions, used by model_functions_get and _put -------------------------------------------------------------
function f_m_get_columns_view_model($source, $where){
  // get all models, to pick one
  $result = f_m_get_txt_data($source, "", $where, "", "", "sys_gen2a");
  if(count($result) == 0){
    echo "$source is empty!";
    die();
  }
  // read the model
  $rows = f_m_get_txt($result[0]["map_file"], "", "get columns view model 2");
  // return de model names
  return f_m_get_column_names($rows);
}

function f_m_get_txt($filename, $model_name, $view_name){
  // give back the content of a txt file
  $rows = array();
  if(!file_exists($filename)){
    return $rows;
  }
  $open = @fopen($filename, "r");
  if(!$open){
    $reply["alert"]["text"] = "The view $filename does not open";
    $reply["view_name"] = $view_name;
    f_return_json($reply);
  }
  // read content, will be stored in array[n]
  $rows = file($filename);
  fclose($open);
  return $rows;
}

function f_m_get_column_names($rows){
  // get the column names from first row
  if(!isset($rows[0])){
    return array();
  }
  $columnames = explode(";", $rows[0]);
  for($k = 0; $k < count($columnames); $k++){
    $columnames[$k] = trim($columnames[$k]);
  }
  return $columnames;
}

function f_m_get_files($dir_name, $pre){
  // read all files in a dir
  // selection option: $pre: contains first characters of the file
  $dir = opendir($dir_name);
  $files = array();
  $n = 0;
  $pre_length = strlen($pre);
  while(($file = readdir($dir))){
    if(substr($file, 0, $pre_length) == $pre){
      $files[$n] = $file;
      $n++;
    }
  }
  sort($files);
  closedir($dir);
  return $files;
}