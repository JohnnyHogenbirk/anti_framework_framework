<?php
// call by view_functions
// ouput: dataset
function contact_mail($rows, $result_total){
  // $ids is combi: id form # id row
  $topic = $_POST["topic"];
  $name = $_POST["name"];
  $email = $_POST["email"];
  $text = "Topic: $topic\r\nName: $name\r\nText:\r\n" . $_POST["text"];
  $mailheader = "Reply-To: Johnny Hogenbirk <email@johnnyhogenbirk.nl>\r\n";
  $mailheader .= "Return-Path: Johnny Hogenbirk <email@johnnyhogenbirk.nl>\r\n";
  $mailheader .= "From: Johnny Hogenbirk <email@johnnyhogenbirk.nl>\r\n";
  $mailheader .= "MIME-Version: 1.0\r\n";
  $mailheader .= "Content-type: text/plain;charset=UTF-8\r\n";
  $mailheader .= "X-Mailer: PHP" . phpversion();
  mail($email, "Email from AFF", $text, $mailheader);
  return $result_total;
}