<?php
    if ($_GET['name']) {
        $userData = $_SESSION['userdata'];

        $profile = getUserByUsername($_GET['name']);
        if ($profile) {
            layouts('header', ['pageTitle' => $profile['first_name'].' '.$profile['last_name']]);

            require_once "modules/home/navbar.php";

            $userId = $profile['id'];

            $profile_post = getPostById($userId);
            $user_follower = getFollower($userId);
            $user_following = getFollowing($userId);
            $blockUsers = getBlockedUser();

            $blocked = false;
            if (!empty($blockUsers)) {
                if ($blockUsers['blocked_user_Id'] == $profile['id']) {
                    $blocked = true;
                } else {
                    $blocked = false;
                }
            }
        } else {
            redirect('?module=users&action=usernotfound');
        }
    } 

    require_once "modules/action/followermodal.php";
    require_once "modules/action/followingmodal.php";
?>

<div class="container col-9 rounded-0">
    <div class="col-12 rounded p-4 mt-4 d-flex gap-5">
        <div class="col-4 d-flex justify-content-end align-items-start"><img src="assets/img/profile/<?=$profile['profile_pic']?>"
                class="img-thumbnail rounded-circle my-3" style="height:170px; width:170px;" alt="...">
        </div>
        <div class="col-8">
            <div class="d-flex flex-column">
                <div class="d-flex gap-5 align-items-center">
                    <span style="font-size: xx-large;"><?=$profile['first_name'].' '.$profile['last_name']?></span>
                    <?php
                        if ($userData['id'] != $profile['id']) {
                            ?>
                            <div class="dropdown <?=($blocked ? 'd-none' : '')?>">
                                <span class="" style="font-size:xx-large" role="button" id="dropdownMenuButton1"
                                    data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></span>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    <li><a class="dropdown-item" href="#"><i class="fa-solid fa-comment-dots"></i> Message</a></li>
                                    <li><button class="dropdown-item block-btn" data-blocked-user-id="<?=$profile['id']?>"><i class="fa-solid fa-circle-xmark"></i> Block</button></li>
                                </ul>
                            </div>
                            <?php
                        }
                    ?>
                    
                </div>
                <span style="font-size: larger;" class="text-secondary">@<?=$profile['username']?></span>
                <?php
                    if (!$blocked) {
                        ?>
                            <div class="d-flex gap-2 align-items-center my-3">
                                <a class="btn btn-sm btn-primary"><i class="fa-solid fa-newspaper"></i> <?php is_array($profile_post) ? $count = count($profile_post) : $count = 0; echo $count;?> Posts</a>
                                <a class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#follower_list"><i class="fa-solid fa-users"></i> <?php is_array($user_follower) ? $count = count($user_follower) : $count = 0; echo $count;?> Followers</a>
                                <a class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#following_list"><i class="fa-solid fa-user"></i> <?php is_array($user_following) ? $count = count($user_following) : $count = 0; echo $count;?> Following</a>
                            </div>
                        <?php
                    }
                ?>
                <?php
                    if ($userData['id']!=$profile['id']) {
                        ?>
                            <div class="d-flex gap-2 align-items-center my-1">
                                <?php
                                    if (checkFollowStatus($profile['id']) && !$blocked) {
                                        ?>
                                            <button class="btn btn-sm btn-danger unfollow-btn" data-user-id="<?=$profile['id']?>">Unfollow</button>
                                        <?php
                                    } else if ($blocked) {
                                        ?>
                                            <button class="btn btn-sm btn-danger unblock-btn" data-user-id="<?=$profile['id']?>">Unblocked</button>
                                        <?php
                                    } else {
                                        ?>
                                            <button class="btn btn-sm btn-primary follow-btn" data-user-id="<?=$profile['id']?>">Follow</button>
                                        <?php
                                    }
                                ?>
                            </div>
                        <?php
                    }
                ?>
                
            </div>
        </div>
    </div>
    <h3 class="border-bottom">Posts</h3>
    <?php
    if (!is_array($profile_post) && !$blocked) {
        echo "<p class='p-2 bg-white border rounded text-center my-3'>You don't have any post.</p>";
    }
    if ($blocked) {
        echo "<p class='p-2 bg-white border rounded text-center my-3'>You don't have allowed to see @".$profile['username']." post.</p>";
    }
    ?>
    <div class="gallery d-flex flex-wrap gap-2 mb-4">
        <?php
            if (is_array($profile_post) && !$blocked) {
                foreach ($profile_post as $post) {
                    if ($post['post_img']) {
                    ?>
                        <img src="assets/img/post/<?=$post['post_img']?>" data-bs-toggle="modal" data-bs-target="#postview<?=$post['id']?>" width="300px" class="rounded" />
                    <?php
                        require "modules/action/postviewmodal.php";
                    }
                }
            }   
        ?>
    </div>
</div>

<?php
    layouts('footer');
?>