<?php
// api, called by code in index.html
// gives structure and data back to the browser
//
// general *************************************************************************************************************
// there must be a view_name via url or f_view_name by via posting form
if(!isset($_GET["view_name"]) and !isset($_POST["f_view_name"])){
  // if not, illegal call, so die without callback
  die();
}

// general functions for mvc, almost always used by the code
include("model/model_functions_get.php");
include("view/view_functions_general.php");
include("control/control_functions.php");

// allway's work with session and execute checks
f_session_start_check();

// post ****************************************************************************************************************
// if data must be added, update or delete, include the code for that action
if((isset($_POST["f_view_name"])) and (isset($_POST["f_button_id"]))){
  // include the functions, needed for data actions
  include("model/model_functions_put.php");
  // alway's view_name en button_id needed
  $view_name = $_POST["f_view_name"];
  $button_id =  $_POST["f_button_id"];
  // call the main function from model put actions
  $reply = f_m_put_data($view_name, $button_id);
  // in the reply, the viewname must be present
  $reply["view_name"] = $view_name;
}

// get *****************************************************************************************************************
// index data: 3-dim array, indexes: name dataset, row number, colomn-name. for example: $reply_data['list_1"][0]['value"]
// index view: array with different amount indexes
// index javascript: string with javascript that must be stored in the browser

// if a view or data is needed, include the code for that action
$view_name = "";
if(isset($_GET["view_name"])){
  $view_name = $_GET["view_name"];
  // the start views do start with s_
  // so, be aware of this when you give other views a name
  if(substr($view_name, 0, 2) == "s_"){
    // in the first call, the function is not included, so check
    if(!function_exists("f_$view_name")){
      include("view/view_functions_start.php");
    }
    // the start functions only give data back to the browser
    // notice: for every starting view, there must be a function with with 'f_'  + the viewname (in view_functions_start.php)
    $reply["data"] = call_user_func("f_$view_name");
  }else{
    // not a start view, so call the main view that returns the view en data index
    $reply = f_v_get_view($view_name);
  }
  // in the reply, the viewname must be present
  $reply["view_name"] = $view_name;
}

// return reply array back to the browser by json
f_return_json($reply);
// note: when there was only a posting of data and no new view asked,
// the reply is only an alert with 'data stored' or something like that
