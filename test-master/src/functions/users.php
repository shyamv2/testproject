<?php
//Verify if certain email exists, returning true in case of no existance
function no_existant_email($con, $email, $logged_in = false){
  $email = mysqli_real_escape_string($con, $email);
  $sql = "SELECT email, id FROM users WHERE email = '$email'";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  if(mysqli_num_rows($r) > 0){
    if($logged_in == true){
      $r = mysqli_fetch_assoc($r);
      if($r['id'] == $_SESSION['id']){
        return true;
      }
      return false;
    }
    return false;
  }
  return true;
}
//Signing user up, registring it
function sign_user_up($con, $user){
  $sql = "INSERT INTO users
  (name, email, agenda, password, ip, flag_type, verified)
  VALUES
  (
    '{$user['name']}',
    '{$user['email']}',
    'This is your agenda. It automatically saves itself!',
    '{$user['password']}',
    '{$user['ip']}',
    {$user['type']},
    {$user['verified']}
  )";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  return mysqli_insert_id($con);
}
//Finding user by ID
function find_user($con, $user_id){
  $sql = "SELECT * FROM users WHERE id = " . $user_id;
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  return mysqli_fetch_assoc($r);
}
//Verifying if email and password match to log user in
function match_email_password($con, $user){
  $sql = "SELECT * FROM users WHERE email = '{$user['email']}' AND password = '{$user['password']}' limit 1";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  if(mysqli_num_rows($r) > 0){
    return true;
  }
  return false;
}
//Finding user by email
function find_user_by_email($con, $email){
  $sql = "SELECT * FROM users WHERE email = '$email'";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  return mysqli_fetch_assoc($r);
}
//Edit User Profile Info
function edit_profile($con, $user){
  $sql = "UPDATE users SET
  name = '{$user['name']}',
  email = '{$user['email']}',
  associated_email = '{$user['associated_email']}',
  flag_type = {$user['flag_type']},
  bio = '{$user['bio']}',
  school = '{$user['school']}',
  birthdate = '{$user['birthdate']}',
  genre = {$user['genre']},
  verified = {$user['verified']}
  WHERE id = {$user['id']}";
  return mysqli_query($con, $sql) or die(mysqli_error($con));
}
//Register password reset request
function register_password_reset_request($con, $user, $key){
  $sql = "INSERT INTO password_reset_requests
  (user_id, secret_key, valid)
  VALUES
  (
    {$user['id']},
    '{$key}',
    1
  )";
  return mysqli_query($con, $sql) or die(mysqli_error($con));
}
//Send email to reset password
function send_email_to_reset_password($con, $user, $key){
  //PHPMAILER
  $user['key'] = $key;
  $emailBody = prepare_body($con, $user, "reset_password.php");
  $email = new PHPMailer(true);

  $email->isSMTP();
  $email->Host = "smtp.gmail.com";
  $email->Port = "587";
  $email->SMTPSecure = 'TSL';
  $email->SMTPAuth = true;
  $email->CharSet = 'UTF-8';
  $email->Username = "quickbackweb@gmail.com";
  $email->Password = "72257647Ra19271996a";
  $email->setFrom("istudyplatform@gmail.com", "iStudy Team");
  $email->addAddress($user['email']);
  $email->Subject = "Reset Your iStudy Password";
  $email->msgHTML($emailBody);
  $email->SMTPOptions = array(
    'ssl' => array(
        'verify_peer'  => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
  );
  $email->send();
  return true;
}
function validate_password_reset_request($con, $user, $key){
  $sql = "SELECT * FROM password_reset_requests WHERE secret_key = '$key' AND valid = 1 limit 1";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  if(mysqli_num_rows($r) > 0){
    return true;
  }
  return false;
}
function reset_password($con, $password, $user_id){
  $reset_sql = "UPDATE users SET
  password = '$password'
  WHERE id = $user_id";
  mysqli_query($con, $reset_sql) or die(mysqli_error($con));
  $unvalid_requests_sql = "UPDATE password_reset_requests SET
  valid = 0
  WHERE user_id = $user_id";
  return mysqli_query($con, $unvalid_requests_sql) or die(mysqli_error($con));
}
//List users based on search words
function search_users($con, $search){
  $logged = $_SESSION['id'];
  $sql = "SELECT * FROM users WHERE (name LIKE '%". $search . "%' OR email LIKE '%". $search . "%') AND id != $logged ORDER BY name ASC limit 30";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  $users = array();
  while($each = mysqli_fetch_assoc($r)){
    $users[] = $each;
  }
  return $users;
}
function is_following($con, $followed_id){
  $follower_id = $_SESSION['id'];
  $sql = "SELECT id FROM follows WHERE followed_id = $followed_id AND follower_id = $follower_id limit 1";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  if(mysqli_num_rows($r) > 0){
    return true;
  }
  return false;
}
function num_following($con, $user_id){
  $sql = "SELECT COUNT(*) as num FROM follows WHERE follower_id = $user_id";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  $r = mysqli_fetch_assoc($r);
  return $r['num'];
}
function num_followed($con, $user_id){
  $sql = "SELECT COUNT(*) as num FROM follows WHERE followed_id = $user_id";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  $r = mysqli_fetch_assoc($r);
  return $r['num'];
}

function send_feedback($con, $feedback){
    //PHPMAILER
    $emailBody = prepare_body($con, $feedback, "give_feedback.php");
    $email = new PHPMailer(true);
    $email->isSMTP();
    $email->Host = "smtp.gmail.com";
    $email->Port = "587";
    $email->SMTPSecure = 'TSL';
    $email->SMTPAuth = true;
    $email->CharSet = 'UTF-8';
    $email->Username = "quickbackweb@gmail.com";
    $email->Password = "72257647Ra19271996a";
    $email->setFrom("istudyplatform@gmail.com", "iStudy Platform");
    $email->addAddress("arturbarbosa10@gmail.com");
    $email->Subject = "Feedback: Session ID: " . $_SESSION['id'] . ' Time: ' . time();
    $email->msgHTML($emailBody);
    $email->SMTPOptions = array(
        'ssl' => array(
            'verify_peer'  => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );
    $email->send();
    return true;
}
function get_feed_news($con){
  $user_id = $_SESSION['id'];
  $sql = "SELECT posts.* FROM follows
  INNER JOIN (SELECT posts.*, users.name as author_name, users.picture as author_picture FROM posts LEFT JOIN users ON posts.author_id = users.id) AS posts
  ON follows.followed_id = posts.author_id
  WHERE (follows.follower_id = $user_id OR posts.author_id = $user_id)
  AND posts.target = 'profile' GROUP BY posts.id ORDER BY posts.id DESC limit 3";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  $posts = array();
  while($each = mysqli_fetch_assoc($r)){
    $posts[] = $each;
  }
  return $posts;
}
function send_final_report_email($con, $data){
  //PHPMAILER
  $emailBody = prepare_body($con, $data, "final_report.php");
  $email = new PHPMailer(true);

  $email->isSMTP();
  $email->Host = "smtp.gmail.com";
  $email->Port = "587";
  $email->SMTPSecure = 'TSL';
  $email->SMTPAuth = true;
  $email->CharSet = 'UTF-8';
  $email->Username = "quickbackweb@gmail.com";
  $email->Password = "72257647Ra19271996a";
  $email->setFrom($data['teacher_email'], $data['teacher_name']);
  $email->addAddress($data['member_email']);
  if(!empty(trim($data['member_associated_email']))){
    $email->addAddress($data['member_associated_email']);
  }
  $email->Subject = "Your " . $data['class_name'] . " official report has been released.";
  $email->msgHTML($emailBody);
  $email->SMTPOptions = array(
    'ssl' => array(
        'verify_peer'  => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
  );
  $email->send();
  return true;
}
function send_welcoming_email($con, $user){
  //Create code to validate email
  $user['secret_key'] = sha1($user['email'] . time() . uniqid());
  register_email_validation_request($con, $user);
  //PHPMAILER
  $emailBody = prepare_body($con, $user, "welcome_email.php");
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
  $email->Subject = "Welcome to iStudy, " . $user['name'] . "! + Validate Your Email.";;
  $email->msgHTML($emailBody);
  $email->SMTPOptions = array(
    'ssl' => array(
        'verify_peer'  => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
  );
  $email->send();
  return true;
}
function register_email_validation_request($con, $user){
  $sql = "INSERT INTO email_validation (user_id, secret_key) VALUES ({$user['id']}, '{$user['secret_key']}')";
  return mysqli_query($con, $sql) or die(mysqli_error($con));
}
function num_association_requests($con, $user_id){
  $sql = "SELECT COUNT(*) as total FROM account_association_requests WHERE receiver_id = $user_id";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  $r = mysqli_fetch_assoc($r);
  return $r['total'];
}
function list_association_requests($con, $user_id){
  $sql = "SELECT account_association_requests.id as association_id, users.* FROM account_association_requests
  INNER JOIN users ON account_association_requests.requester_id = users.id WHERE account_association_requests.receiver_id = $user_id
  ORDER BY account_association_requests.id DESC";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  $requests = array();
  while($each = mysqli_fetch_assoc($r)){
    $requests[] = $each;
  }
  return $requests;
}
function no_existant_association_request($con, $requester_id, $receiver_id){
  $sql = "SELECT COUNT(*) as total FROM account_association_requests WHERE receiver_id = $receiver_id AND requester_id = $requester_id";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  $r = mysqli_fetch_assoc($r);
  if($r['total'] >= 1){
    return false;
  }
  return true;
}
function register_association_request($con, $request){
  $sql = "INSERT INTO account_association_requests
  (requester_id, receiver_id)
  VALUES
  ({$request['requester_id']}, {$request['receiver_id']})";
  return mysqli_query($con, $sql) or die(mysqli_error($con));
}
function register_account_association($con, $association){
  $sql = "INSERT INTO account_associations
  (account_id, associated_with_id)
  VALUES
  ({$association['account_id']}, {$association['associated_with_id']})";
  return mysqli_query($con, $sql) or die(mysqli_error($con));
}
function remove_association_request($con, $requester_id, $receiver_id){
  $sql = "DELETE FROM account_association_requests WHERE requester_id = $requester_id AND receiver_id = $receiver_id";
  return mysqli_query($con, $sql) or die(mysqli_error($con));
}
function no_existant_account_association($con, $account_id, $associated_with_id){
  $sql = "SELECT COUNT(*) as total FROM account_associations WHERE account_id = $account_id AND associated_with_id = $associated_with_id";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  $r = mysqli_fetch_assoc($r);
  if($r['total'] >= 1){
    return false;
  }
  return true;
}
function list_associated_accounts($con){
  $associated_with_id = $_SESSION['id'];
  $sql = "SELECT account_associations.id as association_id, users.id as user_id, users.name as name, users.picture as picture
  FROM account_associations INNER JOIN users ON account_associations.account_id = users.id WHERE associated_with_id = $associated_with_id
  ORDER BY account_associations.id DESC";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  $associations = array();
  while($each = mysqli_fetch_assoc($r)){
    $associations[] = $each;
  }
  return $associations;
}
function validate_secret_key_for_email($con, $user){
  $sql = "SELECT COUNT(*) as total FROM email_validation WHERE user_id = {$user['id']} AND secret_key = '{$user['secret_key']}'";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  $r = mysqli_fetch_assoc($r);
  if($r['total'] >= 1){
    return true;
  }
  return false;
}
 ?>
