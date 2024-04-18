<?php
    layouts('header', ['pageTitle' => 'Home']);

    if (!isLogin()) {
        redirect('?module=auth&action=signin');
    }

    $_SESSION['postdata'] = filterHomePost();
    $userData = $_SESSION['userdata'];
    $postData  = $_SESSION['postdata'];
    $follow_suggest = filterFollowSuggestion();

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
        alertMsg($msg.' '.$msgErrors, $msg_type);
    }

    if (count($postData) < 1) {
        echo "<p class='p-2 bg-white border rounded text-center mt-5'>Follow Someone or Add Post To See The Post.</p>";
    }
    else { 
        foreach ($postData as $post) {
            $count_likes = countLikePost($post['id']);
            $count_comments = getComments($post['id']);
            $d_none = false;
            if ($userData['id'] != $post['user_Id']) {
                $d_none = true;
            }
            ?>
            <div class="card mt-4">
                <div class="card-title d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center p-2">
                        <a href="?module=users&action=profile&name=<?=$post['username']?>" class="text-decoration-none text-dark">
                            <img src="assets/img/profile/<?=$post['profile_pic']; ?>" width="30" alt="" height="30"
                                class="rounded-circle border">&nbsp;&nbsp;
                            <?=$post['first_name']; ?> <?=$post['last_name']; ?>
                        </a>
                        <a href="?module=home&action=postview&id=<?=$post['id']?>" class="text-decoration-none text-dark p-2">• <?php echo getTimeOnPost($post['created_at']) ?></a>
                    </div>
                    <div class="dropdown p-2">
                        <span role="button" id="dropdownDelete"
                            data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis"></i></span>
                        <ul class="dropdown-menu <?=$d_none ? 'd-none' : ''?>" aria-labelledby="dropdownDelete">
                            <li><button class="dropdown-item delete-post" data-user-id="<?=$post['user_Id']?>" data-post-id="<?=$post['id']?>"><i class="fa-solid fa-x"></i> Delete post</button></li>
                        </ul>
                    </div>
                </div>
                <?php
                    if ($post['post_img']) {
                        ?>
                            <img style="border-radius: 4px;" src="assets/img/post/<?=$post['post_img']; ?>" class="" alt="...">
                            <h4 style="font-size: x-larger" class="p-2 border-bottom">
                            <?php
                                require "modules/action/countlikecomment.php";
                            ?>
                        <?php
                    }

                    if (!$post['post_img']) {
                        ?>
                            <h4 style="font-size: x-larger" class="p-2 border-bottom">
                        <?php
                            require "modules/action/countlikecomment.php";
                    }
                    
                    require "modules/action/userlikemodal.php"; 
                    require "modules/action/postviewhomemodal.php";
                ?>
                <div class="card-body d-flex">
                    <p style="font-weight: 600;"><?=$post['username']?></p>
                    <p class="ms-1"><?=$post['post_text']; ?></p>
                </div>

                <div class="input-group p-2 border-top">
                    <input type="text" class="form-control rounded-0 border-0 comment-input" placeholder="say something.."
                        aria-label="Recipient's username" aria-describedby="button-addon2">
                    <button class="btn btn-outline-primary rounded-0 border-0 add-comment" data-post-id="<?=$post['id']?>" data-user-id="<?=$post['user_Id']?>" type="button"
                        id="button-addon2">Post</button>
                </div>

            </div>
            <?php
        }
    }
?>
    </div>
    <div class="col-5 mt-4 p-3">
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
            <?php
                foreach ($follow_suggest as $user_suggest) {
            ?>
            <div class="d-flex justify-content-between">
                <div class="d-flex align-items-center p-2">
                    <div><img src="assets/img/profile/<?=$user_suggest['profile_pic']?>" width="40" alt="" height="40" class="rounded-circle border">
                    </div>
                    <div>&nbsp;&nbsp;</div>
                    <div class="d-flex flex-column justify-content-center">
                        <a href="?module=users&action=profile&name=<?=$user_suggest['username']?>" class="text-decoration-none text-dark"><h6 style="margin: 0px;font-size: small;"><?=$user_suggest['first_name'].' '.$user_suggest['last_name']?></h6></a>
                        <p style="margin:0px;font-size:small" class="text-muted">@<?=$user_suggest['username']?></p>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <button class="btn btn-sm btn-primary follow-btn" data-user-id='<?=$user_suggest['id']?>'>Follow</button>

                </div>
            </div>
            <?php
                }
                if (count($follow_suggest) < 1) {
                    echo "<p class='p-2 bg-white border rounded text-center'>No Suggestion User</p>";
                }
            ?>
        </div>
    </div>
</div>

<?php
    layouts('footer');
?>