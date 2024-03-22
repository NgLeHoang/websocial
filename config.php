<?php

    const _MODULE = 'home';
    const _ACTION = 'dashboard';

    // Information connect database
    const _HOST = 'localhost';
    const _DB = 'websocial';
    const _USER = 'root';
    const _PASS = '';

    // Setting host 
    define('_WEB_HOST', 'http://'. $_SERVER['HTTP_HOST'] . '/WebSocial');
    define('_WEB_HOST_TEMPLATES', _WEB_HOST . '/templates');
    define('_WEB_HOST_ASSETS', _WEB_HOST . '/assets');

    // Setting path
    define('_WEB_PATH', __DIR__);
    define('_WEB_PATH_TEMPLATES', _WEB_PATH . '/templates');