<?php $fileName = basename($_SERVER['PHP_SELF'], ".php"); ?>
<div class="header">
  <div class="head-container">
    <?php if ($fileName != "menu" && $fileName != "vieworders" && $fileName != "forgotpassword" && !$updateAccount) { ?>
      <a href="<?php if ($fileName != "index" && $_SESSION['sessionType'] != "Admin") {
        echo "./home.php";
      } ?>">
        <span class="material-symbols-outlined">lunch_dining</span>
      </a>
      <?php if ($_SESSION['sessionType'] == "Admin") { ?>
        <p class="stall-title">Admin</p>
      <?php } else if ($fileName == "index" || $fileName == "login" || $fileName == "register" || $fileName == "account") { ?>
          <p class="stall-title">
          <?php echo $stallName ?>
          </p>
        <?php } else { ?>
          <form method="post" action="home.php">
            <?php
            if ($_SESSION['delivery']) {
              if ($addressRecord) {
                foreach ($addressRecord as $address)
                  if ($address['selected']) { ?>
                    <button type="submit" name="reSelectDining" class="stall-title">
                      <div style="display: flex; flex-direction: column; align-items: flex-start;">
                        <p>Delivery</p>
                        <p style="font-weight: 500;">
                        <?php echo $selectedAddress ?>
                        </p>
                      </div>
                    </button>
                    <?php
                  }
              }
            } else { ?>
            <button type="submit" name="reSelectDining" class="stall-title">Pickup</button>
          <?php } ?>
          </form>
        <?php }
    } else { ?>
      <button type="submit" name="back" onclick="<?php if (isset($_SESSION['isSelectMenu'])) {
        echo "location.replace('http://localhost/gjb-order/home.php')";
      } else if (isset($_SESSION['isEditOrder'])) {
        echo "location.replace('http://localhost/gjb-order/myorders.php')";
      } else if (isset($_SESSION['isViewOrders'])) {
        echo "location.replace('http://localhost/gjb-order/summary.php')";
      } else {
        echo "history.back()";
      } ?>">
        <span class="material-symbols-outlined">arrow_back</span>
      </button>

    <?php } ?>
  </div>
  <?php if ($fileName != "index" && $fileName != "menu" && $fileName != "vieworders" && $fileName != "forgotpassword" && !$updateAccount) { ?>
    <div>
      <a href="<?php if ($_SESSION['sessionType'] == "Guest") {
        echo "./register.php";
      } else {
        echo "./account.php";
      } ?>" style="margin-right: 14px;">
        <span class="material-symbols-outlined">person</span>
      </a>
      <?php if ($_SESSION['sessionType'] != "Admin") { ?>
        <a href="./myorders.php" style="margin-right: 14px;"><span class="material-symbols-outlined">receipt_long</span></a>
      <?php } ?>
      <a href="./summary.php"><span class="material-symbols-outlined">book</span></a>
    </div>
  <?php } ?>
</div>