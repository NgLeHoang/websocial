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