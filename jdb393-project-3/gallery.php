<?php
include("includes/init.php");
include("includes/login-logout.php");
$title = "Gallery";
$navGallery = "currentPage";
?>

<!-- Source: (original work) Kyle Harms -->
<?php
$messages = array();
const MAX_FILE_SIZE = 1000000;    // 10 MB
if (isset($_POST["submit_upload"]) && is_user_logged_in()) {
  $upload_info = $_FILES["box_file"];
  $upload_desc = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
  $upload_cite = filter_input(INPUT_POST, 'citation', FILTER_SANITIZE_STRING);
  if ($upload_info['error'] == UPLOAD_ERR_OK) {    // no err
    $upload_name = basename($upload_info["name"]);
    $upload_ext = strtolower(pathinfo($upload_name, PATHINFO_EXTENSION));    // extension of upload
    $sql = "INSERT INTO images (file_name, citation, file_ext, description, user_id) VALUES (:filename, :citation, :extension, :description, :user_id)";
    $params = array(
      ':filename' => $upload_name,
      ':citation' => $upload_cite,
      ':extension' => $upload_ext,
      ':description' => $upload_desc,
      ':user_id' => $current_user['username']
    );
    $result = exec_sql_query($db, $sql, $params);
    if ($result) {    // if inserted into db
      $file_id = $db->lastInsertId("id");
      $id_filename = 'uploads/' . $upload_name;
      if (move_uploaded_file($upload_info["tmp_name"], $id_filename)) {    // moves to uploads dir
      } else {
        array_push($messages, "Failed to upload file");
      }
    } else {
      array_push($messages, "Failed to upload file");
    }
  } else {
    array_push($messages, "Failed to upload file");
  }
}
?>
<!-- end of original work by Kyle Harms -->

<!-- add a tag: -->
<?php
if (isset($_POST["add_tag"]) && is_user_logged_in()) {
  $tag_to_add = filter_input(INPUT_POST, 'tag_to_add', FILTER_SANITIZE_STRING);
  $current_image_id = filter_input(INPUT_POST, 'current_image', FILTER_SANITIZE_STRING);
  $sql = "SELECT * FROM tags WHERE " . "tags.tag" . " = '%' || :tag_to_add || '%'";
  $params = array(
    ':tag_to_add' => $tag_to_add
  );
  $results = exec_sql_query($db, $sql, $params);
  if($results->rowCount() != 1) {
    try {
      // create a new tag
      $sql = "INSERT INTO tags (tag) VALUES (:tag_to_add)";   // insert the new tag into tags (tags.tag)
      $params = array(
        ':tag_to_add' => $tag_to_add
      );
      $result = exec_sql_query($db, $sql, $params);
      $sql = "SELECT tags.id FROM tags WHERE " . "tags.tag" . " LIKE '%' || :tag_to_add || '%'";    // get the id of the new tag (tags.id)
      $params = array(
        ':tag_to_add' => $tag_to_add
      );
      $new_tag_id = exec_sql_query($db, $sql, $params);
      foreach($new_tag_id as $id) {
        $sql = "INSERT INTO image_tags (image_id, tag_id) VALUES (:current_image_id, :id)";   // insert the tag and image ids into image_tags
        $params = array(
          ':current_image_id' => $current_image_id,
          ':id' => $id['id']
        );
        $result = exec_sql_query($db, $sql, $params);
      }
    } catch (Exception $e) {    // b/c i give up trying
      $exception = TRUE;
    }
  } else {
      // tag already exists
      $sql = "SELECT tags.id FROM tags WHERE " . "tags.tag" . " LIKE '%' || :tag_to_add || '%'";
      $params = array(
        ':tag_to_add' => $tag_to_add
      );
      $sql = exec_sql_query($db, $sql, $params);
      $sql = "INSERT INTO image_tags (image_id, tag_id) VALUES (:current_image_id, :old_tag_id)";
      $params = array(
        ':current_image_id' => $current_image_id,
        ':old_tag_id' => $old_tag_id
      );
      $result = exec_sql_query($db, $sql, $params);
  }
  if($exception) {    // executed if-statement when the tag already existed, exception caught
      // tag already exists
      $sql = "SELECT tags.id FROM tags WHERE " . "tags.tag" . " LIKE '%' || :tag_to_add || '%'";
      $params = array(
        ':tag_to_add' => $tag_to_add
      );
      $sql = exec_sql_query($db, $sql, $params);
      foreach($sql as $s) {
        $old_tag_id = $s['id'];
      }

      $addTag = TRUE;
      // check if the image already has the tag applied to it:
      $test_sql = exec_sql_query($db, "SELECT * FROM image_tags WHERE image_tags.image_id = $current_image_id AND image_tags.tag_id = $old_tag_id")->fetchAll(PDO::FETCH_ASSOC);
      foreach($test_sql as $t) {
        if($t['image_id'] == $current_image_id && $t['tag_id'] == $old_tag_id) {    // intentionally redundant
          $addTag = FALSE;
        }
      }

      // if the image does not already have the tag on it:
      if($addTag) {
        $sql = "INSERT INTO image_tags (image_id, tag_id) VALUES (:current_image_id, :old_tag_id)";
        $params = array(
          ':current_image_id' => $current_image_id,
          ':old_tag_id' => $old_tag_id
        );
        $result = exec_sql_query($db, $sql, $params);
      }
  }
}
?>

<!-- delete a tag: -->
<?php
if (isset($_POST["remove_tag"]) && is_user_logged_in()) {
  try {
    $tag_to_remove = filter_input(INPUT_POST, 'tag_to_remove', FILTER_SANITIZE_STRING);
    $current_image_id = filter_input(INPUT_POST, 'current_image', FILTER_SANITIZE_STRING);
    $sql = "SELECT * FROM tags WHERE " . "tags.tag" . " LIKE '%' || :tag_to_remove || '%'";
    $params = array(
      ':tag_to_remove' => $tag_to_remove
    );
    $sql = exec_sql_query($db, $sql, $params);
    foreach($sql as $s) {
      $id = $s['id'];
    }
    $sql = exec_sql_query($db, "DELETE FROM image_tags WHERE image_tags.image_id = $current_image_id AND image_tags.tag_id = $id")->fetchAll(PDO::FETCH_ASSOC);
  } catch (Exception $e) {    // photo does not have the tag the user is trying to delete
    $break;
  }
}
?>

<!-- delete an image: -->
<?php
if (isset($_POST["delete_post"]) && is_user_logged_in()) {
  $image_to_delete = filter_input(INPUT_POST, 'current_image', FILTER_SANITIZE_STRING);
  $sql = exec_sql_query($db, "DELETE FROM images WHERE images.id = $image_to_delete")->fetchAll(PDO::FETCH_ASSOC);    // delete the image
  $sql = exec_sql_query($db, "DELETE FROM image_tags WHERE image_tags.image_id = $image_to_delete")->fetchAll(PDO::FETCH_ASSOC);    // delete its connected tags
}
?>

<?php
const SEARCH_FIELDS = [
    "tags" => "By Tag",
    "images" => "By Photo (description)"
];

if (isset($_GET['search']) && isset($_GET['category'])) {
  $do_search = TRUE;
  $search_type = filter_input(INPUT_GET, 'category', FILTER_SANITIZE_STRING);
  if (in_array($search_type, array_keys(SEARCH_FIELDS))) {
    $search_field = $search_type;
  } else {
    array_push($messages, "Invalid search category");
    $do_search = FALSE;
  }
  if (is_string($search_type)) {
    $search = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_STRING);
  } else {    // already sanitized as string
    $search = filter_input(INPUT_GET, 'search', FILTER_VALIDATE_INT);
  }
  $search = trim($search);
  } else {
    $do_search = FALSE;
    $search_type = NULL;
    $search = NULL;
  }
?>

<!-- HTML -->
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

  <h1 class="titleHeader">Gallery</h1>

  <p>A gallery of photos of Dean. Search photos by image or tag. Log in to upload photos. You must be logged in to upload photos, edit tags, and view individual photos.</p>

  <!-- view all photos: -->
  <div id="displayButtons">
  <form id="view_all_photos" class="viewForm" action="gallery.php" method="get">
    <button class="submit1" type="submit">View all photos</button>
  </form>

  <!-- view all tags: -->
  <form class="viewForm" action="gallery.php" method="get">
    <button class="submit2" name="show_tags" type="submit">View all tags</button>
  </form>
  </div>

  <ul id="deanGallery">
  <?php
  // view a single post at once:
  if (isset($_POST['view_post'])) {?>
    <?php
    $image_id_to_view = filter_input(INPUT_POST, 'current_image', FILTER_SANITIZE_STRING);
    $records = exec_sql_query($db, "SELECT * FROM images WHERE images.id = $image_id_to_view")->fetchAll(PDO::FETCH_ASSOC);
      foreach($records as $record){?>
        <img id="galleryImage" src="uploads/<?php echo $record['file_name'];?>">
        <h2><?php echo $record['description'];?></h2>
        <p><?php echo "Posted by " . $record['user_id'];?></p>
        <?php   // show tags
        $current_image_id = $record['id'];
        $tag_string = '';
        $current_image_tags = exec_sql_query($db, "SELECT * FROM tags LEFT OUTER JOIN images, image_tags ON image_tags.image_id = images.id AND image_tags.tag_id = tags.id AND images.id = $current_image_id")->fetchAll(PDO::FETCH_ASSOC);
        foreach($current_image_tags as $tag){
          $tag_string = $tag_string . '#' . $tag['tag'] . ' ';
        ?>
        <?php
        }
        ?>
        <p><?php echo "Tags: " . $tag_string;?></p>
        <p><?php echo "Source: " . "<a href= " . $record['citation'] . ">" . $record['citation'] . "</a>";?></p>
        <?php if (is_user_logged_in() && $record['user_id'] == $current_user['username']) {?>
          <form id="deleteForm" action="gallery.php" method="post" enctype="multipart/form-data">
            <textarea readonly class="hidden" id="box_cite" name="current_image" cols="15" rows="1"><?php echo ($current_image_id);?></textarea>  <!-- current image: hidden -->
            <button name="delete_post" type="submit">Delete Post</button>
          </form>
        <?php
        }
        ?>
        <?php if (is_user_logged_in()) {?>
          <?php if (is_user_logged_in() && $record['user_id'] == $current_user['username']) {?>
            <form id="removeTagForm" action="gallery.php" method="post" enctype="multipart/form-data">
              <label for="box_cite">Remove a tag:</label>
              <textarea id="box_cite" name="tag_to_remove" cols="15" rows="1"></textarea>
              <textarea readonly class="hidden" id="box_cite" name="current_image" cols="15" rows="1"><?php echo ($current_image_id);?></textarea>  <!-- current image: hidden -->
              <button name="remove_tag" type="submit">Remove Tag</button>
            </form>
          <?php
          }
          ?>

          <form id="addTagForm" action="gallery.php" method="post" enctype="multipart/form-data">
            <label for="box_cite">Add a tag:</label>
            <textarea id="box_cite" name="tag_to_add" cols="15" rows="1"></textarea>
            <button name="add_tag" type="submit">Add Tag</button>
            <textarea readonly class="hidden" id="box_cite" name="current_image" cols="15" rows="1"><?php echo ($current_image_id);?></textarea>  <!-- current image: hidden -->
          </form>
        <?php
        }
        ?>
    <?php
    }
  // show all tags:
  } else if (isset($_GET["show_tags"])) {
    $all_tags = exec_sql_query($db, "SELECT * FROM tags")->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <h2><?php echo 'All Previously Used Image Tags';?></h2>
    <?php
    foreach($all_tags as $tag) {?>
      <p><?php echo "#" . $tag['tag'];?></p>
    <?php
    }
    ?>
  <?php
  // Search by tag or photo description:
  } else if($do_search) {
    ?>
    <h1 class="listings">Search Results:</h1>
    <?php
    echo '<script>console.log(" doing search ")</script>';
    if($search_type == 'images') {      // search by image description
      ?>
      <h2><?php echo 'For Posts about ' . htmlspecialchars($search);?></h2>
      <?php
      $sql = "SELECT * FROM images WHERE " . $search_field . ".description" . " LIKE '%' || :search || '%'";
      $params = array(
        ':search' => $search
      );
    ?>
    <?php
    // search by tag:
    } else {
      ?>
      <h2><?php echo 'For #' . htmlspecialchars($search);?></h2>
      <?php
      $sql = "SELECT * FROM images LEFT OUTER JOIN tags, image_tags ON image_tags.image_id = images.id AND image_tags.tag_id = tags.id WHERE " . $search_field . ".tag" . " LIKE '%' || :search || '%'";
      $params = array(
        ':search' => $search
      );
    }
    $user_search = exec_sql_query($db, $sql, $params);
    foreach($user_search as $record){?>
      <img id="galleryImage" src="uploads/<?php echo $record['file_name'];?>">
      <h2><?php echo $record['description'];?></h2>
      <p><?php echo "Posted by " . htmlspecialchars($record['user_id']);?></p>
      <?php   // show tags
      if($search_type == 'images') {        // tags get messed by when searching by tag -- take care of this with the "for #<tag>" header
        $current_image_id = $record['id'];
        $tag_string = '';
        $current_image_tags = exec_sql_query($db, "SELECT * FROM tags LEFT OUTER JOIN images, image_tags ON image_tags.image_id = images.id AND image_tags.tag_id = tags.id AND images.id = $current_image_id")->fetchAll(PDO::FETCH_ASSOC);
        foreach($current_image_tags as $tag){
          $tag_string = $tag_string . '#' . $tag['tag'] . ' ';
        ?>
        <?php
        }
        ?>
        <p><?php echo "Tags: " . htmlspecialchars($tag_string);?></p>
      <?php
      }
      ?>
      <p><?php echo "Source: " . "<a href= " . htmlspecialchars($record['citation']) . ">" . htmlspecialchars($record['citation']) . "</a>";?></p>

      <!-- do not allow user to add/remove tags, view/delete image when searching by tag -->
      <?php if($search_type == 'images') {?>
        <?php if (is_user_logged_in()) {?>
          <!-- view image button: -->
          <form id="viewForm" action="gallery.php" method="post" enctype="multipart/form-data">
            <textarea readonly class="hidden" id="box_cite" name="current_image" cols="15" rows="1"><?php echo htmlspecialchars($current_image_id);?></textarea>  <!-- current image: hidden -->
            <button name="view_post" type="submit">View Post</button>
          </form>
        <?php
        }
        ?>
        <?php if (is_user_logged_in() && $record['user_id'] == $current_user['username']) {?>
          <form id="deleteForm" action="gallery.php" method="post" enctype="multipart/form-data">
            <textarea readonly class="hidden" id="box_cite" name="current_image" cols="15" rows="1"><?php echo htmlspecialchars($current_image_id);?></textarea>  <!-- current image: hidden -->
            <button name="delete_post" type="submit">Delete Post</button>
          </form>

          <!-- delete tag -->
          <form id="removeTagForm" action="gallery.php" method="post" enctype="multipart/form-data">
              <label for="box_cite">Remove a tag:</label>
              <textarea id="box_cite" name="tag_to_remove" cols="15" rows="1"></textarea>
              <textarea readonly class="hidden" id="box_cite" name="current_image" cols="15" rows="1"><?php echo htmlspecialchars($current_image_id);?></textarea>  <!-- current image: hidden -->
              <button name="remove_tag" type="submit">Remove Tag</button>
            </form>
        <?php
        }
        ?>
        <?php if (is_user_logged_in()) {?>
          <!-- Add tag: -->
          <form id="addTagForm" action="gallery.php" method="post" enctype="multipart/form-data">
            <label for="box_cite">Add a tag:</label>
            <textarea id="box_cite" name="tag_to_add" cols="15" rows="1"></textarea>
            <button name="add_tag" type="submit">Add Tag</button>
            <textarea readonly class="hidden" id="box_cite" name="current_image" cols="15" rows="1"><?php echo htmlspecialchars($current_image_id);?></textarea>  <!-- current image: hidden -->
          </form>
        <?php
        }
        ?>
      <?php
      } else {?>
        <p>Please return to the 'view all photos' page to perform actions on this photo.</p>
        <p>You can also search this image by title (description).</p>
      <?php
      }
      ?>


    <?php
    }
  // show all images:
  } else {?>
    <?php
    $records = exec_sql_query($db, "SELECT * FROM images")->fetchAll(PDO::FETCH_ASSOC);
      foreach($records as $record){?>
        <img id="galleryImage" src="uploads/<?php echo htmlspecialchars($record['file_name']);?>">
        <h2><?php echo htmlspecialchars($record['description']);?></h2>
        <p><?php echo "Posted by " . htmlspecialchars($record['user_id']);?></p>
        <?php   // show tags
        // Get the correct tags for each image:
        $current_image_id = $record['id'];
        $tag_string = '';
        $current_image_tags = exec_sql_query($db, "SELECT * FROM tags LEFT OUTER JOIN images, image_tags ON image_tags.image_id = images.id AND image_tags.tag_id = tags.id AND images.id = $current_image_id")->fetchAll(PDO::FETCH_ASSOC);
        foreach($current_image_tags as $tag){
          // $tag_string.append('#' . $tag['tag']);     // THIS DOES NOT WORK
          $tag_string = $tag_string . '#' . $tag['tag'] . ' ';
        ?>
        <?php
        }
        ?>
        <p><?php echo "Tags: " . htmlspecialchars($tag_string);?></p>
        <p><?php echo "Source: " . "<a href= " . htmlspecialchars($record['citation']) . ">" . htmlspecialchars($record['citation']) . "</a>";?></p>
        <?php if (is_user_logged_in() && $record['user_id'] == $current_user['username']) {?>
          <form id="deleteForm" action="gallery.php" method="post" enctype="multipart/form-data">
            <textarea readonly class="hidden" id="box_cite" name="current_image" cols="15" rows="1"><?php echo ($current_image_id);?></textarea>  <!-- current image: hidden -->
            <button name="delete_post" type="submit">Delete Post</button>
          </form>
        <?php
        ?> <!-- view image button: -->
        <?php
        }
        ?>
        <?php if (is_user_logged_in()) {?>
          <!-- view image button: -->
          <form id="viewForm" action="gallery.php" method="post" enctype="multipart/form-data">
            <textarea readonly class="hidden" id="box_cite" name="current_image" cols="15" rows="1"><?php echo htmlspecialchars($current_image_id);?></textarea>  <!-- current image: hidden -->
            <button name="view_post" type="submit">View Post</button>
          </form>

          <?php if ($record['user_id'] == $current_user['username']) {?>
            <!-- Remove tag button: -->
            <form id="removeTagForm" action="gallery.php" method="post" enctype="multipart/form-data">
              <label for="box_cite">Remove a tag:</label>
              <textarea id="box_cite" name="tag_to_remove" cols="15" rows="1"></textarea>
              <textarea readonly class="hidden" id="box_cite" name="current_image" cols="15" rows="1"><?php echo htmlspecialchars($current_image_id);?></textarea>  <!-- current image: hidden -->
              <button name="remove_tag" type="submit">Remove Tag</button>
            </form>
          <?php
          }
          ?>

          <!-- Add tag button: -->
          <form id="addTagForm" action="gallery.php" method="post" enctype="multipart/form-data">
            <label for="box_cite">Add a tag:</label>
            <textarea id="box_cite" name="tag_to_add" cols="15" rows="1"></textarea>
            <button name="add_tag" type="submit">Add Tag</button>
            <textarea readonly class="hidden" id="box_cite" name="current_image" cols="15" rows="1"><?php echo htmlspecialchars($current_image_id);?></textarea>  <!-- current image: hidden -->
          </form>
        <?php
        }
        ?>
    <?php
    }
    ?>
  </ul>
  <?php
  }
  ?>
  <!-- end of else statement -->

  <!-- search form -->
  <form id="searchForm" action="gallery.php" method="get">
    <h1 class="searchInfo">Search Photos</h1>
    <p class="aboutText">Search by photo or tag.</p>
    <div id=searchSelection>
      <select name="category">
        <option class="searchLabel"  value="" selected disabled>Search By</option>
        <?php
        foreach(SEARCH_FIELDS as $field_name => $label){?>
          <option value="<?php echo htmlspecialchars($field_name);?>"><?php echo htmlspecialchars($label);?></option>
        <?php
        }
        ?>
      </select>
      <input class="searchInput" type="text" name="search"/>
      <button type="submit">Search</button>
    </div>
  </form>

  <!-- UPLOAD ONLY form-->
  <!-- Source: (original work) Kyle Harms -->
  <?php const MAX_FILE_SIZE = 1000000; ?>
  <?php if (is_user_logged_in()) {?>
    <form id="postForm" action="gallery.php" method="post" enctype="multipart/form-data">
    <div id="uploadSubmission">
      <ul id="uploadInputList">
        <li id="uploadArea">
          <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MAX_FILE_SIZE;?>"/>
          <label for="box_file">Upload File:</label>
          <input id="box_file" type="file" name="box_file">
        </li>
        <li>
          <label for="box_cite">Citation:</label>
          <textarea id="box_cite" name="citation" cols="50" rows="2"></textarea>
        </li>
        <li>
          <label for="box_desc">Description:</label>
          <textarea id="box_desc" name="description" cols="50" rows="2"></textarea>
        </li>
        <li>
          <button name="submit_upload" type="submit">Upload File</button>
        </li>
      </ul>
    </div>
    </form>
  <?php
  }
  ?>
  <!-- end of original work by Kyle Harms -->

  <?php include("includes/login.php"); ?>
  <?php include("includes/footer.php"); ?>

</body>
</html>
