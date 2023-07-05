<?php include('./src/components/server.php');
include('./src/components/pagevalidation.php') ?>
<!DOCTYPE html>
<html>

<head>
  <title>Orders</title>
  <link rel="stylesheet" type="text/css" href="./src/css/style.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>

<body class="vieworders">
  <?php include('./header.php') ?>
  <div class="container">
    <div class="left">
      <?php
      if ($ordersRecord) {
        foreach ($ordersRecord as $orders)
          if ($orders['orders_id'] === $_SESSION['ordersSelected']) {
            $ordersNumber = str_pad($orders['orders_id'], 4, "0", STR_PAD_LEFT); ?>
            <p class="title">Order Number
              <?php echo $ordersNumber ?> -
              <?php echo $orders['type'] ?>
            </p>
            <form method="post" action="vieworders.php">
              <?php
              $subTotal = 0;
              $deliveryCharges = 0;
              $totalPayment = 0;
              $progress = $orders['progress'];
              $payment = $orders['payment'];
              $ordersDetails = unserialize($orders['details']);

              if ($orders['type'] == "Delivery") {
                $deliveryStatus = $orders['delivery'];
                $deliveryCharges = 5;
                $address = $orders['address'];
              } else {
                $deliveryStatus = null;
                $address = null;
              }

              if ($payment === "Paid") {
                $color = "var(--green)";
              } else {
                $color = "var(--red)";
              }

              foreach ($ordersDetails as $key => $oD) {
                $subTotal += $oD->{'total_price'};
                $totalPayment = $subTotal + $deliveryCharges; ?>
                <div type="submit" name="expandOrder" class="selected-menu">
                  <?php
                  if ($menuRecord) {
                    foreach ($menuRecord as $menu)
                      if ($menu['menu_id'] === $oD->{'menu_id'}) {
                        include('./src/components/menuvalidation.php') ?>
                        <div class="img <?php echo $classMenuImg ?>">
                          <div class="overlay"></div>
                        </div>
                        <div class="order-info">
                          <p>
                            <?php echo $menu['name'] ?>
                          </p>
                          <div class="update-menu">
                            <button type="submit" name="viewDetails" value="<?php echo $key ?>">View Details</button>
                          </div>
                        </div>
                        <div class="total-box">
                          <p class="total-txt">Total</p>
                          <p class="rm-txt">RM
                            <?php echo number_format($oD->{'total_price'}, 2) ?>
                          </p>
                        </div>
                        <?php
                      }
                  } ?>
                </div>
                <?php
              } ?>
            </form>
          </div>
          <?php
          }
      } ?>
    <div class="right">
      <p class="title">Summary</p>
      <?php if ($address) { ?>
        <div class="priceBox" style="margin-bottom: 30px;">
          <p>
            <?php echo $address ?>
          </p>
        </div>
      <?php } ?>
      <div>
        <div class="line"></div>
        <div class="priceBox">
          <p>Subtotal</p>
          <p id="sumSubtotal">RM
            <?php echo number_format($subTotal, 2) ?>
          </p>
        </div>
        <div class="priceBox">
          <p>Delivery Charges</p>
          <p id="deliveryCharges">RM
            <?php echo number_format($deliveryCharges, 2) ?>
          </p>
        </div>
        <div class="line" style="margin-top: 30px;"></div>
        <div class="priceBox total">
          <p>Total Payment</p>
          <p>RM
            <span id="totalPayment" style="font-size: 24px">
              <?php echo number_format($totalPayment, 2) ?>
            </span>
          </p>
        </div>
        <form method="post" action="vieworders.php">
          <div class="order-status <?php echo $progress ?>">
            <div>
              <?php if ($_SESSION['sessionType'] == "Admin" && $progress != "Pending") { ?>
                <button type="submit" name="updateProgress" value="progress-down">
                  <span class="material-symbols-outlined" style="margin-right: 20px;">chevron_left</span>
                </button>
              <?php } ?>
              <p>Order Progress</p>
            </div>
            <div>
              <p style="font-weight: 700;">
                <?php echo $progress ?>
              </p>
              <?php if ($_SESSION['sessionType'] == "Admin" && $progress != "Complete") { ?>
                <button type="submit" name="updateProgress" value="progress-up">
                  <span class="material-symbols-outlined" style="margin-left: 20px;">chevron_right</span>
                </button>
              <?php } ?>
            </div>
          </div>
          <?php if ($deliveryStatus) { ?>
            <div class="order-status <?php echo $deliveryStatus ?>">
              <div>
                <?php if ($_SESSION['sessionType'] == "Admin" && $deliveryStatus != "Waiting") { ?>
                  <button type="submit" name="updateProgress" value="delivery-down">
                    <span class="material-symbols-outlined" style="margin-right: 20px;">chevron_left</span>
                  </button>
                <?php } ?>
                <p>Delivery Status</p>
              </div>
              <div>
                <p style="font-weight: 700;">
                  <?php echo $deliveryStatus ?>
                </p>
                <?php if ($_SESSION['sessionType'] == "Admin" && $deliveryStatus != "Complete") { ?>
                  <button type="submit" name="updateProgress" value="delivery-up">
                    <span class="material-symbols-outlined" style="margin-left: 20px;">chevron_right</span>
                  </button>
                <?php } ?>
              </div>
            </div>
          <?php } ?>
          <div class="order-status" style="background-color: <?php echo $color ?>">
            <div>
              <?php if ($_SESSION['sessionType'] == "Admin" && $payment != "Not Paid") { ?>
                <button type="submit" name="updateProgress" value="payment-down">
                  <span class="material-symbols-outlined" style="margin-right: 20px;">chevron_left</span>
                </button>
              <?php } ?>
              <p>Payment Status</p>
            </div>
            <div>
              <p style="font-weight: 700;">
                <?php echo $payment ?>
              </p>
              <?php if ($_SESSION['sessionType'] == "Admin" && $payment != "Paid") { ?>
                <button type="submit" name="updateProgress" value="payment-up">
                  <span class="material-symbols-outlined" style="margin-left: 20px;">chevron_right</span>
                </button>
              <?php } ?>
            </div>
          </div>
        </form>
      </div>
    </div>
</body>

</html>