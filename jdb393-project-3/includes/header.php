<?php error_reporting(0); ?>   <!-- Turn off undeclared variable notices on webpages -->
<header>
    <h1 id="title"><a id="titleText" href="index.php">DEAN</a></h1>
    <nav id="menu">
        <ul>
            <li id=<?php echo $navHome?>><a class="navLink" href="index.php">Home</a></li>
            <li id=<?php echo $navAbout?>><a class="navLink" href="about.php">About</a></li>
            <li id=<?php echo $navNews?>><a class="navLink" href="news.php">News</a></li>
            <li id=<?php echo $navTour?>><a class="navLink" href="tour.php">Tour</a></li>
            <li id=<?php echo $navGallery?>><a class="navLink" href="gallery.php">Gallery</a></li>

            <!-- Source: (original work) Kyle Harms -->
            <?php
                if (is_user_logged_in()) {
                    $logout_url = htmlspecialchars($_SERVER['PHP_SELF']) . '?' . http_build_query(array('logout' => ''));
                    echo '<li id="nav-last"><a href="' . $logout_url . '">Sign Out ' . htmlspecialchars($current_user['username']) . '</a></li>';
                } else {
                    echo '<li id="nav-last"><a>Logged Out</a></li>';
                }
            ?>
            <!-- end of original work by Kyle Harms -->
        </ul>
    </nav>
</header>
