<?php include('./src/components/server.php');
include('./src/components/pagevalidation.php') ?>
<!DOCTYPE html>
<html>

<head>
  <title>Register</title>
  <link rel="stylesheet" type="text/css" href="./src/css/style.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>

<body class="register">
  <?php include('./header.php') ?>
  <div class="container">
    <div class="left"></div>
    <div class="right">
      <p class="title">Get's Started</p>
      <p class="desc">Already have an account? <a href="login.php" style="font-weight: 600;">Log In</a></p>
      <form method="post" action="register.php" class="register-form">
        <?php include('./src/components/errors.php'); ?>
        <input type="text" name="username" placeholder="Username" value="<?php echo $username; ?>">
        <input type="email" name="email" placeholder="Email Address" value="<?php echo $email; ?>">
        <input type="password" name="password1" placeholder="Password">
        <input type="password" name="password2" placeholder="Confirm Password">
        <a href="#" class="terms-conditions">
          By clicking register you agree to our <span style="font-weight: 600;">Privacy Policy</span> and
          <span style="font-weight: 600;">Terms and Conditions</span>
        </a>
        <button type="submit" name="register">Register</button>
      </form>
    </div>
  </div>
</body>

</html>