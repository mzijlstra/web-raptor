<?php

/**
 * User Controller Class
 *
 * @author mzijlstra 11/14/2014
 */
class UserCtrl {
    // set by context on creation
    public $userDao;
    public $projectDao;

    public function login() {
        // start session, and clean any login errors 
        unset($_SESSION['error']);

        $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
        $pass = filter_input(INPUT_POST, "pass");

        // check if this is a valid login
        $row = $this->userDao->checkLogin($email);

        if ($row && password_verify($pass, $row['password'])) {
            // prevent session fixation
            session_regenerate_id();

            // set current user details
            $_SESSION['user'] = array(
                "id" => $row['id'],
                "first" => $row['firstname'],
                "last" => $row['lastname'],
                "type" => $row['type']
            );

            // update the last accessed time
            $this->userDao->updateAccessed($row['id']);

            // redirect to the Web Raptor SPA
            return "Location: wr";
        } else {
            $_SESSION['error'] = "Invalid email / pass combo";
            return "Location: login";
        }
    }

    public function logout() {
        session_destroy();
        $_SESSION['error'] = "Logged Out";
        return "Location: login";
    }
    
    public function all() {
        global $VIEW_DATA;
        $VIEW_DATA['users'] = $this->userDao->all();
        return "users.php";
    }

    public function details() {
        global $VIEW_DATA;
        global $URI_PARAMS;
        $uid = $URI_PARAMS[1];

        $user = $this->userDao->retrieve($uid);
        $VIEW_DATA['user'] = $user;
        $VIEW_DATA['uid'] = $uid;
        return "userDetails.php";
    }
    
    public function create() {
        $first = filter_input(INPUT_POST, "first", FILTER_SANITIZE_STRING);
        $last = filter_input(INPUT_POST, "last", FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
        $pass = filter_input(INPUT_POST, "pass");
        $type = filter_input(INPUT_POST, "type");
        $active = filter_input(INPUT_POST, "active");

        if (!$first || !$last || !$email || !$pass) {
            return "userDetails.php";
        }
        $hash = password_hash($pass, PASSWORD_DEFAULT);

        $actv = 1;
        if (!$active) {
            $actv = 0;
        }
        $uid = $this->userDao->insert($first, $last, $email, $hash, $type, 
                $actv);
        return "Location: user/$uid";
    }

    public function update() {
        global $URI_PARAMS;
        $uid = $URI_PARAMS[1];
        $first = filter_input(INPUT_POST, "first", FILTER_SANITIZE_STRING);
        $last = filter_input(INPUT_POST, "last", FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
        $type = filter_input(INPUT_POST, "type");
        $active = filter_input(INPUT_POST, "active");
        $pass = filter_input(INPUT_POST, "pass");

        if (!$first || !$last || !$email) {
            // FIXME set $VIEW_DATA and redirect back
        }

        $actv = 1;
        if (!$active) {
            $actv = 0;
        }
        $this->userDao->update($first, $last, $email, $type, $actv, 
                $uid, $pass);

        return "Location: user/$uid";
    }

}
