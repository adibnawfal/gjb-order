<?php include('./src/components/server.php');
include('./src/components/pagevalidation.php') ?>
<!DOCTYPE html>
<html>

<head>
  <title>Account</title>
  <link rel="stylesheet" type="text/css" href="./src/css/style.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>

<body class="account">
  <?php include('./header.php') ?>
  <div class="container">
    <div class="left"></div>
    <div class="right">
      <p class="title">
        <?php if ($_SESSION['sessionType'] == "Registered") {
          echo "User Details";
        } else {
          echo "Stall Details";
        } ?>
      </p>
      <form method="post" action="account.php" class="account-form">
        <?php include('./src/components/errors.php');
        if ($_SESSION['sessionType'] == "Registered") { ?>
          <input type="text" name="userUsername" placeholder="Username" value="<?php echo $userUsername ?>" <?php if (!$updateAccount) { ?> disabled <?php } ?>>
          <input type="text" name="userFullName" placeholder="Full Name" value="<?php echo $userFullName ?>" <?php if (!$updateAccount) { ?> disabled <?php } ?>>
          <input type="text" name="userEmail" placeholder="Email" value="<?php echo $userEmail ?>" <?php if (!$updateAccount) { ?> disabled <?php } ?>>
          <input type="text" name="userPhoneNo" placeholder="Phone No" value="<?php echo $userPhoneNo ?>" <?php if (!$updateAccount) { ?> disabled <?php } ?>>
          <?php
        } else { ?>
          <input type="text" name="stallName" placeholder="Stall Name" value="<?php echo $stallName ?>" <?php if (!$updateAccount) { ?> disabled <?php } ?>>
          <input type="text" name="stallLocation" placeholder="Location" value="<?php echo $stallLocation ?>" <?php if (!$updateAccount) { ?> disabled <?php } ?>>
          <input type="text" name="stallPhoneNo" placeholder="Phone No" value="<?php echo $stallPhoneNo ?>" <?php if (!$updateAccount) { ?> disabled <?php } ?>>
        <?php }
        if ($updateAccount) { ?>
          <button type="submit" name="confirmUpdate">Confirm Update</button>
        <?php } else { ?>
          <button type="submit" name="updateAccount">
            <?php if ($_SESSION['sessionType'] == "Registered") {
              echo "Update User Details";
            } else {
              echo "Update Stall Details";
            } ?>
          </button>
          <button type="submit" name="logout" style="background-color: var(--red); color: var(--white)">Log Out</button>
        <?php } ?>
      </form>
    </div>
  </div>
</body>

</html>