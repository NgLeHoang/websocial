<?php 
    if (isLogin()) {
        $token = getSession('logintoken');
        delete('logintoken', "token = '$token'");
        removeSession('logintoken');
        session_destroy();
        redirect('?module=auth&action=signin');
    }

    redirect('?module=auth&action=signin');
?>