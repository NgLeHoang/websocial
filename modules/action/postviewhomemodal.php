<div class="modal fade" id="postview<?=$post['id']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-body d-flex p-0">
                <?php
                    if ($post['post_img']) {
                        ?>
                        <div class="col-8">
                            <img src="assets/img/post/<?=$post['post_img']?>" class="w-100 rounded-start">
                        </div>
                        <?php
                    }
                ?>
                <div class="col-4 d-flex flex-column">
                    <div class="d-flex align-items-center p-2 border-bottom">
                        <div><img src="assets/img/profile/<?=$post['profile_pic']?>" alt="" height="50" width="50" class="rounded-circle border">
                        </div>
                        <div>&nbsp;&nbsp;&nbsp;</div>
                        <div class="d-flex flex-column justify-content-start align-items-center">
                            <h6 style="margin: 0px;"><?=$post['first_name'].' '.$post['last_name']?></h6>
                            <p style="margin:0px;" class="text-muted">@<?=$post['username']?></p>
                        </div>
                    </div>
                    <div class="flex-fill align-self-stretch overflow-auto" id="comment-section<?=$post['id']?>" style="height: 100px;">
                        <?php
                            $comments = getComments($post['id']);
                            if (is_array($comments)) {
                                foreach ($comments as $comment) {
                                    $current_user = getUser($comment['user_Id']);
                                    ?>  
                                    <div class="d-flex align-items-center p-2">
                                        <div><img src="assets/img/profile/<?=$current_user['profile_pic']?>" alt="" width="40" height="40" class="rounded-circle border">
                                        </div>
                                        <div>&nbsp;&nbsp;&nbsp;</div>
                                        <div class="d-flex flex-column justify-content-start align-items-start">
                                            <h6 style="margin: 0px;"><a class="text-decoration-none text-dark" href="?module=users&action=profile&name=<?=$current_user['username']?>">@<?=$current_user['username']?></a></h6>
                                            <p style="margin:0px;" class="text-muted"><?=$comment['comment']?></p>
                                        </div>
                                    </div>
                                    <?php
                                }
                            } else {
                                echo "<p class='p-2 text-center my-1 no-comment'>No comments.</p>";
                            }
                        ?>
                    </div>
                    <div class="input-group p-2 border-top">
                        <input type="text" class="form-control rounded-0 border-0 comment-input" placeholder="say something.."
                            aria-label="Recipient's username" aria-describedby="button-addon2">
                        <button class="btn btn-outline-primary rounded-0 border-0 add-comment" data-cs="comment-section<?=$post['id']?>" data-post-id="<?=$post['id']?>" data-user-id="<?=$post['user_Id']?>" type="button"
                            id="button-addon2">Post</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>