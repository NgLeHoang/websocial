<?php
    layouts('header', ['pageTitle' => 'Verify email']);

    if (isPost()) {
        $code = $_SESSION['code'];
        $email = $_SESSION['email'];
        $filterAll = filterData();
        if (!empty($filterAll)) {
            if ($filterAll['code'] == $code) {
                $queryUser = getOneRaw("SELECT id FROM users WHERE email = '$email'");
                if (!empty($queryUser)) {
                    $userId = $queryUser['id'];
    
                    $dataUpdate = [
                        'status' => 1,
                        'updated_at' => date('Y-m-d H:i:s')
                    ];
    
                    $updateQuery = update('users', $dataUpdate, "id = $userId");
                    if ($updateQuery) {
                        redirect('?module=home&action=dashboard');
                    } else {
                        setFlashData('msg', 'System har error, please try again later!');
                        setFlashData('msg_type', 'danger');
                    }
                } else {
                    setFlashData('msg', 'User is not exist, please check again!');
                    setFlashData('msg_type', 'danger');
                }
            } else {
                setFlashData('msg', 'Code is not valid, please check again!');
                setFlashData('msg_type', 'danger');
            }
        } else {
            setFlashData('msg', 'Code is not empty!');
            setFlashData('msg_type', 'danger');
        }
    }

    $msgcode = getFlashData('msgcode');    
    $msg = getFlashData('msg');
    $msg_type = getFlashData('msg_type');
?>
<div class="login">
    <div class="col-4 bg-white border rounded p-4 shadow-sm">
        <form method="post">
            <div class="d-flex justify-content-center">
            </div>
            <h1 class="h5 mb-3 fw-normal">Verify Your Email</h1>
            <h1 class="h5 mb-3 fw-normal">(<?php echo $_SESSION['email']; ?>)</h1>
            <?php 
                if (!empty($msg)) {
                    alertMsg($msg, $msg_type);
                }
            ?>
            <p>Enter 6 Digit Code Sended to You</p>
            <div class="form-floating mt-1">
                <input type="password" name="code" class="form-control rounded-0" id="floatingPassword" placeholder="Password">
                <label for="floatingPassword">######</label>
            </div>
            <?php
                if (!empty($msgcode)) {
                    ?>
                        <p class="text-success"><?php echo $msgcode ?></p>
                    <?php
                }
            ?>
            <div class="mt-3 d-flex justify-content-between align-items-center">
                <a href="?module=auth&action=resendcode" class="text-decoration-none" type="submit">Resend Code</a>
                <button class="btn btn-primary" type="submit">Verify Email</button>
            </div>
        </form>
    </div>
</div>