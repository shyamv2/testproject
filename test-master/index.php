<?php
  include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php";
  if(isset($_SESSION['id'])){
    include "templates/dashboard.php";
  }
  else{
    include "templates/home.php";
  }
 ?>
