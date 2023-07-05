<?php include('./src/components/server.php');
include('./src/components/pagevalidation.php') ?>
<!DOCTYPE html>
<html>

<head>
  <?php if ($menuRecord) {
    foreach ($menuRecord as $menu)
      if ($menu['menu_id'] == $_SESSION['menuSelected']) { ?>
        <title>
          <?php echo $menu['name']; ?>
        </title>
      <?php }
  } ?>
  <link rel="stylesheet" type="text/css" href="./src/css/style.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>

<body class="menu-page">
  <?php include('./header.php') ?>
  <div class="container">
    <?php
    if ($menuRecord) {
      foreach ($menuRecord as $menu)
        if ($menu['menu_id'] == $_SESSION['menuSelected']) {
          include('./src/components/menuvalidation.php') ?>
          <div class="left">
            <p class="food-title">Food Image</p>
            <div class="img <?php echo $classMenuImg ?>">
              <div class="overlay"></div>
            </div>
          </div>
          <?php
        }
    } ?>
    <div class="right">
      <?php if ($menuRecord) {
        foreach ($menuRecord as $menu)
          if ($menu['menu_id'] == $_SESSION['menuSelected']) {
            $_SESSION['menuTotalPrice'] = $menu['price'] + ($_SESSION['addOnCheese'] * 1) + ($_SESSION['addOnEgg'] * 1) + ($_SESSION['addOnPatty'] * 2);
            ?>
            <form method="post" action="menu.php">
              <p class="food-title">Food Details</p>
              <div class="food-details">
                <div style="display: flex; align-items: center;">
                  <span class="material-symbols-outlined">lunch_dining</span>
                  <p class="title">
                    <?php echo $menu['name']; ?>
                  </p>
                </div>
                <div style="display: flex; align-items: center;">
                  <span class="material-symbols-outlined">payments</span>
                  <p class="title price">RM
                    <?php echo number_format($menu['price'], 2) ?>
                  </p>
                </div>
              </div>
              <div class="sauce" style="margin-bottom: 30px;">
                <p class="title">Select Sauce</p>
                <button type="submit" name="toggleChili" <?php if (isset($_SESSION['isViewOrders'])) { ?> disabled <?php } ?>>
                  <p class="custom-txt">Chili</p>
                  <span class="material-symbols-outlined">
                    <?php echo $_SESSION['sauceChili']; ?>
                  </span>
                </button>
                <button type="submit" name="toggleKetchup" <?php if (isset($_SESSION['isViewOrders'])) { ?> disabled <?php } ?>>
                  <p class="custom-txt">Ketchup</p>
                  <span class="material-symbols-outlined">
                    <?php echo $_SESSION['sauceKetchup']; ?>
                  </span>
                </button>
                <button type="submit" name="toggleMayonaisse" <?php if (isset($_SESSION['isViewOrders'])) { ?> disabled <?php } ?>>
                  <p class="custom-txt">Mayonaisse</p>
                  <span class="material-symbols-outlined">
                    <?php echo $_SESSION['sauceMayonaisse']; ?>
                  </span>
                </button>
                <button type="submit" name="toggleBbq" <?php if (isset($_SESSION['isViewOrders'])) { ?> disabled <?php } ?>>
                  <p class="custom-txt">BBQ</p>
                  <span class="material-symbols-outlined">
                    <?php echo $_SESSION['sauceBbq']; ?>
                  </span>
                </button>
              </div>
              <div class="remove" style="margin-bottom: 30px;">
                <div class="remove-box">
                  <p class="title">Remove From Order</p>
                  <p class="title" style="color: var(--lightGray)">-RM0.00</p>
                </div>
                <button type="submit" name="toggleReLettuce" <?php if (isset($_SESSION['isViewOrders'])) { ?> disabled <?php } ?>>
                  <p class="custom-txt">Remove Lettuce</p>
                  <span class="material-symbols-outlined">
                    <?php echo $_SESSION['removeLettuce']; ?>
                  </span>
                </button>
                <button type="submit" name="toggleReOnion" <?php if (isset($_SESSION['isViewOrders'])) { ?> disabled <?php } ?>>
                  <p class="custom-txt">Remove Onion</p>
                  <span class="material-symbols-outlined">
                    <?php echo $_SESSION['removeOnion']; ?>
                  </span>
                </button>
                <button type="submit" name="toggleReTomato" <?php if (isset($_SESSION['isViewOrders'])) { ?> disabled <?php } ?>>
                  <p class="custom-txt">Remove Tomato</p>
                  <span class="material-symbols-outlined">
                    <?php echo $_SESSION['removeTomato']; ?>
                  </span>
                </button>
                <button type="submit" name="toggleReCucumber" <?php if (isset($_SESSION['isViewOrders'])) { ?> disabled <?php } ?>>
                  <p class="custom-txt">Remove Cucumber</p>
                  <span class="material-symbols-outlined">
                    <?php echo $_SESSION['removeCucumber']; ?>
                  </span>
                </button>
              </div>
              <div class="extra" style="margin-bottom: 30px;">
                <div class="extra-box">
                  <p class="title">Extra</p>
                  <p class="title" style="color: var(--lightGray)">+RM0.00</p>
                </div>
                <button type="submit" name="toggleExLettuce" <?php if (isset($_SESSION['isViewOrders'])) { ?> disabled <?php } ?>>
                  <p class="custom-txt">Extra Lettuce</p>
                  <span class="material-symbols-outlined">
                    <?php echo $_SESSION['extraLettuce']; ?>
                  </span>
                </button>
                <button type="submit" name="toggleExOnion" <?php if (isset($_SESSION['isViewOrders'])) { ?> disabled <?php } ?>>
                  <p class="custom-txt">Extra Onion</p>
                  <span class="material-symbols-outlined">
                    <?php echo $_SESSION['extraOnion']; ?>
                  </span>
                </button>
                <button type="submit" name="toggleExTomato" <?php if (isset($_SESSION['isViewOrders'])) { ?> disabled <?php } ?>>
                  <p class="custom-txt">Extra Tomato</p>
                  <span class="material-symbols-outlined">
                    <?php echo $_SESSION['extraTomato']; ?>
                  </span>
                </button>
                <button type="submit" name="toggleExCucumber" <?php if (isset($_SESSION['isViewOrders'])) { ?> disabled <?php } ?>>
                  <p class="custom-txt">Extra Cucumber</p>
                  <span class="material-symbols-outlined">
                    <?php echo $_SESSION['extraCucumber']; ?>
                  </span>
                </button>
              </div>
              <div class="add-on" style="margin-bottom: 30px;">
                <p class="title">Add On</p>
                <div class="button">
                  <p class="custom-txt">Cheese <span class="add-on-price">(+RM1.00)</span></p>
                  <div class="plus-minus">
                    <button type="submit" name="minusAddOnCheese" class="icon-box" <?php if (isset($_SESSION['isViewOrders'])) { ?> disabled <?php } ?>>
                      <span class="material-symbols-outlined">do_not_disturb_on</span>
                    </button>
                    <p class="qty-txt">
                      <?php echo $_SESSION['addOnCheese']; ?>
                    </p>
                    <button type="submit" name="plusAddOnCheese" class="icon-box" <?php if (isset($_SESSION['isViewOrders'])) { ?> disabled <?php } ?>>
                      <span class="material-symbols-outlined">add_circle</span>
                    </button>
                  </div>
                </div>
                <div class="button">
                  <p class="custom-txt">Egg <span class="add-on-price">(+RM1.00)</span></p>
                  <div class="plus-minus">
                    <button type="submit" name="minusAddOnEgg" class="icon-box" <?php if (isset($_SESSION['isViewOrders'])) { ?> disabled <?php } ?>>
                      <span class="material-symbols-outlined">do_not_disturb_on</span>
                    </button>
                    <p class="qty-txt">
                      <?php echo $_SESSION['addOnEgg']; ?>
                    </p>
                    <button type="submit" name="plusAddOnEgg" class="icon-box" <?php if (isset($_SESSION['isViewOrders'])) { ?> disabled <?php } ?>>
                      <span class="material-symbols-outlined">add_circle</span>
                    </button>
                  </div>
                </div>
                <div class="button">
                  <p class="custom-txt">Patty <span class="add-on-price">(+RM2.00)</span></p>
                  <div class="plus-minus">
                    <button type="submit" name="minusAddOnPatty" class="icon-box" <?php if (isset($_SESSION['isViewOrders'])) { ?> disabled <?php } ?>>
                      <span class="material-symbols-outlined">do_not_disturb_on</span>
                    </button>
                    <p class="qty-txt">
                      <?php echo $_SESSION['addOnPatty']; ?>
                    </p>
                    <button type="submit" name="plusAddOnPatty" class="icon-box" <?php if (isset($_SESSION['isViewOrders'])) { ?> disabled <?php } ?>>
                      <span class="material-symbols-outlined">add_circle</span>
                    </button>
                  </div>
                </div>
              </div>
              <div class="line"></div>
              <div class="total-box">
                <p>Total Price</p>
                <p>RM
                  <?php echo number_format($_SESSION['menuTotalPrice'], 2) ?>
                </p>
              </div>
              <?php if (isset($_SESSION['editOrderKey'])) { ?>
                <button type="submit" name="cancelChanges" class="btn-add-to-order" style="background-color: var(--darkGray); color: var(--white); margin-bottom: 15px">
                  Cancel Changes
                </button>
                <button type="submit" name="saveChanges" class="btn-add-to-order">
                  Save Changes
                </button>
              <?php } else if (isset($_SESSION['isSelectMenu'])) { ?>
                  <button type="submit" name="addToOrder" class="btn-add-to-order">Add To Order</button>
                <?php } ?>
            </form>
          <?php }
      } ?>
    </div>
  </div>
</body>

</html>