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
                class="img-thumbnail rounded-circle my-3" style="height:170px;" alt="...">
        </div>
        <div class="col-8">
            <div class="d-flex flex-column">
                <div class="d-flex gap-5 align-items-center">
                    <span style="font-size: xx-large;"><?=$profile['first_name'].' '.$profile['last_name']?></span>
                    <?php
                        if ($userData['id'] != $profile['id']) {
                            ?>
                            <div class="dropdown">
                                <span class="" style="font-size:xx-large" role="button" id="dropdownMenuButton1"
                                    data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></span>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    <li><a class="dropdown-item" href="#"><i class="fa-solid fa-comment-dots"></i> Message</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="fa-solid fa-circle-xmark"></i> Block</a></li>
                                </ul>
                            </div>
                            <?php
                        }
                    ?>
                    
                </div>
                <span style="font-size: larger;" class="text-secondary">@<?=$profile['username']?></span>
                <div class="d-flex gap-2 align-items-center my-3">
                    <a class="btn btn-sm btn-primary"><i class="fa-solid fa-newspaper"></i> <?php is_array($profile_post) ? $count = count($profile_post) : $count = 0; echo $count;?> Posts</a>
                    <a class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#follower_list"><i class="fa-solid fa-users"></i> <?php is_array($user_follower) ? $count = count($user_follower) : $count = 0; echo $count;?> Followers</a>
                    <a class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#following_list"><i class="fa-solid fa-user"></i> <?php is_array($user_following) ? $count = count($user_following) : $count = 0; echo $count;?> Following</a>
                </div>
                <?php
                    if ($userData['id']!=$profile['id']) {
                        ?>
                            <div class="d-flex gap-2 align-items-center my-1">
                                <?php
                                    if (checkFollowStatus($profile['id'])) {
                                        ?>
                                            <button class="btn btn-sm btn-danger unfollow-btn" data-user-id="<?=$profile['id']?>">Unfollow</button>
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
    if (!is_array($profile_post)) {
        echo "<p class='p-2 bg-white border rounded text-center my-3'>You don't have any post.</p>";
    }
    ?>
    <div class="gallery d-flex flex-wrap gap-2 mb-4">
        <?php
            if (is_array($profile_post)) {
                foreach ($profile_post as $post) {
                    if ($post['post_img']) {
                    ?>
                        <img src="assets/img/post/<?=$post['post_img']?>" width="300px" class="rounded" />
                    <?php
                    }
                }
            }   
        ?>
    </div>
</div>

<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
    Launch demo modal
</button>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-body d-flex p-0">
                <div class="col-8">
                    <img src="img/post2.jpg" class="w-100 rounded-start">
                </div>
                <div class="col-4 d-flex flex-column">
                    <div class="d-flex align-items-center p-2 border-bottom">
                        <div><img src="./img/profile.jpg" alt="" height="50" class="rounded-circle border">
                        </div>
                        <div>&nbsp;&nbsp;&nbsp;</div>
                        <div class="d-flex flex-column justify-content-start align-items-center">
                            <h6 style="margin: 0px;">Monu Giri</h6>
                            <p style="margin:0px;" class="text-muted">@oyeitsmg</p>
                        </div>
                    </div>
                    <div class="flex-fill align-self-stretch overflow-auto" style="height: 100px;">

                        <div class="d-flex align-items-center p-2">
                            <div><img src="./img/profile2.jpg" alt="" height="40" class="rounded-circle border">
                            </div>
                            <div>&nbsp;&nbsp;&nbsp;</div>
                            <div class="d-flex flex-column justify-content-start align-items-start">
                                <h6 style="margin: 0px;">@osilva</h6>
                                <p style="margin:0px;" class="text-muted">its nice pic very good</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center p-2">
                            <div><img src="./img/profile2.jpg" alt="" height="40" class="rounded-circle border">
                            </div>
                            <div>&nbsp;&nbsp;&nbsp;</div>
                            <div class="d-flex flex-column justify-content-start align-items-start">
                                <h6 style="margin: 0px;">@osilva</h6>
                                <p style="margin:0px;" class="text-muted">its nice pic very good</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-center p-2">
                            <div><img src="./img/profile2.jpg" alt="" height="40" class="rounded-circle border">
                            </div>
                            <div>&nbsp;&nbsp;&nbsp;</div>
                            <div class="d-flex flex-column justify-content-start align-items-start">
                                <h6 style="margin: 0px;">@osilva</h6>
                                <p style="margin:0px;" class="text-muted">its nice pic very good</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-center p-2">
                            <div><img src="./img/profile2.jpg" alt="" height="40" class="rounded-circle border">
                            </div>
                            <div>&nbsp;&nbsp;&nbsp;</div>
                            <div class="d-flex flex-column justify-content-start align-items-start">
                                <h6 style="margin: 0px;">@osilva</h6>
                                <p style="margin:0px;" class="text-muted">its nice pic very good</p>
                            </div>
                        </div>

                    </div>
                    <div class="input-group p-2 border-top">
                        <input type="text" class="form-control rounded-0 border-0" placeholder="say something.."
                            aria-label="Recipient's username" aria-describedby="button-addon2">
                        <button class="btn btn-outline-primary rounded-0 border-0" type="button"
                            id="button-addon2">Post</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    layouts('footer');
?>