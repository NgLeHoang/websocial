<?php
    layouts('header', $data = ['pageTitle' => 'Sign in']);

    if (isPost()) {
        $login_data = filterData();
        
        $errors = validateSigninForm($login_data);
        if (empty($errors)) {
            checkUser($login_data);
        }
    }

    $msg = getFlashData('msg');
    $msg_type = getFlashData('msg_type');
    
?>


<div class="login">
    <div class="col-4 bg-white border rounded p-4 shadow-sm">
        <form method="post">
            <h1 class="h5 mb-3 fw-normal">Please sign in</h1>
            <?php
                if (!empty($msg)) {
                    alertMsg($msg, $msg_type);
                }
            ?>
            <div class="form-floating">
                <input type="text" name="username_email" class="form-control rounded-0" placeholder="username/email">
                <label for="floatingInput">username/email</label>
            </div>

            <div class="form-floating mt-1">
                <input type="password" name="password" class="form-control rounded-0" id="floatingPassword" placeholder="Password">
                <label for="floatingPassword">password</label>
            </div>

            <div class="mt-3 d-flex justify-content-between align-items-center">
                <button class="btn btn-primary" type="submit">Sign in</button>
                <a href="?module=auth&action=signup" class="text-decoration-none">Create New Account</a>


            </div>
            <a href="?module=auth&action=forgot" class="text-decoration-none mt-2 d-block">Forgot password ?</a>
        </form>
    </div>
</div>