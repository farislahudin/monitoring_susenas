<?php
  $dbhost = 'localhost';
  $dbusername = 'root';
  $dbpassword = '';
  $dbname = 'susenas_kaltara';

  $db = mysqli_connect($dbhost,$dbusername,$dbpassword,$dbname);

  if (!$db){
    die(mysqli_connect_error());
  }
  
?>