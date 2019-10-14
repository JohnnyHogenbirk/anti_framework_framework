<?php
// include by api.php
// functions that will be uses at the start or page refresh
function f_s_javascript(){
  // get all the standard javascript, needed for general functions (not view specific functions)
  $result = "";
  // the place where all standard javascript is stored
  $dir_name = "view/javascript/start";
  $files = f_m_get_files($dir_name, "f_");
  // loop over every javascript files: add the (minified) text to $result
  for($i = 0; $i < count($files); $i++){
    $rows = f_m_get_txt("$dir_name/" . $files[$i], "", "file js read");
    $result .= s_v_get_js_minify($rows, "", "");
  }
  return $result;
}

function f_s_style(){
  // does get the style out of the database
  // (for this demo, out of txt files)
  // it is possible to select nav items based on the app, role, person, etc.
  $result = "";
  // get the style data
  $style = f_m_get_txt_data("model/data/d_sys_style.txt", "*", "id_app='xx'", "", "", "f_style");
  // needed to detect if a style is complete and can be ended by }
  $last_object = "";
  // it is posible to work with var's in the style database
  $style_var = array();
  $s = 0;
  // get the var's first, loop over the style, search for object type var
  for($i = 0; $i < count($style); $i++){
    if($style[$i]["object"] == "var"){
      $style_var[$s]["style_name"] = $style[$i]["style_name"];
      $style_var[$s]["style_value"] = $style[$i]["style_value"];
      $s++;
    }
  }
  // now loop over the style
  for($i = 0; $i < count($style); $i++){
    // now not the object type var
    if($style[$i]["object"] != "var"){
      // detect when a style must by opened: when the object name is different to the last
      if($last_object != $style[$i]["object"]){
        $result .= $style[$i]["object"] . "{";
        $last_object = $style[$i]["object"];
      }
      // replace var's in $style[$i]["style_value"] by $style_var
      for($p = 0; $p < count($style_var); $p++){
        $style[$i]["style_value"] = str_replace("@" . $style_var[$p]["style_name"], $style_var[$p]["style_value"], $style[$i]["style_value"]);
      }
      /// add the style to the $result
      $result .= $style[$i]["style_name"] . ":" . $style[$i]["style_value"] . ";";
      // detect when a style must by closed: when the next object name is different
      if($i < count($style) - 1){
        if($style[$i + 1]["object"] != $style[$i]["object"]){
          $result .= "}";
        }
      }
    }
  }
  // always close the last
  $result .= "}";
  return $result;
}

function f_s_html(){
  // make html for body: header, article and nav
  // (elements and content)
  $result_arr = array();
  $result_arr = f_s_html_header($result_arr);
  $result_arr = f_s_html_nav($result_arr);
  $result_arr = f_s_html_article($result_arr);
  $result_arr = f_s_html_footer($result_arr);

  return $result_arr;
}

function f_s_html_header($result_arr){
  // make data for header
  // view/javascript/dynamic/f_add_html.js will create the elements
  $n_h = 0;
  $result_arr[$n_h] = "el_body|header|id=el_header";
  $n_h++;
  $result_arr[$n_h] = "el_header|svg|class=svg_header_nav|id=el_svg_header_nav|onclick=f_nav_move('div')";
  $n_h++;
  $result_arr[$n_h] = "el_svg_header_nav|line|x1=0|y1=7|x2=32|y2=7|class=svg_header_nav_line";
  $n_h++;
  $result_arr[$n_h] = "el_svg_header_nav|line|x1=0|y1=15|x2=32|y2=15|class=svg_header_nav_line";
  $n_h++;
  $result_arr[$n_h] = "el_svg_header_nav|line|x1=0|y1=23|x2=32|y2=23|class=svg_header_nav_line";
  $n_h++;
  $result_arr[$n_h] = "el_header|div|class=svg_header_title|id=svg_header_title|innerHTML=Anti Framework Framework";
  $n_h++;

  return $result_arr;
}

function f_s_html_nav($result_arr){
  // make data for nav
  // view/javascript/dynamic/f_add_html.js will create the elements
  $n_h = count($result_arr);
  // declaration var's for for-loop
  $ul_nr = 0;
  $id_ul_this = "ul_$ul_nr";
  $actual_niv = 1;
  $id_ul_niv[$actual_niv] = $id_ul_this;

  // add nav
  $result_arr[$n_h] = "el_body|nav|class=el_nav|id=el_nav";
  $n_h++;

  // arrow to the right, to fixe nav element, so you can see him all the time
  $result_arr[$n_h] = "el_nav|svg|class=svg_nav_arrow svg_nav_arrow_right|id=el_nav_svg_1|onclick=f_nav_fixed()";
  $n_h++;
  $result_arr[$n_h] = "el_nav_svg_1|line|x1=5|y1=10|x2=20|y2=10|class=svg_nav_arrow_line";
  $n_h++;
  $result_arr[$n_h] = "el_nav_svg_1|line|x1=15|y1=5|x2=20|y2=10|class=svg_nav_arrow_line";
  $n_h++;
  $result_arr[$n_h] = "el_nav_svg_1|line|x1=15|y1=15|x2=20|y2=10|class=svg_nav_arrow_line";
  $n_h++;

  // arrow to the left, to hide nav element (if fixed)
  $result_arr[$n_h] = "el_nav|svg|class=svg_nav_arrow svg_nav_arrow_left|id=el_nav_svg_2|onclick=f_nav_fixed()";
  $n_h++;
  $result_arr[$n_h] = "el_nav_svg_2|line|x1=5|y1=10|x2=20|y2=10|class=svg_nav_arrow_line";
  $n_h++;
  $result_arr[$n_h] = "el_nav_svg_2|line|x1=5|y1=10|x2=10|y2=5|class=svg_nav_arrow_line";
  $n_h++;
  $result_arr[$n_h] = "el_nav_svg_2|line|x1=5|y1=10|x2=10|y2=15|class=svg_nav_arrow_line";
  $n_h++;

  // div around ul
  $result_arr[$n_h] = "el_nav|div|class=el_nav_div|id=el_nav_div";
  $n_h++;

  // add main ul
  $result_arr[$n_h] = "el_nav_div|ul|class=el_nav_ul|id=$id_ul_this";
  $n_h++;

  // get data for li's, depending on login done or not and, after login, on role
  if(isset($_SESSION["user_id"])){
    $login_done = "yes";
  }else{
    $login_done = "no";
  }
  $nav = f_m_get_txt_data("model/data/d_sys_nav.txt", "*", "login_done='$login_done'", "order", "", "f_html_nav");

  // if the user has not logged in, give the role 'all'
  if(!isset($_SESSION["role"])){
    $_SESSION["role"] = "all";
  }

  for($i = 0; $i < count($nav); $i++){
    // if login is done, then check role
    $take_this_one = 1;
    if($login_done == "yes"){
      if(($nav[$i]["role"] != "all") and ($nav[$i]["role"] != $_SESSION["role"])){
        $take_this_one = 0;
      }
    }
    if($take_this_one == 1){
      // always upper
      $nav[$i]["nav_title"] = strtoupper($nav[$i]["nav_title"]);
      // check if next nav item is less deep
      if($nav[$i]["niv"] < $actual_niv){
        $actual_niv--;
        // give var the id of the previous ul, for binding next ul or li
        $id_ul_this = $id_ul_niv[$actual_niv];
      }
      if($nav[$i]["type"] == "head"){
        // because in the next li there must be the number of the next ul (for f_nav_open_close), add one to $ul_nr
        $ul_nr++;
        $result_arr[$n_h] = "$id_ul_this|li|class=el_nav_item|id=li_$n_h|onclick=f_nav_open_close($ul_nr)|innerHTML=" . $nav[$i]["nav_title"];
        $n_h++;
        $id_ul_next = "ul_$ul_nr";
        $result_arr[$n_h] = "$id_ul_this|ul|class=el_nav_ul|id=$id_ul_next|style=display:none";
        $n_h++;
        $actual_niv++;
        $id_ul_this = "ul_$ul_nr";
        $id_ul_niv[$actual_niv] = $id_ul_this;
      }else{
        // add li
        $result_arr[$n_h] = "$id_ul_this|li|class=el_nav_item|id=li_$n_h|onclick=f_remove_alert();f_nav_move('li');f_get_content('" . $nav[$i]["nav_id"] . "', '')|innerHTML=" . $nav[$i]["nav_title"];
        $n_h++;
      }
    }
  }
  return $result_arr;
}

function f_s_html_article($result_arr){
  $n_h = count($result_arr);
  // empty element, will be filled because of call in index.html, call of the view home
  $result_arr[$n_h] = "el_body|article|class=el_article|id=el_article";
  $n_h++;
  // exeption, height not by class but inline, because of the close functionality
  $result_arr[$n_h] = "el_article|div|class=el_alert|id=el_article_alert|onclick=f_remove_alert()";
  $n_h++;
  $result_arr[$n_h] = "el_article|div|class=el_article_content|id=el_article_content";
  $n_h++;
  $result_arr[$n_h] = "el_body|script|f_get_content('home', '');";
  return $result_arr;
}

function f_s_html_footer($result_arr){
  // nothing, yet
  return $result_arr;
}
