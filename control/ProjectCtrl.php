<?php

/**
 * Description of ProjectController
 *
 * @author mzijlstra 11/15/2014
 */
class ProjectCtrl {

    // set by context on creation
    public $projectDao;
    public $functionDao;

    // GET /project/(\d+)$
    // GET /user/(\d+)/project/(\d+)$
    public function getProject() {
        // TODO FIXME, make sure the user is allowed to do this
        global $URI_PARAMS;
        global $VIEW_DATA;

        $user = $_SESSION['user'];
        $uid = $user['id'];
        $pid = $URI_PARAMS[1];
        $type = "student";

        if (count($URI_PARAMS) === 3) {
            if ($user['type'] === 'admin') {
                $uid = $URI_PARAMS[1];
                $pid = $URI_PARAMS[2];
                $type = "admin";
            } else {
                // Then show login page
                $_SESSION['error'] = "Admin Access Required";
                header("Location: ${MY_BASE}/login");
                exit();
            }
        }

        $proj = $this->projectDao->get($pid, $uid, $type);
        if (!$proj) {
            // clearly uid did not match
            $_SESSION['error'] = "Incorrect Username for Requested Project";
            header("Location: ${MY_BASE}/login");
            exit();
        }
        $VIEW_DATA['funcs'] = $this->functionDao->all($pid);

        $VIEW_DATA['pname'] = $proj['name'];
        $VIEW_DATA['pid'] = $pid;

        return "wr.php";
    }

    // AJAX GET /project$
    public function getProjects() {
        global $VIEW_DATA;

        $uid = $_SESSION['user']['id'];

        // Retrieve all projects for this user
        $projects = $this->projectDao->all($uid);
        $VIEW_DATA['json'] = $projects;

        return "json.php";
    }

    // AJAX GET /user/(\d+)/project$
    public function getUserProjects() {
        global $URI_PARAMS;
        global $VIEW_DATA;

        $uid = $URI_PARAMS[1];

        // Retrieve all projects for this user
        $projects = $this->projectDao->all($uid);
        $VIEW_DATA['json'] = $projects;

        return "json.php";
    }

    // AJAX POST /project/(\D[^/]+)$
    public function create() {
        global $URI_PARAMS;
        global $VIEW_DATA;

        $name = urldecode($URI_PARAMS[1]);
        $uid = $_SESSION['user']['id'];

        try {
            $this->projectDao->db->beginTransaction();

            $pid = $this->projectDao->create($name, $uid);
            $this->functionDao->createMain($pid);

            $this->projectDao->db->commit();
        } catch (PDOException $e) {
            $this->projectDao->db->rollBack();
            throw $e;
        }
        $VIEW_DATA['json'] = $pid;
        return "json.php";
    }

    // AJAX POST /project/(\d+)/rename$
    public function rename() {
        global $URI_PARAMS;

        $pid = $URI_PARAMS[1];
        $name = filter_input(INPUT_POST, "name");
        $this->projectDao->rename($pid, $name);
    }

    // AJAX POST /project/(\d+)/delete
    public function delete() {
        global $URI_PARAMS;

        $pid = $URI_PARAMS[1];
        $this->projectDao->delete($pid);
    }

    // AJAX POST /project/(\d+)/(\w+)
    public function addFunction() {
        global $URI_PARAMS;
        global $VIEW_DATA;

        $pid = $URI_PARAMS[1];
        $name = $URI_PARAMS[2];
        $idata = filter_input(INPUT_POST, "idata");
        $vdata = filter_input(INPUT_POST, "vdata");

        $fid = $this->functionDao->create($pid, $name, $idata, $vdata);
        $VIEW_DATA['json'] = $fid;

        return "json.php";
    }

    // AJAX POST /function/(\d+)/vars
    public function updVars() {
        global $URI_PARAMS;
        $fid = $URI_PARAMS[1];

        $vdata = filter_input(INPUT_POST, "vdata");
        $this->functionDao->updVars($fid, $vdata);
    }

    // AJAX POST /function/(\d+)/ins
    public function updIns() {
        global $URI_PARAMS;
        $fid = $URI_PARAMS[1];

        $idata = filter_input(INPUT_POST, "idata");
        $this->functionDao->updIns($fid, $idata);
    }

    // AJAX POST /function/(\d+)/rename
    public function renameFunction() {
        global $URI_PARAMS;
        $fid = $URI_PARAMS[1];

        $name = filter_input(INPUT_POST, "name");
        $this->functionDao->rename($fid, $name);
    }

    // AJAX POST /function/(\d+)/delete
    public function deleteFunction() {
        global $URI_PARAMS;
        $fid = $URI_PARAMS[1];

        $this->functionDao->delete($fid);
    }

}
