<?php
header('Content-Type: text/html; charset=utf-8');
ini_set("default_charset", "UTF-8");
mb_internal_encoding("UTF-8");
iconv_set_encoding("internal_encoding", "UTF-8");
iconv_set_encoding("output_encoding", "UTF-8");
require("signup.php");
if(isset($_POST['un'])){
  $un=$_POST['un'];
try{
  require("conecction.php");
  $rs=$db->query("SELECT * FROM users WHERE username='$un'");
   $count = $rs->fetchColumn();
  if ($count>0)
    echo "invalid";
  else {
    echo "valid";
  }
  $db =null;
  }
  catch(PDOException $ex) { //ex is the error object
      die ("Error Message ".$ex->getMessage());
  }
}





  ?>
