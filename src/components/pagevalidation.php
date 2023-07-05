<?php
$fileName = basename($_SERVER['PHP_SELF'], ".php");

if ($_SESSION['sessionType'] == "Admin") {
  if (
    $fileName == "index" ||
    $fileName == "home" ||
    $fileName == "myorders" ||
    $fileName == "register" ||
    $fileName == "login" ||
    $fileName == "forgotpassword"
  ) {
    header('location: account.php', true) or die("Unable to connect to given site.");
  }
} else if ($_SESSION['sessionType'] == "Registered") {
  if (
    $fileName == "register" ||
    $fileName == "login" ||
    $fileName == "forgotpassword"
  ) {
    header('location: index.php', true) or die("Unable to connect to given site.");
  }
} else if ($_SESSION['sessionType'] == "Guest") {
  if ($fileName == "account") {
    header('location: index.php', true) or die("Unable to connect to given site.");
  }
}
?>