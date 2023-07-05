<?php include('./src/components/server.php');
include('./src/components/pagevalidation.php') ?>
<!DOCTYPE html>
<html>

<head>
  <title>My Orders</title>
  <link rel="stylesheet" type="text/css" href="./src/css/style.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>

<body class="myorders">
  <?php include('./header.php') ?>
  <div class="container">
    <div class="left">
      <p class="title">My Orders</p>
      <form method="post" action="myorders.php">
        <?php
        $subTotal = 0;
        $deliveryCharges = 0;
        $totalPayment = 0;

        if (isset($_SESSION['ordersDetails'])) {
          $ordersDetails = $_SESSION['ordersDetails'];
          foreach ($ordersDetails as $key => $orders) {
            if ($_SESSION['delivery']) {
              $deliveryCharges = 5;
            }
            $subTotal += $orders->{'total_price'};
            $totalPayment = $subTotal + $deliveryCharges;
            ?>
            <div type="submit" name="expandOrder" class="selected-menu">
              <?php
              if ($menuRecord) {
                foreach ($menuRecord as $menu)
                  if ($menu['menu_id'] === $orders->{'menu_id'}) {
                    include('./src/components/menuvalidation.php') ?>
                    <div class="img <?php echo $classMenuImg ?>">
                      <div class="overlay"></div>
                    </div>
                    <div class="order-info">
                      <p>
                        <?php echo $menu['name'] ?>
                      </p>
                      <div class="update-menu">
                        <button type="submit" name="editOrder" value="<?php echo $key ?>">Edit</button>
                        <button type="submit" name="removeOrder" value="<?php echo $key ?>">Remove</button>
                      </div>
                    </div>
                    <div class="total-box">
                      <p class="total-txt">Total</p>
                      <p class="rm-txt">RM
                        <?php echo number_format($orders->{'total_price'}, 2) ?>
                      </p>
                    </div>
                    <?php
                  }
              } ?>
            </div>
            <?php
          }
        } ?>
      </form>
    </div>
    <div class="right">
      <p class="title">Summary</p>
      <?php if ($_SESSION['delivery']) { ?>
        <div class="priceBox" style="margin-bottom: 30px;">
          <p>
            <?php echo $selectedAddress ?>
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
              <?php echo number_format($totalPayment, 2); ?>
            </span>
          </p>
        </div>
      </div>
      <form method="post" action="myorders.php">
        <button type="submit" name="addAnotherOrder">Add Another Order</button>
        <button type="submit" name="confirmOrder" class="confirm-order" value="<?php echo $totalPayment ?>">
          Confirm Order
        </button>
      </form>
    </div>
  </div>
</body>

</html>