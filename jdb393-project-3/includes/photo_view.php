<?php foreach($records as $record){?>
        <img id="galleryImage" src="uploads/<?php echo $record['file_name'];?>">
        <h2><?php echo $record['description'];?></h2>
        <p><?php echo "Posted by " . $record['user_id'];?></p>
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
        <p><?php echo "Tags: " . $tag_string;?></p>
        <p><?php echo "Source: " . "<a href= " . $record['citation'] . ">" . $record['citation'] . "</a>";?></p>
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
            <textarea readonly class="hidden" id="box_cite" name="current_image" cols="15" rows="1"><?php echo ($current_image_id);?></textarea>  <!-- current image: hidden -->
            <button name="view_post" type="submit">View Post</button>
          </form>

          <!-- Remove tag button: -->
          <form id="removeTagForm" action="gallery.php" method="post" enctype="multipart/form-data">
            <label for="box_cite">Remove a tag:</label>
            <textarea id="box_cite" name="tag_to_remove" cols="15" rows="1"></textarea>
            <textarea readonly class="hidden" id="box_cite" name="current_image" cols="15" rows="1"><?php echo ($current_image_id);?></textarea>  <!-- current image: hidden -->
            <button name="remove_tag" type="submit">Remove Tag</button>
          </form>

          <!-- Add tag button: -->
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
    ?>
