<?php
	header( "Content-Type: application/vnd.ms-excel; charset=utf-8" );
	header( "Content-disposition: attachment; filename=final-report-spreadsheet.xls" );

  include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php";

	if(isset($_GET['class_id']) AND is_numeric($_GET['class_id'])){
    $class_id = mysqli_real_escape_string($con, $_GET['class_id']);
    if(isset($_SESSION['id'])){
      $list_class_members = list_class_members($con, $class_id, 0);
			$list_assignments = list_class_assignments($con, $class_id);
    }
    else{
      exit;
    }
  }
  else{
    exit;
  }
	$assignments_list = "";
	foreach($list_assignments as $assign){
		$assignments_list .= $assign['title'] . "\t";
	}
	echo 'Student' . "\t" . $assignments_list . "Final Grade" . "\n";
  foreach($list_class_members as $member){
		if(user_class_permission($con, $_SESSION['id'], $class_id) > 1 OR $_SESSION['id'] == $member['id']){
			$list_a_grades = "";
			$f_grade = find_user_final_grade($con,  $class_id, $member['id']);
			foreach($list_assignments as $assign){
				$a_grade = find_user_assignment_grade($con, $assign['id'], $class_id, $member['id']);
				$list_a_grades .= $a_grade . "\t";
			}
		  echo $member['name'] . "\t" . $list_a_grades . $f_grade . "\n";
		}
  }
?>
