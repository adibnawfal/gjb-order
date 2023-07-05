<?php
switch ($menu['menu_id']) {
  case 1:
    $classMenuImg = "benjo";
    $menuTitle = "Burger";
    $menuHidden = "block";
    break;

  case 2:
    $classMenuImg = "kahwin";
    $menuHidden = "none";
    break;

  case 3:
    $classMenuImg = "ayam";
    $menuHidden = "none";
    break;

  case 4:
    $classMenuImg = "daging";
    $menuHidden = "none";
    break;

  case 5:
    $classMenuImg = "ayam-double";
    $menuHidden = "none";
    break;

  case 6:
    $classMenuImg = "daging-double";
    $menuHidden = "none";
    break;

  case 7:
    $classMenuImg = "john-ayam";
    $menuTitle = "Roti John";
    $menuHidden = "block";
    break;

  case 8:
    $classMenuImg = "john-daging";
    $menuHidden = "none";
    break;

  default:
    $menuHidden = "none";
    # code...
    break;
}
?>