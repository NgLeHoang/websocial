<?php
    layouts('header', ['pageTitle' => 'Forgot Password']);

    if ((isset($_SESSION['code'])) && (!isset($_SESSION['auth_temp']))){
        $action = 'verifycode';
    } else if ((isset($_SESSION['code'])) && (isset($_SESSION['auth_temp']))){
        $action = 'resetpassword';
    } else {
        $action = 'forgotpassword';
    }

    $msg = getFlashData('msg');
    $msg_type = getFlashData('msg_type');
?>
<div class="login">
    <div class="col-4 bg-white border rounded p-4 shadow-sm">
        <form method="post" action="?module=process&action=forgot&<?=$action?>">
            <div class="d-flex justify-content-center">

            </div>
            <h1 class="h5 mb-3 fw-normal">Forgot Your Password ?</h1>
            <?php
                if (!empty($msg)) {
                    alertMsg($msg, $msg_type);
                }
            ?>
            <?php
                if ($action == 'forgotpassword') {
                    ?>
                        <div class="form-floating">
                            <input type="email" name="email" class="form-control rounded-0" placeholder="username/email">
                            <label for="floatingInput">Enter your email</label>
                        </div>
                        <br>
                        <button class="btn btn-primary" type="submit">Send Verification Code</button>
                    <?php
                }
            ?>
            <?php
                if ($action == 'verifycode') {
                    ?>
                        <p>Enter 6 Digit Code Sended to You <?=$_SESSION['forgot_email']?></p>
                        <div class="form-floating mt-1">
                            <input type="password" name="code" class="form-control rounded-0" id="floatingPassword" placeholder="Password">
                            <label for="floatingPassword">######</label>
                        </div>
                        <br>
                        <button class="btn btn-primary" type="submit">Verify Code</button>
                    <?php
                }
            ?>
            <?php
                if ($action == 'resetpassword') {
                    ?>
                        <p>Enter New Password For Account - <?=$_SESSION['forgot_email']?></p>
                        <div class="form-floating mt-1">
                            <input type="password" name="password" class="form-control rounded-0" id="floatingPassword" placeholder="Password">
                            <label for="floatingPassword">Enter new password</label>
                        </div>
                        <br>
                        <button class="btn btn-primary" type="submit">Change Password</button>
                    <?php
                }
            ?>
            <br>
            <a href="?module=auth&action=signin" class="text-decoration-none mt-2 d-block"><i class="bi bi-arrow-left-circle-fill"></i> 
                Go Back To Login
            </a>
        </form>
    </div>
</div>