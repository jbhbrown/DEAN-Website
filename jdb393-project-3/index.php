<?php
// DO NOT REMOVE!
include("includes/init.php");
include("includes/login-logout.php");
// DO NOT REMOVE!
$title = "Home";
$navHome = "currentPage";
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

  <!-- TODO: This should be your main page for your site. -->

  <?php include("includes/header.php"); ?>

  <h1 class="titleHeader">Welcome</h1>

  <img src="images/dean_home_img.jpg" alt="Portrait of Dean"/>
  <!-- Source: http://www.kstreetmanila.com/2018/10/dean-is-coming-back-to-manila-in-november/-->
  <p>Source: <cite><a href="http://www.kstreetmanila.com/2018/10/dean-is-coming-back-to-manila-in-november/">KStreet Manila</a></cite></p>
  <p>about</p>

  <h1>Dayfly Video Out Now</h1>
  <img src="images/dayfly.jpg" alt="Dayfly video preview"/>
  <!-- Source: https://www.youtube.com/watch?v=TL99o4b6x4s -->
  <p>Source: <cite><a href="https://www.youtube.com/watch?v=TL99o4b6x4s">YouTue</a></cite></p>
  <p>about</p>

  <h1>Recap: phony ppl live in seoul</h1>
  <p>Dean performed at Yes24 Live Hall. Hosted by Nothin' but chill and Phony Ppl.</p>
  <p><a href="news.php#ppl">Read more</a></p>

  <?php include("includes/login.php"); ?>
  <?php include("includes/footer.php"); ?>

</body>
</html>
