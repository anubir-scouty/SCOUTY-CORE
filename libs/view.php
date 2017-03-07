<?php

class View extends Apps {

    public $title;
    public $meta_desc;
    public $meta_keywords;
    public $search_index;
		public $html_string;
    public $css_string;
    public $js_string;

    public function __construt() {

        global $equrl;
    }

    public function loadHeader() {

        global $equrl;
        // $eqApp = new Apps();

        $meta_keywords    = '';
        $meta_description = '';
        $search_index     = '';

        $this->html_string = "<!DOCTYPE html>\n";
        $this->html_string .= "<html>";
        $this->html_string .= "<head>\n";
        $this->html_string .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n";
        $this->html_string .= "<meta charset=\"utf-8\">\n";
        $this->html_string .= "<title>" . $this->title . "</title>\n";
        $this->html_string .= "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no\" />\n";
        $this->html_string .= "<meta name=\"apple-mobile-web-app-capable\" content=\"yes\">\n";
        $this->html_string .= "<meta name=\"apple-touch-fullscreen\" content=\"yes\">\n";
        $this->html_string .= "<meta name=\"apple-mobile-web-app-status-bar-style\" content=\"default\">\n";
        $this->html_string .= "<meta name=\"description\" content=\"" . $this->meta_desc . "\">\n";
        $this->html_string .= "<meta name=\"keywords\" content=\"" . $this->meta_keywords . "\">\n";
        $this->html_string .= "<meta name=\"robots\" content=\"index," . $this->search_index . "\" />\n";
        $this->html_string .= "<link rel=\"shortcut icon\" href=\"" . _RES_  . "favicon.ico\">\n";

        // $html .= $this->meta ? $this->meta : "";
				echo $this->html_string;
    }

    public function loadBody() {

        // global $bundleConf,
        // 	   $equrl;
        //
        // if($bundleConf['pages'][$equrl[0]]['breadcrumbs'] == true){
        // 	require 'template/breadcrumbs.phtml';
        // }
        include(_SITEBODY_);
    }

    public function loadFooter() {

        global $equrl;
        //

        // $bundleGlo = include('libs/config/bundle.config.php');
        // $bundleMod = include('plugins/' . $equrl[0] . '/config/bundle.config.php');
        // $eqApp = new Apps();
        // $latest = $eqApp->latestProps();
        include(_SITEFOOT_);
        echo "\n</body>\n" . $this->js_string . "\n</html>";
    }

    public function render($name, $args = false) {
        //renders the view along with passed data
        global $equrl;
        // $eqApp = new Apps();
        // $parishArr = $eqApp->parishArr();
        $newUrl = $this->camelCaseUrl($equrl[0]);

        if (file_exists('plugins/' . $newUrl . '/views/' . $name . '.phtml')) {
            require 'plugins/' . $newUrl . '/views/' . $name . '.phtml';
        } else {
            ob_clean();
            die('File: "plugins/' . $newUrl . '/views/' . $name . '.phtml" does not exist!');
        }
    }


    public function loadPage() {

        global $equrl;
        $this->loadHeader();
        $bundleGlo = $this->getBundle('libs/config/bundle.config.php');
        $bundleMod = $this->getBundle('plugins/' . $equrl[0] . '/config/bundle.config.php');
        $this->buildGlobalResources($bundleGlo, $equrl);
        $this->buildModularResources($bundleMod, $equrl);
				echo $this->css_string;
        $this->loadBody();
    }

    public function buildGlobalResources($res) {
        if (!empty($res)) {
            $this->resBuild($res['global_res']);
        }
    }

    public function getBundle($file) {
        if (file_exists($file)) {
            return include($file);

        }
    }

    public function buildModularResources($res, $url) {
        if (!empty($res)) {
            if ($res['module_name'] == $url[0]) {
                //means that the module name is correct
                //run core files first
                $this->resBuild($res['resources']['core_pages'], $url[0]);
            }
            if(isset($url[1])){
                if(array_key_exists($url[1],$res['resources']['sub_pages'])){
                    $this->resBuild($res['resources']['sub_pages'][$url[1]], $url[0]);
                }
            }
        }
    }

    public function resBuild($files, $module = false) {

        if (array_key_exists('css', $files)) {
            $pre = $module ? _PLUG_ . $module . '/' : _RES_;
            foreach ($files['css'] as $file) {
                if (!empty($file)) {
                    $this->css_string .= "<link href=\"" . (strpos($file, "http://") !== false || strpos($file, "https://") !== false ? '' : $pre) . $file . "\" rel=\"stylesheet\">\n";
                }
            }
        }
        if (array_key_exists('js', $files)) {
            $pre = $module ? _PLUG_ . $module . '/' : _RES_;
            foreach ($files['js'] as $file) {
                if (!empty($file)) {
                    $this->js_string .= "<script type=\"text/javascript\" src=\"" . (strpos($file, "http://") !== false || strpos($file, "https://") !== false ? '' : $pre) . $file . "\"></script>\n";
                }
            }
        }
    }

    public function camelCaseUrl($url) {
        $a = explode("-", $url);

        $str = "";
        foreach ($a as $key => $val) {
            $str .= ($key > 0 ? ucfirst($val) : $val);
        }

        return $str;
    }

}
