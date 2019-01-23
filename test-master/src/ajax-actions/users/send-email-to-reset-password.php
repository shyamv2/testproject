<?php
  //Including all the system and its files
  include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php";
  //Error Array
  $r = array();
  $r['error'] = false;
  $r['msg_error'] = "";

if(!isset($_SESSION['id'])){
  if(isset($_POST['email_reset']) AND !empty($_POST['email_reset'])){
    //Validate EMAIL
    $email = clearString($con, $_POST['email_reset']);
    if(filter_var($email, FILTER_VALIDATE_EMAIL)){
      //Verify if email is registered
      if(!no_existant_email($con, $email)){
        //Create a key
        $key = sha1(time() . uniqid());
        //User
        $user = find_user_by_email($con, $email);
        //Send email
        if(register_password_reset_request($con, $user, $key)){
          if(send_email_to_reset_password($con, $user, $key)){
            echo json_encode($r);
          }
        }
      }
      else{
        $r['error'] = true;
        $r['msg_error'] = "We couldn't find this email. Check if the email is correct...";
        echo json_encode($r);
      }
    }
    else{
      $r['error'] = true;
      $r['msg_error'] = "This email is not valid...";
      echo json_encode($r);
    }
  }
  else{
    $r['error'] = true;
    $r['msg_error'] = "Type your email to continue...";
    echo json_encode($r);
  }
}

?>
