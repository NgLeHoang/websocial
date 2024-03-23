<?php

if (isset($_GET['forgotpassword'])) {
    $filterAll = filterData();
    $email = $filterAll['email'];
    $_SESSION['forgot_email'] = $email;
    if (!empty($email)) {
        if (!isEmailRegisted($email)) {
            setFlashData('msg', 'Email is not registered!');
            setFlashData('msg_type', 'danger');
        } else {
            $_SESSION['code'] = $code = rand(111111,999999);
            $sendCode = sendMail($email, 'Forgot Your Password ?', $code);
            if ($sendCode) {
                setFlashData('msg', 'Please check email to give code');
                setFlashData('msg_type', 'success');
                } else {
                setFlashData('msg', 'System has error, please try again later!');
                setFlashData('msg_type', 'danger');
            }
        }
    } else {
        setFlashData('msg', 'Please input your email!');
        setFlashData('msg_type', 'danger');
    }
    redirect('?module=auth&action=forgot');
}

if (isset($_GET['verifycode'])) {
    $user_code = $_POST['code'];
    $code = $_SESSION['code'];
    if (!empty($user_code)) {
        if ($code == $user_code) {
            $_SESSION['auth_temp'] = true;
        } else {
            setFlashData('msg', 'Incorrect verification code!');
            setFlashData('msg_type', 'danger');
        }
    } else {
        setFlashData('msg', 'Please input verification code!');
        setFlashData('msg_type', 'danger');
    }
    redirect('?module=auth&action=forgot');
}

if (isset($_GET['resetpassword'])) {
    $filterAll = filterData();
    $password = $filterAll['password'];
    $email = $_SESSION['forgot_email'];
    if (!empty($password)) {
        $resetPassword = resetPassword($email, $password);
        if ($resetPassword) {
            redirect('?module=auth&action=signin');
            session_destroy();
        }
    } else {
        setFlashData('msg', 'Enter your new password');
        setFlashData('msg_type', 'danger');
        redirect('?module=auth&action=forgot');
    }
}