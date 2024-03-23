<?php

    $_SESSION['code'] = $code = rand(111111,999999);
    $email = $_SESSION['email'];
    sendMail($email, 'Verify Your Email', $code);
    setFlashData('msgcode', 'Verifycation code has resended!');

    redirect('?module=auth&action=verifyemail');

?>