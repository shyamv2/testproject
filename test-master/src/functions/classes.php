<?php
//Registry Class
function save_class($con, $class){
  $class['author_id'] = $_SESSION['id'];
  $sql = "INSERT INTO classes
  (author_id, name, description, start_date, end_date, code, agenda, privacy)
  VALUES
  (
    {$class['author_id']},
    '{$class['name']}',
    '{$class['description']}',
    '{$class['start_date']}',
    '{$class['end_date']}',
    '{$class['code']}',
    'Write here your agenda. It will be saved automatically.',
    {$class['privacy']}
  )";
  mysqli_query($con, $sql) or die(mysqli_error($con));
  $class_id = mysqli_insert_id($con);
  register_class_member($con, $class_id, $class['author_id'], 2);
  return $class_id;
}
//Register Member of a class
function register_class_member($con, $class_id, $member_id, $permission){
  $sql = "INSERT INTO class_members
  (class_id, member_id, permission)
  VALUES
  ($class_id, $member_id, $permission)";
  return mysqli_query($con, $sql) or die(mysqli_error($con));
}
//List classes to display on index page
function list_classes($con){
  $sql = "SELECT classes.*, users.name as user_name, users.id as user_id
  FROM classes
  INNER JOIN users ON classes.author_id = users.id
  ORDER BY classes.id DESC limit 30";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));;
  $classes = array();
  while($each = mysqli_fetch_assoc($r)){
    $classes[] = $each;
  }
  return $classes;
}
//List classes based on search words
function search_classes($con, $search){
  $sql = "SELECT classes.*, users.name as user_name, users.id as user_id
  FROM classes
  INNER JOIN users ON classes.author_id = users.id WHERE classes.name LIKE '%". $search . "%' AND classes.privacy = 0
  ORDER BY classes.id DESC limit 30";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));;
  $classes = array();
  while($each = mysqli_fetch_assoc($r)){
    $classes[] = $each;
  }
  return $classes;
}
//List classes by SESSION ID
function list_user_classes($con, $member_id = null){
  if(null === $member_id) {
    $member_id = $_SESSION['id'];
  }
  $sql = "SELECT  class_members.member_id, class_members.enrollment_id as enrollment_id, classes.*
  FROM class_members
  INNER JOIN classes
  ON class_id = classes.id
  WHERE member_id = {$member_id}
  ORDER BY id DESC";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));;
  $classes = array();
  while($each = mysqli_fetch_assoc($r)){
    $classes[] = $each;
  }
  return $classes;
}
//List classes as student by SESSION ID
function list_user_classes_as_student($con, $member_id){
  $sql = "SELECT  class_members.member_id, class_members.enrollment_id as enrollment_id, classes.*
  FROM class_members
  INNER JOIN classes
  ON class_id = classes.id
  WHERE member_id = {$member_id} AND permission = 0
  ORDER BY id DESC";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));;
  $classes = array();
  while($each = mysqli_fetch_assoc($r)){
    $classes[] = $each;
  }
  return $classes;
}
//Find Class by Id
function find_class($con, $class_id){
  $sql = "SELECT classes.*, users.id as author_id, users.name as author_name
  FROM classes
  INNER JOIN users
  ON classes.author_id = users.id
  WHERE classes.id = $class_id
  LIMIT 1";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));;;
  return mysqli_fetch_assoc($r);
}
//List all the members of a class
function list_class_members($con, $class_id, $permission){
  $sql = "SELECT * FROM class_members INNER JOIN users ON class_members.member_id = users.id WHERE class_members.class_id = $class_id AND class_members.permission = $permission ORDER BY users.name ASC limit 200";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  $users = array();
  while($each = mysqli_fetch_assoc($r)){
    $users[] = $each;
  }
  return $users;
}
function is_already_enrolled($con, $requester_id, $class_id){
  //is already in this class
  $sql_1 = "SELECT * FROM class_members WHERE member_id = $requester_id AND class_id = $class_id";
  $r_1 = mysqli_query($con, $sql_1);
  if(mysqli_num_rows($r_1) > 0){
    return true;
  }
  //has already requested to enroll
  $sql_2 = "SELECT * FROM enrollment_requests WHERE requester_id = $requester_id AND class_id = $class_id";
  $r_2 = mysqli_query($con, $sql_2);
  if(mysqli_num_rows($r_2) > 0){
    return true;
  }
  return false;
}
//Number of member requests in a certain class
function number_of_member_requests($con, $class_id){
  $sql = "SELECT * FROM enrollment_requests WHERE class_id = $class_id";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  return mysqli_num_rows($r);
}
//List class enrollment requests
function list_member_requests($con, $class_id){
  $sql = "SELECT * FROM enrollment_requests INNER JOIN users ON enrollment_requests.requester_id = users.id WHERE enrollment_requests.class_id = $class_id ORDER BY enrollment_requests.id DESC limit 100";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  $users = array();
  while($each = mysqli_fetch_assoc($r)){
    $users[] = $each;
  }
  return $users;
}
function remove_enrollment_request($con, $class_id, $requester_id){
  $sql = "DELETE FROM enrollment_requests WHERE class_id = $class_id AND requester_id = $requester_id";
  return mysqli_query($con, $sql) or die(mysqli_error($con));
}
//delete class
function delete_class($con, $class_id){
  $sql = "DELETE FROM classes WHERE id = " . $class_id;
  mysqli_query($con, $sql) or die(mysqli_error($con));
}
//delete class members
function delete_class_members($con, $class_id){
  $sql = "DELETE FROM class_members WHERE class_id = " . $class_id;
  mysqli_query($con, $sql) or die(mysqli_error($con));
}
//delete class posts (mural)
function delete_class_posts($con, $class_id){
  $sql = "DELETE FROM posts WHERE target_id = " . $class_id;
  mysqli_query($con, $sql) or die(mysqli_error($con));
}
//delete enrollment requests to that class
function delete_class_enrollment_requests($con, $class_id){
  $sql = "DELETE FROM enrollment_requests WHERE class_id = " . $class_id;
  mysqli_query($con, $sql) or die(mysqli_error($con));
}
//edit class
function edit_class($con, $class){
  $sql = "UPDATE classes SET
  name = '{$class['name']}',
  description = '{$class['description']}',
  start_date = '{$class['start_date']}',
  end_date = '{$class['end_date']}',
  privacy = {$class['privacy']}
  WHERE id = " . $class['id'];
  return mysqli_query($con, $sql) or die(mysqli_error($con));
}
//Verify if a member (usually the SESSION ID) is part of a class
function is_class_member($con, $user_id, $class_id){
  $sql = "SELECT * FROM class_members WHERE member_id = $user_id AND class_id = $class_id";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  if(mysqli_num_rows($r) > 0){
    return true;
  }
  return false;
}
//E-mail Body
function prepare_body($con, $data, $file){
	ob_start();
	include "{$_SERVER['DOCUMENT_ROOT']}/templates/emails/$file";
	$body = ob_get_contents();
	ob_end_clean();
	return $body;
}

//Send email to reset password
function send_email_to_class($con, $list_of_members, $sender, $email){
  //We'll send a link with the account password as GET, so in this link the magic happens
  //We use PHPMailer too
  $emailBody = prepare_body($con, $email, "email_to_class.php");
  $email = new PHPMailer(true);

  $email->isSMTP();
  $email->Host = "smtp.gmail.com";
  $email->Port = "587";
  $email->SMTPSecure = 'TSL';
  $email->SMTPAuth = true;
  $email->CharSet = 'UTF-8';
  $email->Username = "quickbackweb@gmail.com";
  $email->Password = "Ra19271996";
  $email->setFrom("contact@moonhots.org", "Moonshots Staff");
  // Digitar o e-mail do destinatário;
  foreach($list_of_members as $receiver_email){
    $email->addAddress($receiver_email);
  }
  // Digitar o assunto do e-mail;
  $email->Subject = $sender . " sent you a message Moonshots.";
  // Escrever o corpo do e-mail;
  $email->msgHTML($emailBody);
  // Usar a opção de enviar o e-mail.
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
//Find user permission to a given class
function user_class_permission($con, $member_id, $class_id){
  $sql = "SELECT permission FROM class_members WHERE member_id = $member_id AND class_id = $class_id limit 1";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  $member = mysqli_fetch_assoc($r);
  return $member['permission'];
}
//Find Class Id by Post Id
function find_class_id_by_post_id($con, $post_id){
  $sql = "SELECT target_id FROM posts WHERE id = $post_id";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));;;
  $class = mysqli_fetch_assoc($r);
  return $class['target_id'];
}
function find_class_id_by_code($con, $code){
  $sql = "SELECT id FROM classes WHERE code = '$code'";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  $class = mysqli_fetch_assoc($r);
  if(empty($class)){
    return false;
  }
  else{
    return $class['id'];
  }
}
//Find enrollment by id
function find_enrollment($con, $enrollment_id){
  $sql = "SELECT *
    FROM class_members
    WHERE enrollment_id = $enrollment_id limit 1";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  return mysqli_fetch_assoc($r);
}
function get_user_enrollment_id($con, $class_id, $member_id = NULL){
  if($member_id == NULL){
    $member_id = $_SESSION['id'];
  }
  $sql = "SELECT *
    FROM class_members
    WHERE class_id = $class_id and member_id = $member_id limit 1";
    $r = mysqli_query($con, $sql) or die(mysqli_error($con));
    $enrollment = mysqli_fetch_assoc($r);
    return $enrollment['enrollment_id'];
}
//It creates the CSS CLASS label for classes' Users
function display_class_label($con, $user_id, $class_id){
  $type = user_class_permission($con, $user_id, $class_id);
  switch ($type){
    case 0:
      return "student";
      break;
    case 1:
      return "tutor";
      break;
    case 2:
      return "teacher";
      break;
  }
}
function existant_class_code($con, $code){
  $sql = "SELECT COUNT(*) as num FROM classes WHERE code = '$code'";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  $r = mysqli_fetch_assoc($r);
  if($r['num'] > 0){
    return true;
  }
  return false;
}
function last_class($con, $user_id){
  $sql = "SELECT registry
  FROM classes WHERE author_id = $user_id ORDER BY id DESC limit 1";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  if(mysqli_num_rows($r) > 0){
    return mysqli_fetch_assoc($r);
  }
  return false;
}
function delete_assignment($con, $assign_id){
  $sql = "DELETE FROM assignments WHERE id = $assign_id";
  return mysqli_query($con, $sql) or die(mysqli_error($con));
}
?>
