<?php
// include by api.php
// does read the ini file for the form or overview
function f_v_get_view($view_name){
  // get the structure of the view
  $result["view"] = f_m_get_txt_data("view/views/v_$view_name.txt", "*", "", "order", "", $view_name);

  // get js for direct execution or field checks
  $result["javascript"] = s_v_get_js($result, $view_name);

  // check if data is neaded and read is
  $result["data"] = s_v_get_view_datasets($result);
  return $result;
}

function s_v_get_js($result_org, $view_name){
  // check if a view has js, make always a function
  $rows = f_m_get_txt("view/javascript/dynamic/js_start.js", "", "s_v_get_js");
  // replace // checks bij |checks|, so it don't disapear in minify proces

  // minify
  $result = s_v_get_js_minify($rows, "", "");
  // get the code that must be placed inside the f_form_submit function
  // new variable, because the content must be placed within the function f_form_submit
  $content = "";
  for($i = 0; $i < count($result_org["view"]); $i++){
    if($result_org["view"][$i]["check"] != ""){
      // read the rows
      $rows = f_m_get_txt("view/javascript/dynamic/" . $result_org["view"][$i]["check"], "", $view_name);
      // add it to $content
      $content .= s_v_get_js_minify($rows, $result_org["view"][$i]["row_id"], $result_org["view"][$i]["title"]);
    }
  }
  // replace the placeholder in f_form_submit by the content
  $result = str_replace("// checks", $content, $result);
  // add functions that must be called after leaving a html object
  for($i = 0; $i < count($result_org["view"]); $i++){
    if($result_org["view"][$i]["blur"] != ""){
      // read the rows
      $rows = f_m_get_txt("view/javascript/dynamic/" . $result_org["view"][$i]["blur"], "", $view_name);
      // add it to $content
      $result .= s_v_get_js_minify($rows, $result_org["view"][$i]["row_id"], $result_org["view"][$i]["title"]);
    }
  }
  // add direct executable js at the start
  for($i = 0; $i < count($result_org["view"]); $i++){
    if($result_org["view"][$i]["start"] != ""){
      // read the rows
      $rows = f_m_get_txt("view/javascript/dynamic/" . $result_org["view"][$i]["start"], "", $view_name);
      // add it to $content
      $result .= s_v_get_js_minify($rows, $result_org["view"][$i]["row_id"], $result_org["view"][$i]["title"]);
    }
  }
  return $result;
}

function s_v_get_js_minify($result_arr, $this_id, $this_name){
  // minify javascript
  $content = "";
  for($i = 0; $i < count($result_arr); $i++){
    // remove space and \r\n
    $result_arr[$i] = trim($result_arr[$i]);
    // remove //, but not in js_start.js the place for the checks
    // notice: don't use // in javascript at the end of a row
    // notice: don't use /* ... */
    if((substr($result_arr[$i], 0, 2) != "//") or (trim($result_arr[$i]) == "// checks")){
      $content .= $result_arr[$i];
    }
  }
  // replace id's and names in the javacript
  // notice: many common statements like 'object is not empty', need a id of name
  // so, you can use #this_id or #this_name
  $content = str_replace("#this_id", $this_id, $content);
  $content = str_replace("#this_name", $this_name, $content);
  return $content;
}

function s_v_get_view_datasets($result){
  // check if there are datasets, collect them
  // maybe there is no data yet, so initiate the array
  if(!isset($result["data"])){
    $result["data"] = array();
  }
  // collect the sets
  $datasets = array();
  // in case of ddl_ds, there can be a blank row needed
  $blank_row = array();
  $got_sets = "#";
  $n = 0;
  for($i = 0; $i < count($result["view"]); $i++){
    // check if a dataset is needed to fill the form
    if($result["view"][$i]["dataset_name"] != ""){
      // check if we allready have it
      if(!strstr($got_sets, "#" . $result["view"][$i]["dataset_name"] . "#")){
        // no, so add him
        $datasets[$n] = $result["view"][$i]["dataset_name"];
        $blank_row[$n] = "";
        // remember that we got him
        $got_sets .= $datasets[$n] . "#";
        $n++;
      }
    }
    // check if a dateset is needed for a ddl
    if($result["view"][$i]["ddl_ds_name"] != ""){
      // check if we allready have it
      if(!strstr($got_sets, "#" . $result["view"][$i]["ddl_ds_name"] . "#")){
        // no, so add him
        $datasets[$n] = $result["view"][$i]["ddl_ds_name"];
        $blank_row[$n] = $result["view"][$i]["ddl_ds_blank"];
        // remember that we got him
        $got_sets .= $datasets[$n] . "#";
        $n++;
      }
    }
  }
  // read the datasets
  for($i = 0; $i < count($datasets); $i++){
    $view_name = $datasets[$i];
    // read the file
    $result["data"][$view_name] = s_v_get_view_dataset($result,"model/models/get/mg_$view_name.txt", $blank_row[$i], $view_name);
  }

  // only the datasets return, response will be stored in $result["data"]
  return $result["data"];
}

function s_v_get_view_dataset($result_org, $filename, $blank_row, $view_name){
  // reads file from model/models: specs for dataset and reads the data
  // return array with index datasetname, rows (n) and columns (name column)
  if(!file_exists($filename)){
    echo "$filename does not exist<br>";
    die();
  }
  $rows = f_m_get_txt_data($filename, "*", "", "", "", $view_name);
  $result = array();
  for($i = 0; $i < count($rows); $i++){
    // you can add some types, for example api's that get json data from another application
    switch($rows[$i]["type"]){
      case "mysql":
        $result = f_m_get_mysql_data($rows[$i]["source_name"], $rows[$i]["columns"], $rows[$i]["where"], $rows[$i]["sort"], $blank_row, $view_name);
        break;
      case "txt":
        $result = f_m_get_txt_data("model/data/d_" . $rows[$i]["source_name"], $rows[$i]["columns"], $rows[$i]["where"], $rows[$i]["sort"], $blank_row, $view_name);
        break;
      case "php":
        $filename_php = "model/php/get/pg_" . $rows[$i]["source_name"];
        // it should not be nessesary, but check
        if(file_exists($filename_php)){
          // notice: function name must be the same (without .php)
          $function_name = substr($rows[$i]["source_name"], 0, strlen($rows[$i]["source_name"]) - 4);
          // it should not be nessesary, but check
          if(!function_exists ($function_name)){
            include($filename_php);
          }
          // call the function
          $result = call_user_func($function_name, $blank_row, $result_org, $view_name);
        }
        break;
    }
  }
  return $result;
}
