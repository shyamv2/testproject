<?php
include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php";
//result
$r = array();
$r['error'] = false;
$r['msg_error'] = "";
//Class Array
$lesson = array();
//Verifying if there's SESSION
if(isset($_SESSION['id'])){
  if(isset($_POST['class_id']) AND is_numeric($_POST['class_id'])){
    $class = find_class($con, mysqli_real_escape_string($con, $_POST['class_id']));
    $lesson['class_id'] = $class['id'];
    if(user_class_permission($con, $_SESSION['id'], $class['id']) < 1){
      exit;
    }
    //NAME
    if(isset($_POST['name']) AND !empty(trim($_POST['name']))){
      $lesson['name'] = clearString($con, $_POST['name']);
    }
    else{
      $r['error'] = true;
      $r['msg_error'] = "We're missing the name...";
      echo json_encode($r); exit;
    }
    //SKILLS
    if(isset($_POST['skills']) AND !empty(trim($_POST['skills']))){
      $lesson['skills'] = clearString($con, $_POST['skills']);
    }
    else{
      $r['error'] = true;
      $r['msg_error'] = "We're missing the second input...";
      echo json_encode($r); exit;
    }
    //TYPE
    if(isset($_POST['type']) AND !empty($_POST['type'])){
      if($_POST['type'] == 1 OR $_POST['type'] == 2){
        $lesson['type'] = mysqli_real_escape_string($con, $_POST['type']);
      }
    }
    else{
      $r['error'] = true;
      $r['msg_error'] = "What type is this lesson?";
      echo json_encode($r); exit;
    }
    //COVER LINK
    if(isset($_POST['cover_link']) AND !empty(trim($_POST['cover_link']))){
      $lesson['cover_link'] = clearString($con, $_POST['cover_link']);
      if(filter_var($lesson['cover_link'], FILTER_VALIDATE_URL)){
        if($lesson['type'] == 2){
          if(stripos($lesson['cover_link'], 'youtu') > 0){
            preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $lesson['cover_link'], $matches);
            $lesson['cover_link'] = $matches[1];
          }
          else{
            $r['error'] = true;
            $r['msg_error'] = "Insert a valid YOUTUBE link...";
            echo json_encode($r); exit;
          }
        }
      }
      else{
        $r['error'] = true;
        $r['msg_error'] = "Insert a valid URL for the cover...";
        echo json_encode($r); exit;
      }
    }
    else{
      $r['error'] = true;
      $r['msg_error'] = "We're missing the cover link...";
      echo json_encode($r); exit;
    }

    //Article
    if(isset($_POST['article']) AND !empty(trim($_POST['article']))){
      $lesson['article'] = mysqli_real_escape_string($con, $_POST['article']);
    }
    else{
      $r['error'] = true;
      $r['msg_error'] = "We're missing the article...";
      echo json_encode($r); exit;
    }
  }
}
else{
  exit;
}
if($r['error'] == false){
  $r['id'] = save_lesson($con, $lesson);
  $r['link'] = "/lesson/" . $r['id'] . "-" . linka($lesson['name']);
}
echo json_encode($r);
 ?>
