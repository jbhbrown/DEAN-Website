<!-- Source: (original work) Kyle Harms -->
<!-- Copied by hand/keyboard by Jacob Bee Ho Brown -->
<!-- login and log out functions -->
<?php
    define('SESSION_COOKIE_DURATION', 60*60*1);    // 1 hr
    $session_messages = array();

    function log_in($username, $password) {
        global $db;
        global $current_user;
        global $session_messages;
        if(isset($username) && isset($password)) {    // if they exist in db
            $sql = "SELECT * FROM users WHERE username = :username;";
            $params = array (
                ':username' => $username
            );
            $records = exec_sql_query($db, $sql, $params)->fetchAll();
            if($records) {
                $account = $records[0];    // username is unique -- only 1
                if(password_verify($password, $account['password'])) {         // THIS WILL NOT WORK YET BECAUSE THE PASSWORD IS NOT YET HASHED
                // if($password == $account['password']) {
                    $session = session_create_id();    // generate session
                    $sql = "INSERT INTO sessions (user_id, session) VALUES (:user_id, :session);";    // store session ID in db
                    $params = array(
                        ':user_id' => $account['id'],
                        ':session' => $session
                    );
                    $result = exec_sql_query($db, $sql, $params);
                    if($result) {
                        // session stored in db
                        setcookie("session", $session, time() + SESSION_COOKIE_DURATION);    // send back to user
                        $current_user = $account;
                        return $current_user;
                    } else {
                        array_push($session_messages, "Log in failed");
                    }
                } else {
                    array_push($session_messages, "Invlaid username or password");
                }
            } else {
                array_push($session_messages, "Invlaid username or password");
            }
        } else {
            array_push($session_messages, "Please enter a valid username and password");
        }
        $current_user = NULL;
        return NULL;
    }

    function find_user($user_id) {
        global $db;
        $sql = "SELECT * FROM users WHERE id = :user_id;";
        $params = array(
            ':user_id' => $user_id
        );
        $records = exec_sql_query($db, $sql, $params)->fetchAll();
        if($records) {
            return $records[0];
        }
        return NULL;
    }

    function find_session($session) {
        global $db;
        if(isset($session)) {
            $sql = "SELECT * FROM sessions WHERE session = :session;";
            $params = array(
                ':session' => $session
            );
            $records = exec_sql_query($db, $sql, $params)->fetchAll();
            if($records) {
                return $records[0];
            }
        }
        return NULL;
    }

    function session_login() {
        global $db;
        global $current_user;
        if(isset($_COOKIE["session"])) {
            $session = $_COOKIE["session"];
            $session_record = find_session ($session);
            if(isset($session_record)) {
                $current_user = find_user($session_record['user_id']);
                setcookie("session", $session, time() + SESSION_COOKIE_DURATION);    // renew for another hour
                return $current_user;
            }
        }
        $current_user = NULL;
    }

    function is_user_logged_in() {
        global $current_user;
        return ($current_user != NULL);    // if $current_user is NULL, user is logged out
    }

    function log_out() {
        global $current_user;
        setcookie("session", '', time() - SESSION_COOKIE_DURATION);
        $current_user = NULL;
    }

    // check for login.logout requests; or check to keep user logged in:
    if(isset($_POST['login']) && isset($_POST['username']) && isset($_POST['password'])) {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        log_in($username, $password);
    } else {
        session_login();
    }

    // check if we should log out user
    if(isset($current_user) && isset($_GET['logout']) || isset($_POST['logout'])) {
        log_out();
    }
?>
<!-- End of original work by Kyle Harms -->
