<?php
  //Including all the system and its files
  include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php";
  //Error Array
  $r = array();
  $r['success'] = false;


if(isset($_SESSION['id'])){
  if($_SESSION['verified'] == 1){
    if((isset($_POST['email']) AND !empty(trim($_POST['email']))) AND filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
      $user = find_user($con, $_SESSION['id']);
      $user['invited'] = mysqli_real_escape_string($con, $_POST['email']);
      //PHPMAILER
      $emailBody = prepare_body($con, $user, "email_invitation.php");
      $email = new PHPMailer(true);

      $email->isSMTP();
      $email->Host = "smtp.gmail.com";
      $email->Port = "587";
      $email->SMTPSecure = 'TSL';
      $email->SMTPAuth = true;
      $email->CharSet = 'UTF-8';
      $email->Username = "quickbackweb@gmail.com";
      $email->Password = "72257647Ra19271996a";
      $email->setFrom("contact@inventxr.com", "iStudy Team");
      $email->addAddress($user['invited']);
      $email->Subject = $user['name'] . " invited you to join iStudy!";;
      $email->msgHTML($emailBody);
      $email->SMTPOptions = array(
        'ssl' => array(
            'verify_peer'  => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
      );
      if($email->send()){
        $r['success'] = true;
        echo json_encode($r);
      }
    }
    else{
      exit;
    }
  }
}

?>
