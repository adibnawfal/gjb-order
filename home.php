<?php include('./src/components/server.php');
include('./src/components/pagevalidation.php') ?>
<!DOCTYPE html>
<html>

<head>
  <title>Home</title>
  <link rel="stylesheet" type="text/css" href="./src/css/style.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>

<body class="home">
  <?php include('./header.php') ?>
  <div class="container">
    <div class="left">
      <div class="category">
        <p class="title">Today's Menu</p>
        <form method="post" action="home.php" class="details">
          <input type="submit" name="category" value="News" />
          <input type="submit" name="category" value="Popular Menu" />
          <input type="submit" name="category" value="Burger Menu" />
          <input type="submit" name="category" value="Roti John Menu" />
        </form>
      </div>
    </div>
    <div class="right">
      <?php
      if ($menuRecord) {
        foreach ($menuRecord as $menu) {
          include('./src/components/menuvalidation.php') ?>
          <form method="post" action="home.php" class="menu">
            <p class="title" style="display: <?php echo $menuHidden ?>;"><?php echo $menuTitle ?> Menu</p>
            <button type="submit" name="selectMenu" class="btn-menu" value="<?php echo $menu['menu_id']; ?>">
              <div class="img <?php echo $classMenuImg ?>">
                <div class="overlay"></div>
              </div>
              <div class="txt">
                <p style="margin-bottom: 15px;">
                  <?php echo $menu['name']; ?>
                </p>
                <p class="price">Price</p>
                <p style="color: var(--gold);">RM
                  <?php echo number_format($menu['price'], 2) ?>
                </p>
              </div>
            </button>
          </form>
          <?php
        }
      } ?>
    </div>
  </div>
</body>

</html>