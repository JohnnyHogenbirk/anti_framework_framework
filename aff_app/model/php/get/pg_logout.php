<?php
// call by view_functions
// ouput: destroy session and take care of reload page
function logout($blank_row, $result_org, $view_name){
  // this is a fake model get, it is called by logout
  // it destroys the session and take care of refreshing te page
  session_destroy();
  $_SESSION = array();
  $reply["javascript"] = "location.reload();";
  f_return_json($reply);
  return "";
}