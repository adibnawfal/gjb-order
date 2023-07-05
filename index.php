<?php include('./src/components/server.php');
include('./src/components/pagevalidation.php') ?>
<!DOCTYPE html>
<html>

<head>
  <title>Dining Option</title>
  <link rel="stylesheet" type="text/css" href="./src/css/style.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>

<body class="dining">
  <?php include('./header.php') ?>
  <div class="container">
    <div class="left"></div>
    <div class="right">
      <p class="title">Order Your Burger Now</p>
      <p class="desc">How Would You Like To Receive?</p>
      <form method="post" action="index.php" class="dining-btn">
        <?php
        if ($_SESSION['delivery']) {
          $classDel = "selected";
          $classPic = "";
        } else {
          $classDel = "";
          $classPic = "selected";
        } ?>
        <input type="submit" name="btnDelivery" value="Delivery" class="delivery <?php echo $classDel ?>" />
        <input type="submit" name="btnPickup" value="Pickup" class="pickup <?php echo $classPic ?>" />
      </form>
      <?php if ($_SESSION['delivery']) { ?>
        <div class="delivery">
          <p class="title">Delivery Address</p>
          <form method="post" action="index.php" class="add-address">
            <?php include('./src/components/errors.php'); ?>
            <?php if ($addressRecord) {
              foreach ($addressRecord as $address) {
                if ($address['selected']) {
                  $icon = "check_circle";
                } else {
                  $icon = "radio_button_unchecked";
                } ?>
                <button type="submit" name="selectAdd" value="<?php echo $address['address_id'] ?>" class="address">
                  <p>
                    <?php echo $address['details']; ?>
                  </p>
                  <span class="material-symbols-outlined" style="color: var(--lightGray);">
                    <?php echo $icon ?>
                  </span>
                </button>
                <?php
              }
            } ?>
            <div class="container">
              <input type="text" name="name" placeholder="Full Name">
              <input type="text" name="address" placeholder="Address">
              <input type="text" name="phoneNo" placeholder="Phone No">
              <button type="submit" name="addAddress" class="btn-add">
                <span class="material-symbols-outlined">add</span>
              </button>
            </div>
            <button type="submit" name="btnDining" class="btn-confirm">Confirm</button>
          </form>
        </div>
      <?php } else { ?>
        <div class="pickup">
          <p class="title">Store Location</p>
          <div class="shop-add">
            <p style="font-weight: 700;">
              <?php echo $stallName ?>
            </p>
            <p>
              <?php echo $stallLocation ?>
            </p>
            <p>
              HP :
              <?php echo $stallPhoneNo ?>
            </p>
          </div>
          <form method="post" action="index.php">
            <button type="submit" name="btnDining" class="btn-confirm">Confirm</button>
          </form>
        </div>
      <?php } ?>
    </div>
  </div>
</body>

</html>