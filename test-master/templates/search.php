<!DOCTYPE html>
<!--System file (php functions)-->
<?php include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php" ?>
<?php
  //Verify if user is logged in
  if(!isset($_SESSION['id'])) {
      header("location: /");
      exit;
  }
  if(!isset($_GET['search'])){
    header("location: /");
    exit;
  }
  else{
    $search = mysqli_real_escape_string($con, $_GET['search']);
    //List Users
    $search_users = search_users($con, $search);
    //List Classes
    $search_classes = search_classes($con, $search);
  }
?>
<html>
<head>
  <title>Results for <?php echo $search ?> | iStudy </title>
  <!--Head file (css and libraries)-->
  <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/head.php" ?>
</head>
<body>
  <!--MENUS (TOP and LEFT)-->
  <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/menu-top.php" ?>
  <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/menu-left.php" ?>

  <!--CONTENT-->
  <div class="content">

    <div class="row row-content">
      <div class="col-sm-12">
      <div class="panel" style="padding: 0">
        <div class="panel-header">Results for <?php echo htmlentities($search); ?></div>
          <div class="panel-body">
          <!-- Nav tabs -->
          <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" data-toggle="tab" href="#profiles" role="tab">Profiles</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#classes" role="tab">Classes</a>
            </li>
          </ul>

          <!-- Tab panes -->
          <div class="tab-content">
            <br>
            <div class="tab-pane active" id="profiles" role="tabpanel">
              <?php if(count($search_users) == 0){
                echo "No profiles were found for your search! Try using different words.";
              } ?>
              <div class="list-group">
              <?php foreach($search_users as $user) : ?>
                <li href="/profile/<?php echo $user['id'] . '-' . linka($user['name']) ?>" class="list-group-item justify-content-between list-profile">
                  <div><a href="/profile/<?php echo $user['id'] . '-' . linka($user['name']) ?>" style="all: unset; cursor: pointer"><img src="<?php echo $user['picture'] ?>">
                  <b><?php echo $user['name'] ?></b> | <?php echo $user['email'] ?></a></div>
                  <div>
                    <div class="btn-group" role="group" aria-label="Basic example">
                      <a href="/messages?chatwith=<?php echo $user['id'] ?>" class="btn btn-primary"><i class="fa fa-envelope"></i> Message</a>
                    </div>
                  </div>
                </li>
              <?php endforeach; ?>
              </div>
            </div>


            <div class="tab-pane" id="classes" role="tabpanel">
              <?php if(count($search_classes) == 0){
                echo "No classes were found for your search! Try using different words.";
              } ?>
              <?php foreach($search_classes as $class) : ?>
                <div class="class-box">
                  <!--Author/Professor-->
                  <div class="class-box-author">
                    <a href="/profile/<?php echo $class['user_id'] . '-' . linka($class['user_name']) ?>">
                      <?php echo $class['user_name'] ?>
                    </a>
                  </div>
                  <!--Name/Title-->
                  <div class="class-box-title">
                    <?php echo $class['name'] ?>
                  </div>
                  <?php if(!is_already_enrolled($con, $_SESSION['id'], $class['id'])) : ?>
                  <!--Request enrollment button-->
                  <div style="float: right">
                    <button type="button" class="btn btn-primary" onclick="requestEnrollment(this, <?php echo $class['id'] ?>)">Request Enrollment</button>
                  </div>
                  <?php endif; ?>
                  <!--Description-->
                  <div class="class-box-description">
                    <?php echo limitName($class['description'], 300) ?>
                  </div>
                  <!--Dates (start-end)-->
                  <div class="class-work-dates">
                    <i class="fa fa-calendar"></i>
                    <?php echo translateDateHalf($class['start_date']) ?> - <?php echo translateDateHalf($class['end_date']) ?>
                  </div>
                </div>
                <hr>
              <?php endforeach; ?>
            </div>

          </div>

        </div>
      </div>
    </div>
  </div>

  <!--Footer-->
  <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/footer.php" ?>

</body>
</html>
