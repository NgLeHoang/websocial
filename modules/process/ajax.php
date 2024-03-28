<?php

    if (isset($_GET['follow'])) {
        $userId = $_POST['user_Id'];

        if (followUser($userId)) {
            $response['status'] = true;
        } else {
            $response['status'] = false;
        }

        echo json_encode($response);
    }

    if (isset($_GET['unfollow'])) {
        $userId = $_POST['user_Id'];

        if (unfollowUser($userId)) {
            $response['status'] = true;
        } else {
            $response['status'] = false;
        }

        echo json_encode($response);
    }

    if (isset($_GET['like'])) {
        $post_Id = $_POST['post_Id'];

        if (!checkLikeStatus($post_Id)) {
            if (likePost($post_Id)) {
                $response['status'] = true;
            } else {
                $response['status'] = false;
            }
        }

        echo json_encode($response);
    }

    if (isset($_GET['unlike'])) {
        $post_Id = $_POST['post_Id'];

        if (checkLikeStatus($post_Id)) {
            if (unlikePost($post_Id)) {
                $response['status'] = true;
            } else {
                $response['status'] = false;
            }
        }

        echo json_encode($response);
    }

    if (isset($_GET['addcomment'])) {
        $post_Id = $_POST['post_Id'];
        $comment = $_POST['comment'];

        if (addComment($post_Id, $comment)) {
            $current_user = getUser($_SESSION['userdata']['id']);
            $response['status'] = true;
            $response['comment'] = '<div class="d-flex align-items-center p-2">
            <div><img src="assets/img/profile/'.$current_user['profile_pic'].'" alt="" width="40" height="40" class="rounded-circle border">
            </div>
            <div>&nbsp;&nbsp;&nbsp;</div>
            <div class="d-flex flex-column justify-content-start align-items-start">
                <h6 style="margin: 0px;"><a class="text-decoration-none text-dark" href="?module=users&action=profile&name='.$current_user['username'].'">@'.$current_user['username'].'</a></h6>
                <p style="margin:0px;" class="text-muted">'.$_POST['comment'].'</p>
            </div>
        </div>';
        } else {
            $response['status'] = false;
        }
        

        echo json_encode($response);
    }