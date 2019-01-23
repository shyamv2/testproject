<?php
include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php";
$r = array();
$r['success'] = false;
if(isset($_SESSION['id'])){
  if(isset($_POST['class_id']) AND is_numeric($_POST['class_id'])){
    $class_id = mysqli_real_escape_string($con, $_POST['class_id']);
    if(user_class_permission($con, $_SESSION['id'], $class_id) > 1){

      $class = find_class($con, $class_id);
      $list_class_members = list_class_members($con, $class_id, 0);
      $list_assignments = list_class_assignments($con, $class['id']);

      $data = array();
      $data['class_id'] = $class['id'];
      $data['class_name'] = $class['name'];
      //Send email to each student
      foreach($list_class_members as $member){
        $data['member_id'] = $member['id'];
        $data['member_email'] = $member['email'];
        $data['member_associated_email'] = $member['associated_email'];
        $data['member_name'] = $member['name'];
        $data['assigns_grades'] = "";
        $i = 1;
        foreach($list_assignments as $assign){
          $a_grade = find_user_assignment_grade($con, $assign['id'], $class['id'], $member['id']);
          if(!isset($a_grade) or empty(trim($a_grade))){
            $a_grade = "Not graded yet.";
          }
          $data['assigns_grades'] .= <<<EOD
            <div style="padding: 15px; border: 1px solid #eee; box-shadow: 1px 2px 6px rgba(0,0,0,.1); border-radius: 5px; background-color: #fafafa; margin-bottom: 10px">
            <h4 style="margin: 0">$i. $assign[title]</h4>
            <p style="font-size: 13px; margin: 5px 0;">$assign[description]</p>
            Grade: <b>$a_grade</b>
          </div>
EOD;
$i++;
        }
        $data['f_grade'] = find_user_final_grade($con,  $class['id'], $member['id']);
        if(!isset($data['f_grade']) or empty(trim($data['f_grade']))){
          $data['f_grade'] = "Not graded yet.";
        }
        $data['teacher_name'] = $_SESSION['name'];
        $data['teacher_email'] = $_SESSION['email'];
        send_final_report_email($con, $data);
      }

      $r['success'] = true;
      echo json_encode($r); exit;

    }
  }
}

?>
