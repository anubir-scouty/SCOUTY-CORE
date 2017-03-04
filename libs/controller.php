<?php

class Controller extends View {

    public $globalNav;

    function __construct() {
      // echo 'hi';die;
      // $this->test();
    }

    public function loadModel($name) {

        global $url;
        $path = 'plugins/' . $name . '/Model/' . $name . '_model.php';

        if (file_exists($path)) {

            require $path;
            $modelName   = ucfirst($name) . '_Model';
            $this->model = new $modelName();
        }
    }
}
