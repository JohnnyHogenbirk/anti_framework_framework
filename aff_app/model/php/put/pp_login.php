<?php
// call by view_functions
// check username and password
function login($rows, $result_total){
  // $ids is combi: id form # id row
  $user_name = trim($_POST["username"]);
  $password = trim($_POST["password"]);
  $login_done = "no";
  $alert = "";
  // notice: normal the users are stored in a database, with encrypted password
  // for demo purposes a text file is used
  $rows = f_m_get_txt_data("model/data/d_user.txt", "*", "", "", "", "login");
  for($i = 0; $i < count($rows); $i++){
    if(($rows[$i]["user_name"] == $user_name) and ($rows[$i]["password"] == $password)){
      // make session
      $_SESSION["user_id"] = $rows[$i]["id"];
      $_SESSION["role"] = $rows[$i]["role"];
      $uri_arr = explode("?", $_SERVER["REQUEST_URI"]);
      $_SESSION["url"] = $_SERVER["HTTP_HOST"] . $uri_arr[0];
      $login_done = "yes";
      // reload browser
      $result_total["javascript"] = "location.reload();";
      break;
    }
  }
  if($login_done == "no"){
    // next view must be login
    $result_total["javascript"] .= "f_get_content('login', '')";
    $alert = "Wrong username or password";
  }
  $result_total["alert"]["text"] .= $alert;
  return $result_total;
}
