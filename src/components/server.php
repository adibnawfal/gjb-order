<?php
session_start();

/**
 * Initializing variables.
 */
$userId = "";
$sessionId = "";
$addressId = "";
$username = "";
$email = "";
$name = "";
$address = "";
$phoneNo = "";
$date = "";
$deliveryProgress = null;
$selectedAddress = "";
$ordersDetails = "";
$ordersType = "";
$stall = null;
$stallName = "";
$stallLocation = "";
$stallPhoneNo = "";
$user = null;
$userUsername = "";
$userFullName = "";
$userEmail = "";
$userPhoneNo = "";
$updateAccount = false;
$errors = array();
$addressRecord = array();
$menuRecord = array();
$ordersRecord = array();

/**
 * Connect to the database.
 */
$conn = new mysqli('localhost', 'root', '', 'greatjohoreburger');

// check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

/**
 * Register user.
 */
if (isset($_POST['register'])) {
  // receive all input values from the form
  $username = $conn->real_escape_string($_POST['username']);
  $email = $conn->real_escape_string($_POST['email']);
  $password1 = $conn->real_escape_string($_POST['password1']);
  $password2 = $conn->real_escape_string($_POST['password2']);
  $sessionType = "Registered";

  // form validation: ensure that the form is correctly filled
  // by adding (array_push()) corresponding error unto $errors array
  if (empty($username)) {
    array_push($errors, "Username is required!");
  }

  if (empty($email)) {
    array_push($errors, "Email is required!");
  }

  if (empty($password1)) {
    array_push($errors, "Password is required!");
  }

  if ($password1 != $password2) {
    array_push($errors, "Passwords do not match!");
  }

  // first check the database to make sure
  // a user does not already exist with the same username and/or email
  $userCheckQuery = "SELECT * FROM user WHERE username='$username' AND email='$email' LIMIT 1";
  $result = $conn->query($userCheckQuery);
  $user = $result->fetch_assoc();

  if ($user) { // if user exists
    if ($user['username'] === $username) {
      array_push($errors, "Username already exists!");
    }

    if ($user['email'] === $email) {
      array_push($errors, "Email already exists!");
    }
  }

  // register user if there are no errors in the form
  if (count($errors) == 0) {
    $password = md5($password1); // encrypt the password before saving in the database
    $query = "INSERT INTO user (username, email, password, type) 
    VALUES ('$username', '$email', '$password', '$sessionType')";

    // get user id of the new user
    if ($conn->query($query) === true) {
      $userId = $conn->insert_id;
    }

    $_SESSION['userId'] = $userId;
    $_SESSION['sessionType'] = $sessionType;
    header('location: index.php');
  }
}

/**
 * Login user.
 */
if (isset($_POST['login'])) {
  // receive all input values from the form
  $username = $conn->real_escape_string($_POST['username']);
  $password = $conn->real_escape_string($_POST['password']);

  // form validation: ensure that the form is correctly filled
  // by adding (array_push()) corresponding error unto $errors array
  if (empty($username)) {
    array_push($errors, "Username is required!");
  }

  if (empty($password)) {
    array_push($errors, "Password is required!");
  }

  // login user if there are no errors in the form
  if (count($errors) == 0) {
    $password = md5($password);
    $query = "SELECT * FROM user 
    WHERE username='$username' 
    AND password='$password'";
    $result = $conn->query($query);
    $user = $result->fetch_assoc();

    if ($user) {
      $_SESSION['userId'] = $user['user_id'];
      $_SESSION['sessionType'] = $user['type'];

      if ($_SESSION['sessionType'] == "Admin") {
        header('location: account.php');
      } else {
        header('location: index.php');
      }
    } else {
      array_push($errors, "Wrong username or password combination!");
    }
  }
}

/**
 * Forgot password.
 */
if (isset($_POST['forgotPassword'])) {
  // receive all input values from the form
  $username = $conn->real_escape_string($_POST['username']);
  $email = $conn->real_escape_string($_POST['email']);
  $password1 = $conn->real_escape_string($_POST['password1']);
  $password2 = $conn->real_escape_string($_POST['password2']);

  // form validation: ensure that the form is correctly filled
  // by adding (array_push()) corresponding error unto $errors array
  if (empty($username)) {
    array_push($errors, "Username is required!");
  }

  if (empty($email)) {
    array_push($errors, "Email address is required!");
  }

  if (empty($password1)) {
    array_push($errors, "New Password is required!");
  }

  if ($password1 != $password2) {
    array_push($errors, "Confirm Password do not match!");
  }

  // first check the database to make sure
  // the username and email exist in the database
  $userCheckQuery = "SELECT * FROM user 
  WHERE username='$username' 
  AND email='$email' LIMIT 1";
  $result = $conn->query($userCheckQuery);
  $user = $result->fetch_assoc();

  if (!$user) { // if user do not exists
    array_push($errors, "User do not exists!");
  }

  // change user password if there are no errors in the form
  if (count($errors) == 0) {
    $password = md5($password1); // encrypt the password before saving in the database
    $query = "UPDATE user 
    SET password='$password'  
    WHERE username='$username' 
    AND email='$email'";

    if ($conn->query($query) === true) {
      $message = 'Password has been successfully changed.';
      echo "<script>
      alert('$message');
      location.replace('http://localhost/gjb-order/login.php');
      </script>";
    }
  }
}

/**
 * Validate session type and dining option. Logout user
 */
if (!isset($_SESSION['sessionType']) || isset($_POST['logout'])) {
  // get session id and set session type
  $sessionId = $conn->real_escape_string(session_id());
  $sessionType = "Guest";

  // first check the database to make sure
  // a user does not already exist with the same session id
  $userCheckQuery = "SELECT * FROM user WHERE username='$sessionId' LIMIT 1";
  $result = $conn->query($userCheckQuery);
  $user = $result->fetch_assoc();

  if ($user) { // if user exists
    // get user id of the existing user
    $userId = $user['user_id'];
  } else {
    // create new user
    $query = "INSERT INTO user (username, type) 
    VALUES ('$sessionId', '$sessionType')";

    // get user id of the new user
    if ($conn->query($query) === true) {
      $userId = $conn->insert_id;
    }
  }

  $_SESSION['userId'] = $userId;
  $_SESSION['sessionType'] = $sessionType;
  header('location: index.php');
}

if (!isset($_SESSION['delivery'])) {
  $_SESSION['delivery'] = true;
}

/**
 * Toggle dining option.
 */
if (isset($_POST['btnDelivery'])) {
  $_SESSION['delivery'] = true;
} else if (isset($_POST['btnPickup'])) {
  $_SESSION['delivery'] = false;
}

/**
 * Add new address.
 */
if (isset($_POST['addAddress'])) {
  // receive all input values from the form
  $sessionId = $conn->real_escape_string(session_id());
  $name = $conn->real_escape_string($_POST['name']);
  $address = $conn->real_escape_string($_POST['address']);
  $phoneNo = $conn->real_escape_string($_POST['phoneNo']);

  // form validation: ensure that the form is correctly filled ...
  // by adding (array_push()) corresponding error unto $errors array
  if (empty($name)) {
    array_push($errors, "Full Name is required!");
  } else if (empty($address)) {
    array_push($errors, "Address is required!");
  } else if (empty($phoneNo)) {
    array_push($errors, "Phone number is required!");
  }

  // check if the user have any address
  $query = "SELECT * FROM address 
  WHERE user_id='{$_SESSION['userId']}'";
  $result = $conn->query($query);
  $result->fetch_assoc();

  // update other address value to false
  if ($result->num_rows > 0) {
    $query = "UPDATE address 
    SET selected=false 
    WHERE user_id='{$_SESSION['userId']}'";
    $conn->query($query);
  }

  // add address to user
  $query = "INSERT INTO address (name, details, phone_no, selected, user_id) 
  VALUES ('$name', '$address', '$phoneNo', true, '{$_SESSION['userId']}')";
  $conn->query($query);
}

/**
 * Re-select address.
 */
if (isset($_POST['selectAdd'])) {
  // receive selected address id from the form
  $addressId = $conn->real_escape_string($_POST['selectAdd']);

  // update other address value to false
  $query = "UPDATE address 
  SET selected=false 
  WHERE user_id='{$_SESSION['userId']}'";
  $conn->query($query);

  // update selected address value to true
  $query = "UPDATE address 
  SET selected=true 
  WHERE user_id='{$_SESSION['userId']}' 
  AND address_id='$addressId'";
  $conn->query($query);
}

/**
 * Confirm dining option.
 */
if (isset($_POST['btnDining'])) {
  if ($_SESSION['delivery']) {
    $query = "SELECT * FROM address WHERE user_id='{$_SESSION['userId']}'";
    $result = $conn->query($query);

    // validate if there's data in database
    if ($result->num_rows > 0) {
      header('location: home.php');
    } else {
      array_push($errors, "Please add your address!");
    }
  } else {
    header('location: home.php');
  }
}

/**
 * Re-select dining option.
 */
if (isset($_POST['reSelectDining'])) {
  header('location: index.php');
}

/**
 * Get all address.
 */
if (isset($_SESSION['userId'])) {
  $query = "SELECT * FROM address WHERE user_id='{$_SESSION['userId']}'";
  $result = $conn->query($query);

  // validate if there's data in database
  if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
      $addressRecord[] = $row;
    }

    // get selected address
    foreach ($addressRecord as $address) {
      if ($address['selected']) {
        $selectedAddress = $address['details'];
      }
    }
  }
}

/**
 * Get all menu.
 */
$query = "SELECT * FROM menu";
$result = $conn->query($query);

// validate if there's data in database
if ($result->num_rows > 0) {
  // output data of each row
  while ($row = $result->fetch_assoc()) {
    $menuRecord[] = $row;
  }
}

/**
 * Select menu.
 */
if (isset($_POST['selectMenu'])) {
  // reset menu customization data
  resetMenuValue();

  // set selected menu
  $_SESSION['menuSelected'] = $_POST['selectMenu'];
  $_SESSION['isSelectMenu'] = true;
  unset($_SESSION['isEditOrder']);
  unset($_SESSION['isViewOrders']);
  unset($_SESSION['editOrderKey']);
  unset($_SESSION['isViewDetails']);
  header('location: menu.php');
}

/**
 * Reset customization menu value.
 */
function resetMenuValue()
{
  $_SESSION['sauceChili'] = "check_circle";
  $_SESSION['sauceKetchup'] = "check_circle";
  $_SESSION['sauceMayonaisse'] = "check_circle";
  $_SESSION['sauceBbq'] = "check_circle";
  $_SESSION['removeLettuce'] = "radio_button_unchecked";
  $_SESSION['removeOnion'] = "radio_button_unchecked";
  $_SESSION['removeTomato'] = "radio_button_unchecked";
  $_SESSION['removeCucumber'] = "radio_button_unchecked";
  $_SESSION['extraLettuce'] = "radio_button_unchecked";
  $_SESSION['extraOnion'] = "radio_button_unchecked";
  $_SESSION['extraTomato'] = "radio_button_unchecked";
  $_SESSION['extraCucumber'] = "radio_button_unchecked";
  $_SESSION['addOnCheese'] = 0;
  $_SESSION['addOnEgg'] = 0;
  $_SESSION['addOnPatty'] = 0;
}

/**
 * Validate menu customization selection.
 */
if (
  !isset($_SESSION['sauceChili']) or
  !isset($_SESSION['sauceKetchup']) or
  !isset($_SESSION['sauceMayonaisse']) or
  !isset($_SESSION['sauceBbq']) or
  !isset($_SESSION['removeLettuce']) or
  !isset($_SESSION['removeOnion']) or
  !isset($_SESSION['removeTomato']) or
  !isset($_SESSION['removeCucumber']) or
  !isset($_SESSION['extraLettuce']) or
  !isset($_SESSION['extraOnion']) or
  !isset($_SESSION['extraTomato']) or
  !isset($_SESSION['extraCucumber']) or
  !isset($_SESSION['addOnCheese']) or
  !isset($_SESSION['addOnEgg']) or
  !isset($_SESSION['addOnPatty'])
) {
  resetMenuValue();
}

/**
 * Toggle sauce, remove, extra, and add on.
 */
if (isset($_POST['toggleChili'])) {
  $_SESSION['sauceChili'] == "check_circle" ?
    $_SESSION['sauceChili'] = "radio_button_unchecked" :
    $_SESSION['sauceChili'] = "check_circle";
}

if (isset($_POST['toggleKetchup'])) {
  $_SESSION['sauceKetchup'] == "check_circle" ?
    $_SESSION['sauceKetchup'] = "radio_button_unchecked" :
    $_SESSION['sauceKetchup'] = "check_circle";
}

if (isset($_POST['toggleMayonaisse'])) {
  $_SESSION['sauceMayonaisse'] == "check_circle" ?
    $_SESSION['sauceMayonaisse'] = "radio_button_unchecked" :
    $_SESSION['sauceMayonaisse'] = "check_circle";
}

if (isset($_POST['toggleBbq'])) {
  $_SESSION['sauceBbq'] == "check_circle" ?
    $_SESSION['sauceBbq'] = "radio_button_unchecked" :
    $_SESSION['sauceBbq'] = "check_circle";
}

if (isset($_POST['toggleReLettuce'])) {
  $_SESSION['removeLettuce'] == "do_not_disturb_on" ?
    $_SESSION['removeLettuce'] = "radio_button_unchecked" :
    $_SESSION['removeLettuce'] = "do_not_disturb_on";
}

if (isset($_POST['toggleReOnion'])) {
  $_SESSION['removeOnion'] == "do_not_disturb_on" ?
    $_SESSION['removeOnion'] = "radio_button_unchecked" :
    $_SESSION['removeOnion'] = "do_not_disturb_on";
}

if (isset($_POST['toggleReTomato'])) {
  $_SESSION['removeTomato'] == "do_not_disturb_on" ?
    $_SESSION['removeTomato'] = "radio_button_unchecked" :
    $_SESSION['removeTomato'] = "do_not_disturb_on";
}

if (isset($_POST['toggleReCucumber'])) {
  $_SESSION['removeCucumber'] == "do_not_disturb_on" ?
    $_SESSION['removeCucumber'] = "radio_button_unchecked" :
    $_SESSION['removeCucumber'] = "do_not_disturb_on";
}

if (isset($_POST['toggleExLettuce'])) {
  $_SESSION['extraLettuce'] == "add_circle" ?
    $_SESSION['extraLettuce'] = "radio_button_unchecked" :
    $_SESSION['extraLettuce'] = "add_circle";
}

if (isset($_POST['toggleExOnion'])) {
  $_SESSION['extraOnion'] == "add_circle" ?
    $_SESSION['extraOnion'] = "radio_button_unchecked" :
    $_SESSION['extraOnion'] = "add_circle";
}

if (isset($_POST['toggleExTomato'])) {
  $_SESSION['extraTomato'] == "add_circle" ?
    $_SESSION['extraTomato'] = "radio_button_unchecked" :
    $_SESSION['extraTomato'] = "add_circle";
}

if (isset($_POST['toggleExCucumber'])) {
  $_SESSION['extraCucumber'] == "add_circle" ?
    $_SESSION['extraCucumber'] = "radio_button_unchecked" :
    $_SESSION['extraCucumber'] = "add_circle";
}

if (isset($_POST['plusAddOnCheese'])) {
  $_SESSION['addOnCheese'] += 1;
} elseif (isset($_POST['minusAddOnCheese'])) {
  if ($_SESSION['addOnCheese'] > 0) {
    $_SESSION['addOnCheese'] -= 1;
  }
}

if (isset($_POST['plusAddOnEgg'])) {
  $_SESSION['addOnEgg'] += 1;
} elseif (isset($_POST['minusAddOnEgg'])) {
  if ($_SESSION['addOnEgg'] > 0) {
    $_SESSION['addOnEgg'] -= 1;
  }
}

if (isset($_POST['plusAddOnPatty'])) {
  $_SESSION['addOnPatty'] += 1;
} elseif (isset($_POST['minusAddOnPatty'])) {
  if ($_SESSION['addOnPatty'] > 0) {
    $_SESSION['addOnPatty'] -= 1;
  }
}

/**
 * Create new order.
 */
function createNewOrder()
{
  $_SESSION['ordersDetails'] = array(
    (object) array(
      "menu_id" => $_SESSION['menuSelected'],
      "sauce" => array(
        $_SESSION['sauceChili'],
        $_SESSION['sauceKetchup'],
        $_SESSION['sauceMayonaisse'],
        $_SESSION['sauceBbq'],
      ),
      "remove" => array(
        $_SESSION['removeLettuce'],
        $_SESSION['removeOnion'],
        $_SESSION['removeTomato'],
        $_SESSION['removeCucumber'],
      ),
      "extra" => array(
        $_SESSION['extraLettuce'],
        $_SESSION['extraOnion'],
        $_SESSION['extraTomato'],
        $_SESSION['extraCucumber'],
      ),
      "add_on" => array(
        $_SESSION['addOnCheese'],
        $_SESSION['addOnEgg'],
        $_SESSION['addOnPatty'],
      ),
      "total_price" => $_SESSION["menuTotalPrice"]
    ),
  );
  $_SESSION['addMoreOrder'] = true;
}

/**
 * Add to order.
 */
if (isset($_POST['addToOrder'])) {
  if (isset($_SESSION['addMoreOrder'])) {
    $newOrders = (object) array(
      "menu_id" => $_SESSION['menuSelected'],
      "sauce" => array(
        $_SESSION['sauceChili'],
        $_SESSION['sauceKetchup'],
        $_SESSION['sauceMayonaisse'],
        $_SESSION['sauceBbq'],
      ),
      "remove" => array(
        $_SESSION['removeLettuce'],
        $_SESSION['removeOnion'],
        $_SESSION['removeTomato'],
        $_SESSION['removeCucumber'],
      ),
      "extra" => array(
        $_SESSION['extraLettuce'],
        $_SESSION['extraOnion'],
        $_SESSION['extraTomato'],
        $_SESSION['extraCucumber'],
      ),
      "add_on" => array(
        $_SESSION['addOnCheese'],
        $_SESSION['addOnEgg'],
        $_SESSION['addOnPatty'],
      ),
      "total_price" => $_SESSION["menuTotalPrice"]
    );
    array_push($_SESSION['ordersDetails'], $newOrders);
  } else {
    createNewOrder();
  }
  header('location: myorders.php');
}

/**
 * Edit order.
 */
if (isset($_POST['editOrder'])) {
  // set menu customization data
  $ordersDetails = $_SESSION['ordersDetails'];
  foreach ($ordersDetails as $key => $orders) {
    if ($key == $_POST['editOrder']) {
      $_SESSION['menuSelected'] = $orders->{'menu_id'};
      $_SESSION['sauceChili'] = $orders->{'sauce'}[0];
      $_SESSION['sauceKetchup'] = $orders->{'sauce'}[1];
      $_SESSION['sauceMayonaisse'] = $orders->{'sauce'}[2];
      $_SESSION['sauceBbq'] = $orders->{'sauce'}[3];
      $_SESSION['removeLettuce'] = $orders->{'remove'}[0];
      $_SESSION['removeOnion'] = $orders->{'remove'}[1];
      $_SESSION['removeTomato'] = $orders->{'remove'}[2];
      $_SESSION['removeCucumber'] = $orders->{'remove'}[3];
      $_SESSION['extraLettuce'] = $orders->{'extra'}[0];
      $_SESSION['extraOnion'] = $orders->{'extra'}[1];
      $_SESSION['extraTomato'] = $orders->{'extra'}[2];
      $_SESSION['extraCucumber'] = $orders->{'extra'}[3];
      $_SESSION['addOnCheese'] = $orders->{'add_on'}[0];
      $_SESSION['addOnEgg'] = $orders->{'add_on'}[1];
      $_SESSION['addOnPatty'] = $orders->{'add_on'}[2];
    }
  }

  $_SESSION['editOrderKey'] = $_POST['editOrder'];
  $_SESSION['isEditOrder'] = true;
  unset($_SESSION['isSelectMenu']);
  unset($_SESSION['isViewOrders']);
  unset($_SESSION['isViewDetails']);
  header('location: menu.php');
}

/**
 * Cancel changes.
 */
if (isset($_POST['cancelChanges'])) {
  unset($_SESSION['editOrderKey']);
  header('location: myorders.php');
}

/**
 * Save changes.
 */
if (isset($_POST['saveChanges'])) {
  // set menu customization data
  $ordersDetails = $_SESSION['ordersDetails'];
  foreach ($ordersDetails as $key => $orders) {
    if ($key == $_SESSION['editOrderKey']) {
      $orders->{'sauce'}[0] = $_SESSION['sauceChili'];
      $orders->{'sauce'}[1] = $_SESSION['sauceKetchup'];
      $orders->{'sauce'}[2] = $_SESSION['sauceMayonaisse'];
      $orders->{'sauce'}[3] = $_SESSION['sauceBbq'];
      $orders->{'remove'}[0] = $_SESSION['removeLettuce'];
      $orders->{'remove'}[1] = $_SESSION['removeOnion'];
      $orders->{'remove'}[2] = $_SESSION['removeTomato'];
      $orders->{'remove'}[3] = $_SESSION['removeCucumber'];
      $orders->{'extra'}[0] = $_SESSION['extraLettuce'];
      $orders->{'extra'}[1] = $_SESSION['extraOnion'];
      $orders->{'extra'}[2] = $_SESSION['extraTomato'];
      $orders->{'extra'}[3] = $_SESSION['extraCucumber'];
      $orders->{'add_on'}[0] = $_SESSION['addOnCheese'];
      $orders->{'add_on'}[1] = $_SESSION['addOnEgg'];
      $orders->{'add_on'}[2] = $_SESSION['addOnPatty'];
      $orders->{'total_price'} = $_SESSION["menuTotalPrice"];
    }
  }

  unset($_SESSION['editOrderKey']);
  header('location: myorders.php');
}

/**
 * Remove order.
 */
if (isset($_POST['removeOrder'])) {
  unset($_SESSION['ordersDetails'][$_POST['removeOrder']]);
  $_SESSION['ordersDetails'] = array_values($_SESSION['ordersDetails']);
  header('location: myorders.php');
}

/**
 * Add another order.
 */
if (isset($_POST['addAnotherOrder'])) {
  header('location: home.php');
}

/**
 * Confirm order.
 */
if (isset($_POST['confirmOrder'])) {
  // set timezone
  date_default_timezone_set('Asia/Kuala_Lumpur');
  $date = date('d/m/Y', time());
  $ordersDetails = serialize($_SESSION['ordersDetails']);

  // initialize order type
  if ($_SESSION['delivery']) {
    $ordersType = "Delivery";
    $deliveryProgress = "Waiting";
  } else {
    $ordersType = "Pickup";
    $deliveryProgress = null;
    $selectedAddress = null;
  }

  $query = "INSERT INTO orders (date, details, progress, payment, delivery, address, total_payment, type, user_id) 
  VALUES ('$date', '$ordersDetails', 'Pending', 'Not Paid', '$deliveryProgress', '$selectedAddress', '{$_POST['confirmOrder']}', '$ordersType', '{$_SESSION['userId']}')";
  $conn->query($query);

  // clear my orders data
  unset($_SESSION['ordersDetails']);
  unset($_SESSION['addMoreOrder']);
  unset($_SESSION['editOrderKey']);
  header('location: summary.php');
}

/**
 * Get all orders.
 */
if (isset($_SESSION['userId'])) {
  if ($_SESSION['sessionType'] != "Admin") {
    $query = "SELECT * FROM orders WHERE user_id='{$_SESSION['userId']}'";
    $result = $conn->query($query);
  } else {
    $query = "SELECT * FROM orders";
    $result = $conn->query($query);
  }

  // validate if there's data in database
  if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
      $ordersRecord[] = $row;
    }
  }
}

/**
 * View selected orders.
 */
if (isset($_POST['viewOrders'])) {
  // receive all input values from the button
  $_SESSION['ordersSelected'] = $_POST['viewOrders'];
  $_SESSION['isViewOrders'] = true;
  unset($_SESSION['isSelectMenu']);
  unset($_SESSION['isEditOrder']);
  unset($_SESSION['isViewDetails']);
  header('location: vieworders.php');
}

/**
 * View details.
 */
if (isset($_POST['viewDetails'])) {
  // set menu customization data
  foreach ($ordersRecord as $orders) {
    if ($orders['orders_id'] == $_SESSION['ordersSelected']) {
      $ordersDetails = unserialize($orders['details']);
      foreach ($ordersDetails as $key => $oD) {
        if ($key == $_POST['viewDetails']) {
          $_SESSION['menuSelected'] = $oD->{'menu_id'};
          $_SESSION['sauceChili'] = $oD->{'sauce'}[0];
          $_SESSION['sauceKetchup'] = $oD->{'sauce'}[1];
          $_SESSION['sauceMayonaisse'] = $oD->{'sauce'}[2];
          $_SESSION['sauceBbq'] = $oD->{'sauce'}[3];
          $_SESSION['removeLettuce'] = $oD->{'remove'}[0];
          $_SESSION['removeOnion'] = $oD->{'remove'}[1];
          $_SESSION['removeTomato'] = $oD->{'remove'}[2];
          $_SESSION['removeCucumber'] = $oD->{'remove'}[3];
          $_SESSION['extraLettuce'] = $oD->{'extra'}[0];
          $_SESSION['extraOnion'] = $oD->{'extra'}[1];
          $_SESSION['extraTomato'] = $oD->{'extra'}[2];
          $_SESSION['extraCucumber'] = $oD->{'extra'}[3];
          $_SESSION['addOnCheese'] = $oD->{'add_on'}[0];
          $_SESSION['addOnEgg'] = $oD->{'add_on'}[1];
          $_SESSION['addOnPatty'] = $oD->{'add_on'}[2];
        }
      }
    }
  }

  $_SESSION['isViewDetails'] = true;
  unset($_SESSION['isSelectMenu']);
  unset($_SESSION['isEditOrder']);
  unset($_SESSION['isViewOrders']);
  unset($_SESSION['editOrderKey']);
  header('location: menu.php');
}

/**
 * Update progress.
 */
if (isset($_POST['updateProgress'])) {
  if ($ordersRecord) {
    foreach ($ordersRecord as $orders) {
      if ($orders['orders_id'] === $_SESSION['ordersSelected']) {
        $progress = $orders['progress'];
        $deliveryStatus = $orders['delivery'];
        $payment = $orders['payment'];
      }
    }
  }

  switch ($_POST['updateProgress']) {
    case 'progress-up':
      $column = "progress";
      if ($progress == "Pending") {
        $value = "Preparing";
      } else if ($progress == "Preparing") {
        $value = "Complete";
      }
      break;

    case 'progress-down':
      $column = "progress";
      if ($progress == "Complete") {
        $value = "Preparing";
      } else if ($progress == "Preparing") {
        $value = "Pending";
      }
      break;

    case 'delivery-up':
      $column = "delivery";
      if ($deliveryStatus == "Waiting") {
        $value = "On-Delivery";
      } else if ($deliveryStatus == "On-Delivery") {
        $value = "Complete";
      }
      break;

    case 'delivery-down':
      $column = "delivery";
      if ($deliveryStatus == "Complete") {
        $value = "On-Delivery";
      } else if ($deliveryStatus == "On-Delivery") {
        $value = "Waiting";
      }
      break;

    case 'payment-up':
      $column = "payment";
      if ($payment == "Not Paid") {
        $value = "Paid";
      }
      break;

    case 'payment-down':
      $column = "payment";
      if ($payment == "Paid") {
        $value = "Not Paid";
      }
      break;

    default:
      # code...
      break;
  }

  $query = "UPDATE orders SET {$column}='{$value}' WHERE orders_id='{$_SESSION['ordersSelected']}'";
  $conn->query($query);
  header("Refresh: 0");
}

/**
 * Get user details.
 */
if ($_SESSION['sessionType'] == "Registered") {
  $query = "SELECT * FROM user WHERE user_id='{$_SESSION['userId']}'";
  $result = $conn->query($query);
  $user = $result->fetch_assoc();

  if ($user) {
    $userUsername = $user['username'];
    $userFullName = $user['name'];
    $userEmail = $user['email'];
    $userPhoneNo = $user['phone_no'];
  }
}

/**
 * Get stall details.
 */
$query = "SELECT * FROM stall";
$result = $conn->query($query);
$stall = $result->fetch_assoc();

if ($stall) { // if stall exists
  $stallName = $stall['name'];
  $stallLocation = $stall['location'];
  $stallPhoneNo = $stall['phone_no'];
}

/**
 * Update account.
 */
if (isset($_POST['updateAccount'])) {
  $updateAccount = true;
  unset($_SESSION['isSelectMenu']);
  unset($_SESSION['isEditOrder']);
  unset($_SESSION['isViewOrders']);
  unset($_SESSION['isViewDetails']);
  unset($_SESSION['editOrderKey']);
}

/**
 * Confirm update account.
 */
if (isset($_POST['confirmUpdate'])) {
  if ($_SESSION['sessionType'] == "Registered") {
    $userUsername = $conn->real_escape_string($_POST['userUsername']);
    $userFullName = $conn->real_escape_string($_POST['userFullName']);
    $userEmail = $conn->real_escape_string($_POST['userEmail']);
    $userPhoneNo = $conn->real_escape_string($_POST['userPhoneNo']);

    $query = "UPDATE user 
    SET username='{$userUsername}',
    email='{$userEmail}',
    name='{$userFullName}',
    phone_no='{$userPhoneNo}' 
    WHERE user_id='{$_SESSION['userId']}'";
    $conn->query($query);

  } else if ($_SESSION['sessionType'] == "Admin") {
    $stallName = $conn->real_escape_string($_POST['stallName']);
    $stallLocation = $conn->real_escape_string($_POST['stallLocation']);
    $stallPhoneNo = $conn->real_escape_string($_POST['stallPhoneNo']);

    $query = "UPDATE stall 
    SET name='{$stallName}',
    location='{$stallLocation}',
    phone_no='{$stallPhoneNo}' 
    WHERE stall_id=1";
    $conn->query($query);
  }

  $updateAccount = false;
}

/**
 * Prevent to save cache.
 */
header("Cache-Control: no cache");

/**
 * Close database connection.
 */
$conn->close();
?>