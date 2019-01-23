<?php
  //Including all the system and its files
  include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php";
  //Error Array
  $r = array();
  $r['error'] = false;
  $r['msg_error'] = "";
  //User Array
  $user = array();
  //Validaion - Email
  if(isset($_POST['email']) AND !empty($_POST['email'])){
    if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
        $user['email'] = clearString($con, $_POST['email']);
    }
    else{
      $r['error'] = true;
      $r['msg_error'] = "This email is not valid...";
      echo json_encode($r); exit;
    }
  }
  else{
    $r['error'] = true;
    $r['msg_error'] = "We're missing your email...";
    echo json_encode($r); exit;
  }
  //Validaion - Password
  if(isset($_POST['login_password']) AND !empty($_POST['login_password'])){
    $user['password'] = sha1($_POST['login_password']);
  }
  else{
    $r['error'] = true;
    $r['msg_error'] = "We're missing your password...";
    echo json_encode($r); exit;
  }


  if($r['error'] == false){
    //Validating Login
    if(match_email_password($con, $user)){
      $user_s = find_user_by_email($con, $user['email']);
      //Creating session
      $_SESSION['id'] = $user_s['id'];
      $_SESSION['name'] = $user_s['name'];
      $_SESSION['email'] = $user_s['email'];
      $_SESSION['type'] = $user_s['flag_type'];
      $_SESSION['picture'] = $user_s['picture'];
      $_SESSION['verified'] = $user_s['verified'];
      //COOKIES to remember user
			if(isset($_POST['remember']) && $_POST['remember'] == 1){
				//Make the cookie
        setcookie("pass_id", $user_s['id'],  time() + (172800 * 15), '/');
			}
      echo json_encode($r);
    }
    else{
      $r['error'] = true;
      $r['msg_error'] = "Your email or password is incorrect!";
      echo json_encode($r); exit;
    }
  }
 ?>
