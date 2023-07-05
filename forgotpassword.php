<?php include('./src/components/server.php');
include('./src/components/pagevalidation.php') ?>
<!DOCTYPE html>
<html>

<head>
  <title>Forgot Password?</title>
  <link rel="stylesheet" type="text/css" href="./src/css/style.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>

<body class="forgot-password">
  <?php include('./header.php') ?>
  <div class="container">
    <div class="left"></div>
    <div class="right">
      <p class="title">Forgot Password</p>
      <p class="desc">Enter the email address associated with your account.</p>
      <form method="post" action="forgotpassword.php" class="forgotpass-form">
        <?php include('./src/components/errors.php'); ?>
        <input type="email" name="email" placeholder="Email Address">
        <input type="password" name="password1" placeholder="New Password">
        <input type="password" name="password2" placeholder="Confirm Password">
        <button type="submit" name="forgotPassword">Confirm</button>
      </form>
    </div>
  </div>

</body>

</html>