<?php
    if (isset($_GET['id'])) {
        $post_id = $_GET['id'];
        $query = "SELECT * FROM posts where id = $post_id";
        $postData = getOneRaw($query);
        if ($postData) {
            $post = $postData;
            $user_Id_post = $post['user_Id'];
            $user = getUser($user_Id_post);
            $count_likes = countLikePost($post_id);
            $count_comments = getComments($post_id);
        } else {
            alert("Somthing is wrong, please try again later...");
        }
        require_once "modules/home/navbar.php";
    }
?>
<div class="container col-9 rounded-0 d-flex justify-content-center">
    <div class="card mt-4">
        <div class="card-title d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center p-2">
                <a href="?module=users&action=profile&name=<?=$user['username']?>"
                    class="text-decoration-none text-dark">
                    <img src="assets/img/profile/<?=$user['profile_pic']; ?>" width="30" alt="" height="30"
                        class="rounded-circle border">&nbsp;&nbsp;
                    <?=$user['first_name']; ?> <?=$user['last_name']; ?>
                </a>
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
                ?>
                <div class="card-body">
                    <p><?=$post['post_text']; ?></p>
                    <p>Posted at: <?=$post['created_at']; ?></p>
                </div>
                <div class="card-body border-top" id="comment-section-post<?=$post['id']?>">
                    <?php
                        if (is_array($count_comments)) {
                            foreach ($count_comments as $comment) {
                                $current_user = getUser($comment['user_Id']);
                                ?>  
                                <div class="d-flex align-items-center p-2">
                                    <div><img src="assets/img/profile/<?=$current_user['profile_pic']?>" alt="" width="40" height="40" class="rounded-circle border">
                                    </div>
                                    <div>&nbsp;&nbsp;&nbsp;</div>
                                    <div style="border-radius: 12px; " class="d-flex flex-column justify-content-start align-items-start bg-secondary p-3">
                                        <h6 style="margin: 0px;"><a class="text-decoration-none text-light" href="?module=users&action=profile&name=<?=$current_user['username']?>"><?php echo $current_user['first_name'] .' '.$current_user['last_name']?></a></h6>
                                        <p style="margin:0px;" class="text-light"><?=$comment['comment']?></p>
                                    </div>
                                </div>
                                <?php
                                require "modules/action/userlikemodal.php";
                            }
                        }
                    ?>
                </div>

                <div class="input-group p-2 border-top">
                    <input type="text" class="form-control rounded-0 border-0 comment-input" placeholder="say something.."
                        aria-label="Recipient's username" aria-describedby="button-addon2">
                    <button class="btn btn-outline-primary rounded-0 border-0 add-comment" data-csp="comment-section-post<?=$post['id']?>" data-post-id="<?=$post['id']?>" data-user-id="<?=$post['user_Id']?>" type="button"
                        id="button-addon2">Post</button>
                </div>
    </div>
</div>