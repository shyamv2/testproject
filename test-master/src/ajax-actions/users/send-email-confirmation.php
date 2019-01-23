<?php
  //Including all the system and its files
  include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php";
  //Error Array
  $r = array();
  $r['success'] = false;


if(isset($_SESSION['id'])){
  if($_SESSION['verified'] == 0){
    $user = find_user($con, $_SESSION['id']);
    $user['secret_key'] = sha1($user['email'] . time() . uniqid());
    register_email_validation_request($con, $user);
    //PHPMAILER
    $emailBody = prepare_body($con, $user, "email_validation.php");
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
    $email->addAddress($user['email']);
    $email->Subject = "Email Validation for iStudy.";;
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
}

?>
