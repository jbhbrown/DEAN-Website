<!-- Source: (original work) Kyle Harms -->
<?php if (!is_user_logged_in()) {?>
    <div class="login">
        <h1>Log In</h1>
        <ul id="loginError">
        <?php
        foreach($session_messages as $message) {
            echo "<li><strong>" . htmlspecialchars($message) . "</strong></li>\n";
        }
        ?>
        </ul>
        <!-- login form must be implemented this way!-->
        <form id="loginForm" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post">
            <ul>
                <li>
                    <label class="inputLabel" for="username">Username:</label>
                    <input class="inputField" for="username" type="text" name="username"/>
                </li>
                <li>
                    <label class="inputLabel" for="password">Password:</label>
                    <input class="inputField" for="password" type="password" name="password"/>
                </li>
                <li>
                    <button id='loginButton' name="login" type="submit">Log In</button>
                </li>
            </ul>
        </form>
    </div>
<?php
}
?>
<!-- end of original work by Kyle Harms -->
