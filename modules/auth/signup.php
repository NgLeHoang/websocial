<?php
    layouts('header');
    
    if (isPost()) {
        $errors = validateSignupForm($_POST);
        if (empty($errors)) {
            $createUser = createUser($_POST);
            if ($createUser) {
                setFlashData('msg', 'Registed success!');
                setFlashData('msg_type', 'success');
                redirect('?module=auth&action=signup');
            }
        } else {
            setFlashData('msg', 'Registerd failed!');
            setFlashData('msg_type', 'danger');
            setFlashData('errors', $errors);
            setFlashData('store_data_form', $_POST);
        }
    }

    $msg = getFlashData('msg');
    $msg_type = getFlashData('msg_type');
    $store_data_form = getFlashData('store_data_form');
    $errors = getFlashData('errors');
?>
<body>
    <div class="login">
        <div class="col-4 bg-white border rounded p-4 shadow-sm">
            <form method="post" action="">
                <h1 class="h5 mb-3 fw-normal">Create new account</h1>
                <?php
                    if (!empty($msg)) {
                        alertMsg($msg, $msg_type);
                    }
                ?>
                <div class="d-flex">
                    <div class="form-floating mt-1 col-6 ">
                        <input type="text" name="first_name" class="form-control rounded-0" placeholder="First name"
                        value="<?php echo store_data_form('first_name', $store_data_form) ?>">
                        <label for="floatingInput">first name</label>
                    </div>
                    <div class="form-floating mt-1 col-6">
                        <input type="text" name="last_name" class="form-control rounded-0" placeholder="Last name"
                        value="<?php echo store_data_form('last_name', $store_data_form) ?>">
                        <label for="floatingInput">last name</label>
                    </div>
                </div>
                <?php echo form_error('first_name', $errors) ?>
                <div class="d-flex gap-3 my-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="gender" id="exampleRadios1"
                            value="1" <?php echo (store_data_form('gender', $store_data_form) == 1) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="exampleRadios1">
                            Male
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="gender" id="exampleRadios3"
                            value="2" <?php echo (store_data_form('gender', $store_data_form) == 2) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="exampleRadios3">
                            Female
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="gender" id="exampleRadios2"
                            value="0" <?php echo (store_data_form('gender', $store_data_form) == 0) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="exampleRadios2">
                            Other
                        </label>
                    </div>
                </div>
                <div class="form-floating mt-1">
                    <input type="email" name="email" class="form-control rounded-0" placeholder="Email"
                    value="<?php echo store_data_form('email', $store_data_form) ?>">
                    <label for="floatingInput">email</label>
                </div>
                <?php echo form_error('email', $errors) ?>
                <div class="form-floating mt-1">
                    <input type="text" name="username" class="form-control rounded-0" placeholder="username"
                    value="<?php echo store_data_form('username', $store_data_form) ?>">
                    <label for="floatingInput">username</label>
                </div>
                <?php echo form_error('username', $errors) ?>
                <div class="form-floating mt-1">
                    <input type="password" name="password" class="form-control rounded-0" id="floatingPassword" placeholder="Password"
                    value="<?php echo store_data_form('password', $store_data_form) ?>">
                    <label for="floatingPassword">password</label>
                </div>
                <?php echo form_error('password', $errors) ?>

                <div class="mt-3 d-flex justify-content-between align-items-center">
                    <button class="btn btn-primary" type="submit">Sign Up</button>
                    <a href="?module=auth&action=login" class="text-decoration-none">Already have an account ?</a>

                </div>

            </form>
        </div>
    </div>
</body>

</html>