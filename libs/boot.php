<?php

class Boot {

    function __construct() {

        global $equrl;


        $equrl = isset($_GET['v']) ? $_GET['v'] : null;
        $equrl = rtrim($equrl, '/');
        $equrl = rtrim($equrl, '-');
        $equrl = explode('/', $equrl);
        foreach (glob("conf/*.php") as $filename) {
            include $filename;
        }

        $this->db = new Database();

        $equrl[0] = empty($equrl[0]) ? 'home' : $equrl[0];
        $newUrl   = $this->camelCaseUrl($equrl[0]);
        $file     = 'plugins/' . $newUrl . '/Controller/' . ucfirst($newUrl) . '.php';
        if (file_exists($file)) {
            require $file;
            $controller = new $newUrl;
            $controller->loadModel($equrl[0]);
            // calling methods, if the url has more than 2 parameters
            // run method with
            if (count($equrl) > 2) {
                if (method_exists($controller, $this->camelCaseUrl($equrl[1]))) {
                    $method = $equrl;
                    unset($method[0], $method[1]);
                    $customUrl = implode("/", $method);
                    $controller->{$this->camelCaseUrl($equrl[1])}($customUrl);
                } else {
				            $equrl[0] = "whoops";
				            $this->error();
                }
            } else {
                if (isset($equrl[1])) {
                    if (method_exists($controller, $this->camelCaseUrl($equrl[1]))) {
                        $controller->{$this->camelCaseUrl($equrl[1])}();
                    } else {
    				            $equrl[0] = "whoops";
                        $this->error();
                    }
                } else {
                    $controller->index();
                }
            }
        } else {
            $equrl[0] = "whoops";
            $this->error();
        }
    }

    function error() {
        require 'plugins/whoops/Controller/Whoops.php';
        $error = new Whoops();
        $error->index();
        return false;
    }

    function camelCaseUrl($url) {
        $a = explode("-", $url);

        $str = "";
        foreach ($a as $key => $val) {
            $str .= ($key > 0 ? ucfirst($val) : $val);
        }
        return $str;
    }
}
