<?php
include("includes/init.php");
include("includes/login-logout.php");
$title = "News";
$navNews = "currentPage";

if (isset($_POST['submit'])) {

 $valid_form = TRUE;

  $first = trim($_POST['first']);
  if ($first == '') {
    $valid_form = FALSE;
    $show_first_error = TRUE;
  } else {
    $valid_form = TRUE;
   }

 $last = trim($_POST['last']);
 if ($last == '') {
   $valid_form = FALSE;
   $show_last_error = TRUE;
 } else {
  $valid_form = TRUE;
 }

 $email = trim($_POST['email']);
 if ($email == '') {
   $valid_form = FALSE;
   $show_email_error = TRUE;
 } else {
  $valid_form = TRUE;
 }

 $language = trim($_POST['language']);
 if ($language == '') {
   $valid_form = FALSE;
   $show_language_error = TRUE;
 } else {
  $valid_form = TRUE;
 }
}

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

  <h1 class="titleHeader">News</h1>

  <p></p>

  <p>Keep up to date with the latest Dean news by subscribing to the newsletter.</p>

<?php
  if (isset($valid_form) && $valid_form) { ?>
    <h1>
      Thank you, <?php echo htmlspecialchars($first) ?> for subscribing.
    </h1>
    <div id="confirmed">
      <p>
        <ul>
          <li>First Name: <?php echo htmlspecialchars($first) ?></li>
          <li>Last Name: <?php echo htmlspecialchars($last) ?></li>
          <li>Email: <?php echo htmlspecialchars($email) ?></li>
          <li>Language: <?php echo htmlspecialchars($language) ?></li>
        </ul>
      </p>
  </div>

  <?php } else { ?>
    <h1>Subscribe</h1>
    <!-- Will sometimes submit incompletion information when Chrome autofill is used then text in an input field is deleted -->
    <form id="subscribe_form" method="post" action="news.php">
      <fieldset>
      <?php if ($first == '') { ?>
        <p class="form_error <?php if (!isset($show_first_error)) {echo 'hiddenNews';} ?>">Please enter your first name</p>
      <?php } ?>
        <p class="userInput" >
          <label class="inputLabel" for="first_field">First Name:</label>
          <input class="inputField" id="first_field" type="text" name="first" value="<?php if(isset($first)) {echo htmlspecialchars($first);} ?>"/>
        </p>

        <?php if ($last == '') { ?>
          <p class="form_error <?php if (!isset($show_last_error)) {echo 'hiddenNews';} ?>">Please enter your last name</p>
        <?php } ?>
        <p class="userInput" >
          <label class="inputLabel" for="last_field">Last Name:</label>
          <input class="inputField" id="last_field" type="text" name="last" value="<?php echo htmlspecialchars($last); ?>"/>
        </p>

        <?php if ($email == '') { ?>
          <p class="form_error <?php if (!isset($show_email_error)) {echo 'hiddenNews';} ?>">Please enter your email</p>
        <?php } ?>
        <p class="userInput" >
          <label class="inputLabel" for="email_field">Email:</label>
          <input class="inputField" id="email_field" type="email" name="email" value="<?php echo htmlspecialchars($email); ?>"/>
        </p>

        <?php if ($language == '') { ?>
          <p class="form_error <?php if (!isset($show_language_error)) {echo 'hiddenNews';} ?>">Please enter the language you would like the newsletter in</p>
        <?php } ?>
        <p class="userInput" >
          <label class="inputLabel" for="language_field">Language:</label>
          <input class="inputField" id="language_field" type="text" name="language" value="<?php echo htmlspecialchars($language); ?>"/>
        </p>

        <div id="submit">
          <input name="submit" id="submit" type="submit" value="Subscribe"/>
        </div>

      </fieldset>
    </form>
  <?php } ?>

  <h1>Dayfly Video Out Now</h1>
  <p>Released March 28, 2019.</p>
  <p>Watch on YouTube.</p>

  <h1 id="ppl">Recap: phony ppl live in seoul</h1>
  <p>2019. 01. 20. 7pm</p>
  <p>Dean performed at Yes24 Live Hall. Hosted by Nothin' but chill and Phony Ppl.</p>
  <p class="paragraph2 smallPaddingBottom">The first solo performance of New York soul band Phony ppl armed with beautiful, sophisticated sensibility and unique sound! Phony Ppl, who has a lot of enthusiasts in Korea, will come to Korea to commemorate the release of his album "mō'zā-ik.".</p>
  <p class="smallPaddingTop"> - Nothin' but chill and Phony Ppl (Facebook)</p>

  <?php include("includes/login.php"); ?>
  <?php include("includes/footer.php"); ?>

</body>
</html>
