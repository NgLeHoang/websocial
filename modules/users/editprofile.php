<?php
    layouts('header', ['pageTitle' => 'Edit profile']);

    require_once "modules/home/navbar.php";
    
    $userData = $_SESSION['userdata'];
    $userId = $userData['id'];

    if (isPost()) {
        $filterAll = filterData();
        $image = $_FILES['profile_pic'];
        $errors = validateUpdateProfile($filterAll, $image);
       
        if (empty($errors)) {
            $updateUser = updateProfile($filterAll, $image);

            if ($updateUser) {
                $userQuery = getOneRaw("SELECT * FROM users WHERE id = '$userId'");
                $_SESSION['userdata'] = $userQuery;

                setFlashData('msg', 'Edit profile success!');
                setFlashData('msg_type', 'success');
                redirect('?module=users&action=editprofile');
            } else {
                setFlashData('msg', 'Edit profile fail!');
                setFlashData('msg_type', 'danger');
            }
        } else {
            setFlashData('msg', 'Edit profile fail, please check data again!');
            setFlashData('msg_type', 'danger');
            setFlashData('errors', $errors);
        }

        redirect('?module=users&action=editprofile');
    }

    $msg = getFlashData('msg');
    $msg_type = getFlashData('msg_type');
    $errors = getFlashData('errors');
?>
<div class="container col-9 rounded-0 d-flex justify-content-between">
    <div class="col-12 bg-white border rounded p-4 mt-4 shadow-sm">
        <form method="post" enctype="multipart/form-data">
            <div class="d-flex justify-content-center">
            </div>
            <?php
                if (!empty($msg)) {
                    alertMsg($msg, $msg_type);
                }
            ?>
            <h1 class="h5 mb-3 fw-normal">Edit Profile</h1>
            <div class="form-floating mt-1 col-6">
                <img src="assets/img/profile/<?=$userData['profile_pic']; ?>" class="img-thumbnail my-3" style="height:150px;" alt="...">
                <div class="mb-3">
                    <label for="formFile" class="form-label">Change Profile Picture</label>
                    <input class="form-control" type="file" name="profile_pic" id="formFile">
                </div>
            </div>
            <?php echo form_error('profile_pic', $errors) ?>
            <div class="d-flex">
                <div class="form-floating mt-1 col-6 ">
                    <input type="text" name="first_name" class="form-control rounded-0" value="<?=$userData['first_name']; ?>">
                    <label for="floatingInput">first name</label>
                </div>
                <div class="form-floating mt-1 col-6">
                    <input type="text" name="last_name" class="form-control rounded-0" value="<?=$userData['last_name']; ?>">
                    <label for="floatingInput">last name</label>
                </div>
            </div>
            <?php echo form_error('first_name', $errors) ?> <?php echo form_error('last_name', $errors) ?>
            <div class="d-flex gap-3 my-3">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1"
                        value="option1" <?=$userData['gender'] == 1 ? 'checked' : ''?> disabled>
                    <label class="form-check-label" for="exampleRadios1">
                        Male
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios3"
                        value="option2" <?=$userData['gender'] == 2 ? 'checked' : ''?> disabled>
                    <label class="form-check-label" for="exampleRadios3">
                        Female
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios2"
                        value="option2" <?=$userData['gender'] == 0 ? 'checked' : ''?> disabled>
                    <label class="form-check-label" for="exampleRadios2">
                        Other
                    </label>
                </div>
            </div>
            <div class="form-floating mt-1">
                <input type="email" class="form-control rounded-0" value="<?=$userData['email']; ?>" disabled>
                <label for="floatingInput">email</label>
            </div>
            <div class="form-floating mt-1">
                <input type="text" name="username" class="form-control rounded-0" value="<?=$userData['username']; ?>">
                <label for="floatingInput">username</label>
            </div>
            <?php echo form_error('username', $errors) ?>
            <div class="form-floating mt-1">
                <input type="password" name="password" class="form-control rounded-0" id="floatingPassword" placeholder="Password">
                <label for="floatingPassword">new password</label>
            </div>
            <?php echo form_error('password', $errors) ?>
            <div class="mt-3 d-flex justify-content-between align-items-center">
                <button class="btn btn-primary" type="submit">Update Profile</button>

            </div>
        </form>
    </div>
</div>

<?php
    layouts('footer');
?>