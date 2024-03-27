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