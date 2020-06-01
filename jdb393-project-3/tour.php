<?php
include("includes/init.php");
include("includes/login-logout.php");
$title = "Tour";
$navTour = "currentPage";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title>Dean - <?php echo $title; ?></title>

  <link rel="stylesheet" type="text/css" href="styles/all.css" media="all" />
</head>

<body>

  <?php include("includes/header.php"); ?>

  <h1 class="titleheader">Tour 2019</h1>

  <div id="tour">
    <ul id="tours">
      <li>check back for </li>
      <li> </li>
      <li>frequent updates</li>
    </ul>
    <ul>
      <li class="col1">Sunday 17 February, 2019</li>
      <li class="col2">1720</li>
      <li class="col3">Los Angeles, CA, US </li>
    </ul>
    <ul>
      <li class="col1">Sunday April 14, 2019</li>
      <li class="col2">Suncoast Showroom</li>
      <li class="col3">Las Vegas, NV, US</li>
    </ul>
    <ul>
      <li class="col1">Saturday May 11, 2019</li>
      <li class="col2">Tangier</li>
      <li class="col3">Akron, OH, US</li>
    </ul>
    <ul>
      <li class="col1">TBA</li>
      <li class="col2">TBA</li>
      <li class="col3">TBA</li>
    </ul>
  </div>

  <?php include("includes/login.php"); ?>
  <?php include("includes/footer.php"); ?>
</body>
</html>
