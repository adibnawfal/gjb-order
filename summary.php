<?php include('./src/components/server.php');
include('./src/components/pagevalidation.php') ?>
<!DOCTYPE html>
<html>

<head>
  <title>Order Summary</title>
  <link rel="stylesheet" type="text/css" href="./src/css/style.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>

<body class="summary">
  <?php include('./header.php') ?>
  <div class="container">
    <p class="title">Order Summary</p>
    <div style="display: flex; flex-wrap: wrap;">
      <?php
      if ($ordersRecord) {
        foreach ($ordersRecord as $orders) {
          $ordersNumber = str_pad($orders['orders_id'], 4, "0", STR_PAD_LEFT);
          $date = $orders['date'];
          $totalPayment = $orders['total_payment'];
          $progress = $orders['progress'];
          $payment = $orders['payment'];

          if ($payment == "Paid") {
            $color = "var(--green)";
          } else {
            $color = "var(--red)";
          }
          ?>
          <form method="post" action="summary.php" class="order-list">
            <button type="submit" name="viewOrders" class="btn-order" value="<?php echo $orders['orders_id'] ?>">
              <div class="orders-no">No.
                <?php echo $ordersNumber ?>
              </div>
              <div class="txt">
                <p style="margin-bottom: 15px">
                  <?php echo $date ?>
                </p>
                <p class="price">Total Payment</p>
                <p style="color: var(--gold);">RM
                  <?php echo number_format($totalPayment, 2) ?>
                </p>
              </div>
              <div class="box">
                <div class="box-progress">
                  <?php echo $progress ?>
                </div>
                <div class="box-payment" style="background-color: <?php echo $color ?>">
                  <?php echo $payment ?>
                </div>
              </div>
            </button>
          </form>
          <?php
        }
      }
      ?>
    </div>
  </div>
</body>

</html>