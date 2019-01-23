<?php
	header( "Content-Type: application/vnd.ms-excel; charset=utf-8" );
	header( "Content-disposition: attachment; filename=class-members-spreadsheet.xls" );

  include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php";

	if(isset($_GET['class_id']) AND is_numeric($_GET['class_id'])){
    $class_id = mysqli_real_escape_string($con, $_GET['class_id']);
    if(isset($_SESSION['id']) AND user_class_permission($con, $_SESSION['id'], $class_id) > 0){
      $list_class_members = list_class_members($con, $class_id, 0);
    }
    else{
      exit;
    }
  }
  else{
    exit;
  }

	// for example:
	echo 'Student' . "\t" . 'Grade' . "\n";
  foreach($list_class_members as $member){
	   echo $member['name'] . "\t" . "" . "\n";
  }
?>
