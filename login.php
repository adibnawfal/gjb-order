<?php include('./src/components/server.php');
include('./src/components/pagevalidation.php') ?>
<!DOCTYPE html>
<html>

<head>
  <title>Login</title>
  <link rel="stylesheet" type="text/css" href="./src/css/style.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>

<body class="login">
  <?php include('./header.php') ?>
  <div class="container">
    <div class="left"></div>
    <div class="right">
      <p class="title">Log In</p>
      <p class="desc">Don't have an account? <a href="register.php" style="font-weight: 600;">Sign Up</a></p>
      <form method="post" action="login.php" class="login-form">
        <?php include('./src/components/errors.php'); ?>
        <input type="text" name="username" placeholder="Username">
        <input type="password" name="password" placeholder="Password">
        <a href="forgotpassword.php <?php
        unset($_SESSION['isSelectMenu']);
        unset($_SESSION['isEditOrder']);
        unset($_SESSION['isViewOrders']);
        unset($_SESSION['editOrderKey']);
        ?>" class="forgot-password">Forgot Password?</a>
        <button type="submit" name="login">Login</button>
      </form>
    </div>
  </div>

</body>

</html>