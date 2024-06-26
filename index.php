<?php
    date_default_timezone_set("Asia/Ho_Chi_Minh");
    session_start();
    require_once "config.php";

    // PHPMailer Library
    require_once './data/phpmailer/Exception.php';
    require_once './data/phpmailer/PHPMailer.php';
    require_once './data/phpmailer/SMTP.php';

    require_once './data/functions.php';
    require_once './data/sessions.php';
    require_once './data/database.php';

    $module = _MODULE;
    $action = _ACTION;

    if (!empty($_GET['module'])) {
        if (is_string($_GET['module'])) {
            $module = trim($_GET['module']);
        }
    }

    if (!empty($_GET['action'])) {
        if (is_string($_GET['action'])) {
            $action = trim($_GET['action']);
        }
    }

    $path = 'modules/'. $module . '/'. $action . '.php';
    
    if (file_exists($path)) {
        require_once ($path);
    } else {
        require_once 'modules/error/404.php';
    }