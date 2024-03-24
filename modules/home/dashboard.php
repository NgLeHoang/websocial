<?php
    layouts('header', ['pageTitle' => 'Home']);

    if (!isLogin()) {
        redirect('?module=auth&action=signin');
    }

    $_SESSION['postdata'] = getPost();
    $userData = $_SESSION['userdata'];
    $postData  = $_SESSION['postdata'];

    require_once "modules/home/navbar.php";

    $msg = getFlashData('msg');
    $msg_type = getFlashData('msg_type');
    $errors = getFlashData('errors');
    if (!empty($errors)) {
        $msgErrors = reset($errors['profile_pic']);
    }
?>

<div class="container col-9 rounded-0 d-flex justify-content-between">
    <div class="col-8">
<?php
    if (!empty($msg) || !empty($errors)) {
        alertMsg($msg. $msgErrors, $msg_type);
    }

    foreach ($postData as $post) {
        ?>
        <div class="card mt-4">
            <div class="card-title d-flex justify-content-between  align-items-center">
                <div class="d-flex align-items-center p-2">
                    <img src="assets/img/profile/<?=$post['profile_pic']; ?>" width="30" alt="" height="30"
                        class="rounded-circle border">&nbsp;&nbsp;
                    <?=$post['first_name']; ?> <?=$post['last_name']; ?>
                </div>
                <div class="p-2">
                    <i class="bi bi-three-dots-vertical"></i>
                </div>
            </div>
            <?php
                if ($post['post_img']) {
                    ?>
                        <img src="assets/img/post/<?=$post['post_img']; ?>" class="" alt="...">
                        <h4 style="font-size: x-larger" class="p-2 border-bottom">
                            <i class="bi bi-heart"></i>
                            &nbsp;&nbsp;
                            <i class="bi bi-chat-left"></i>
                        </h4>
                    <?php
                }
            ?>
            
            <div class="card-body">
                <p><?=$post['post_text']; ?></p>
                <p>Posted at: <?=$post['created_at']; ?></p>
            </div>

            <div class="input-group p-2 border-top">
                <input type="text" class="form-control rounded-0 border-0" placeholder="say something.."
                    aria-label="Recipient's username" aria-describedby="button-addon2">
                <button class="btn btn-outline-primary rounded-0 border-0" type="button"
                    id="button-addon2">Post</button>
            </div>

        </div>
        <?php
    }
?>


    </div>

    <div class="col-4 mt-4 p-3">
        <div class="d-flex align-items-center p-2">
            <div><img src="assets/img/profile/<?=$userData['profile_pic']; ?>" width="60" alt="" height="60"
                    class="rounded-circle border">
            </div>
            <div>&nbsp;&nbsp;&nbsp;</div>
            <div class="d-flex flex-column justify-content-center align-items-center">
                <h6 style="margin: 0px;"><?=$userData['first_name']; ?> <?=$userData['last_name']; ?></h6>
                <p style="margin:0px;" class="text-muted">@<?=$userData['username']; ?></p>
            </div>
        </div>
        <div>
            <h6 class="text-muted p-2">You Can Follow Them</h6>
            <div class="d-flex justify-content-between">
                <div class="d-flex align-items-center p-2">
                    <div><img src="assets/img/avatar.jpg" width="40" alt="" height="40" class="rounded-circle border">
                    </div>
                    <div>&nbsp;&nbsp;</div>
                    <div class="d-flex flex-column justify-content-center">
                        <h6 style="margin: 0px;font-size: small;">Bill Gatesaw</h6>
                        <p style="margin:0px;font-size:small" class="text-muted">@funnybill</p>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <button class="btn btn-sm btn-primary">Follow</button>

                </div>
            </div>
            <div class="d-flex justify-content-between">
                <div class="d-flex align-items-center p-2">
                    <div><img src="assets/img/avatar.jpg" width="40" alt="" height="40" class="rounded-circle border">
                    </div>
                    <div>&nbsp;&nbsp;</div>
                    <div class="d-flex flex-column justify-content-center">
                        <h6 style="margin: 0px;font-size: small;">Tailor Swift</h6>
                        <p style="margin:0px;font-size:small" class="text-muted">@itstailorsong</p>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <button class="btn btn-sm btn-primary">Follow</button>

                </div>
            </div>
            <div class="d-flex justify-content-between">
                <div class="d-flex align-items-center p-2">
                    <div><img src="assets/img/avatar.jpg" width="40" alt="" height="40" class="rounded-circle border">
                    </div>
                    <div>&nbsp;&nbsp;</div>
                    <div class="d-flex flex-column justify-content-center">
                        <h6 style="margin: 0px;font-size: small;">Siri Bottom</h6>
                        <p style="margin:0px;font-size:small" class="text-muted">@siribottom34</p>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <button class="btn btn-sm btn-primary">Follow</button>

                </div>
            </div>
            <div class="d-flex justify-content-between">
                <div class="d-flex align-items-center p-2">
                    <div><img src="assets/img/avatar.jpg" width="40" alt="" height="40" class="rounded-circle border">
                    </div>
                    <div>&nbsp;&nbsp;</div>
                    <div class="d-flex flex-column justify-content-center">
                        <h6 style="margin: 0px;font-size: small;">Angelika Johnson</h6>
                        <p style="margin:0px;font-size:small" class="text-muted">@ajohnson23</p>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <button class="btn btn-sm btn-primary">Follow</button>

                </div>
            </div>
            <div class="d-flex justify-content-between">
                <div class="d-flex align-items-center p-2">
                    <div><img src="assets/img/avatar.jpg" width="40" alt="" height="40" class="rounded-circle border">
                    </div>
                    <div>&nbsp;&nbsp;</div>
                    <div class="d-flex flex-column justify-content-center">
                        <h6 style="margin: 0px;font-size: small;">Steve Jon</h6>
                        <p style="margin:0px;font-size:small" class="text-muted">@steve1998</p>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <button class="btn btn-sm btn-primary">Follow</button>

                </div>
            </div>
            <div class="d-flex justify-content-between">
                <div class="d-flex align-items-center p-2">
                    <div><img src="assets/img/avatar.jpg" width="40" alt="" height="40" class="rounded-circle border">
                    </div>
                    <div>&nbsp;&nbsp;</div>
                    <div class="d-flex flex-column justify-content-center">
                        <h6 style="margin: 0px;font-size: small;">Edward Smith</h6>
                        <p style="margin:0px;font-size:small" class="text-muted">@edwardsm</p>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <button class="btn btn-sm btn-primary">Follow</button>

                </div>
            </div>
            <div class="d-flex justify-content-between">
                <div class="d-flex align-items-center p-2">
                    <div><img src="assets/img/avatar.jpg" width="40" alt="" height="40" class="rounded-circle border">
                    </div>
                    <div>&nbsp;&nbsp;</div>
                    <div class="d-flex flex-column justify-content-center">
                        <h6 style="margin: 0px;font-size: small;">Mayank Sharma</h6>
                        <p style="margin:0px;font-size:small" class="text-muted">@mayankiscool</p>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <button class="btn btn-sm btn-primary" style="">Follow</button>

                </div>
            </div>


        </div>
    </div>
</div>

<?php
    layouts('footer');
?>