<?php
// include by api.php
// div functions used by control (including api.php), view and model code
function f_return_json($reply){
  // reply must contain some indexes
  if(!isset($reply["view_name"])){
    $reply["view_name"] = "";
  }
  if(!isset($reply["data"])){
    $reply["data"] = array();
  }
  if(!isset($reply["view"])){
    $reply["view"] = array();
  }
  if(!isset($reply["javascript"])){
    $reply["javascript"] = "";
  }
  if(!isset($reply["alert"])){
    $reply["alert"] = array();
  }
  echo json_encode($reply);
  die();
}

function f_filter_characters($characters, $filter){
  $id = "";
  for($i = 0; $i < strlen($characters); $i++){
    $character = substr($characters, $i, 1);
    if(strstr($filter, $character)){
      $id .= $character;
    }
  }
  return $id;
}

function f_auto_nr_maak() {
  return date('YmdHis') . chr(rand(97, 122)) . chr(rand(97, 122)) . chr(rand(97, 122)) . chr(rand(97, 122)) . chr(rand(97, 122)) . chr(rand(97, 122)) . chr(rand(97, 122)) . chr(rand(97, 122));
}

function f_session_start_check(){
  session_start();
  // check if another url uses the code
  if(isset($_SESSION["url"])){
    $uri_arr = explode("?", $_SERVER["REQUEST_URI"]);
    if($_SESSION["url"] != $_SERVER["HTTP_HOST"] . $uri_arr[0]){
      @session_destroy();
      $_SESSION = array();
    }
  }
}