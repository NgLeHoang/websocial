<?php
    // Create import layouts
    function layouts($layout_name = 'header', $data=[])
    {
        $path = _WEB_PATH_TEMPLATES . '/layouts/' .$layout_name . '.php';
        if (file_exists($path)) {
            require_once ($path);
        }
    }

    //Check method get or post
    function isGet() {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            return true;
        }
        return false;
    }

    function isPost() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            return true;
        }
        return false;
    }
    
   
    