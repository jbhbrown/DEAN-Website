<!-- THIS PAGE WILL NOT BE USED IN THE WEBSITE -->

<?php
include("includes/init.php");
include("includes/login-logout.php");
$title = "Log In";
$navLogin = "currentPage";
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

  <h1 class="titleHeader">Log In</h1>
  <p>Log in to upload photos to the gallery.</p>

  <!-- login form -->
  <div class="login">
    <!-- Source: (original work) Kyle Harms -->
    <ul>
      <?php
      foreach($session_messages as $message) {
        echo "<li><strong>" . htmlspecialchars($message) . "</strong></li>\n";
      }
      ?>
    </ul>
    <!-- End of original work by Kyle Harms -->
    <form id="loginForm" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post">
        <label class="inputLabel" for="username">Username:</label>
        <input class="inputField" id="username" type="text" name="username"/>
        <label class="inputLabel pw" for="password">Password:</label>
        <input class="inputField" id="password" type="password" name="password"/>
        <button id='loginButton' name="login" type="submit">Log In</button>
    </form>
    </div>

  <?php include("includes/footer.php"); ?>

</body>
</html>
