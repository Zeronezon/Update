<?php
include_once "ui/connect.php";
session_start();

if (isset($_POST['btn_login'])) {
    $userInput = $_POST['txt_email'];
    $password = $_POST['txt_password'];

    // Check if the input is an email or username
    if (filter_var($userInput, FILTER_VALIDATE_EMAIL)) {
        // Input is an email
        $select = $pdo->prepare("SELECT * FROM tbl_user WHERE useremail = :email AND userpassword = :password");
        $select->bindParam(':email', $userInput);
    } else {
        // Input is a username
        $select = $pdo->prepare("SELECT * FROM tbl_user WHERE username = :username AND userpassword = :password");
        $select->bindParam(':username', $userInput);
    }

    // Bind password and execute query
    $select->bindParam(':password', $password);
    $select->execute();

    $row = $select->fetch(PDO::FETCH_ASSOC);

    if ($row !== false) {
        // Login success based on role
        if ($row['role'] == "Admin") {

            $_SESSION['status'] = "Login Success by Admin";
            $_SESSION['status_code'] = "success";

            header('refresh: 1; ui/dashboard.php');

        } elseif ($row['role'] == "User") {

            $_SESSION['status'] = "Login Success by User";
            $_SESSION['status_code'] = "success";
            
            header('refresh: 1; ui/user.php');
        }

        // Set session variables
        $_SESSION['userid'] = $row['userid'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['useremail'] = $row['useremail'];
        $_SESSION['role'] = $row['role'];

    } else {
        // Login failed
        $_SESSION['status'] = "Wrong Email/Username or Password";
        $_SESSION['status_code'] = "error";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Walter Mark | System</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">

</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="" class="h"><b>WALTERMARK</b>_<b>PRODUCT</b> System</a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Sign in to start your session</p>
      <form action="" method="post">
    <div class="input-group mb-3">
        <input type="text" class="form-control" placeholder="Email/Username" name="txt_email" required>
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-envelope"></span>
            </div>
        </div>
    </div>
    <div class="input-group mb-3">
        <input type="password" class="form-control" placeholder="Password" name="txt_password" required>
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-lock"></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-8">
            <a href="forgot-password.html">I forgot my password</a>
        </div>
        <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block" name="btn_login">Login</button>
        </div>
    </div>
</form>

    </div>
  </div>
</div>

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 -->
<script src="plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

<!-- SweetAlert2 Display for Login Status -->
<?php if (isset($_SESSION['status']) && $_SESSION['status'] != ''): ?>
<script>
$(function() {
    var Toast = Swal.mixin({
        toast: true,
        position: 'top',
        showConfirmButton: false,
        timer: 3000
    });

    Toast.fire({
        icon: '<?php echo $_SESSION['status_code']; ?>', // Use session status code for icon (success/error)
        title: '<?php echo addslashes($_SESSION['status']); ?>' // Dynamically add session status message
    });
});
</script>
<?php
    // Clear session status after displaying the message
    unset($_SESSION['status']);
    unset($_SESSION['status_code']);
endif;
?>
</body>
</html>
