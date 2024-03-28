<div class="modal fade" id="following_list" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Following</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php
                    if ($user_following) {
                        foreach($user_following as $user) {
                            $following_user = getUser($user['user_Id']);
                            $following_btn = '';
                            if (checkFollowStatus($user['user_Id'])) {
                                $following_btn = '<button class="btn btn-sm btn-danger unfollow-btn" data-user-id='.$following_user['id'].'>Unfollow</button>';
                            } else if ($userData['id'] == $user['user_Id']) {
                                $following_btn = '';
                            } else {
                                $following_btn = '<button class="btn btn-sm btn-primary follow-btn" data-user-id='.$following_user['id'].'>Follow</button>';
                            }
                            ?>
                                <div class="d-flex justify-content-between">
                                    <div class="d-flex align-items-center p-2">
                                        <div><img src="assets/img/profile/<?=$following_user['profile_pic']?>" width="40" alt="" height="40" class="rounded-circle border">
                                        </div>
                                        <div>&nbsp;&nbsp;</div>
                                        <div class="d-flex flex-column justify-content-center">
                                            <a href="?module=users&action=profile&name=<?=$following_user['username']?>" class="text-decoration-none text-dark"><h6 style="margin: 0px;font-size: small;"><?=$following_user['first_name'].' '.$following_user['last_name']?></h6></a>
                                            <p style="margin:0px;font-size:small" class="text-muted">@<?=$following_user['username']?></p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <?=$following_btn?>
                                    </div>
                                </div>
                            <?php
                        }
                    } else {
                        echo "<p class='text-center'>No User Following.</p>";
                    }
                ?>
            </div> 
        </div>
    </div>
</div>