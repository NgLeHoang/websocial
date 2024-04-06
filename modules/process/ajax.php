<?php

    if (isset($_GET['follow'])) {
        $userId = $_POST['user_Id'];
        $user_info = getUser($userId);

        if (followUser($userId)) {
            $response['status'] = true;
            $seen = false;
            $response['notification'] = '<div class="d-flex justify-content-between border-bottom">
            <div class="d-flex align-items-center p-2">
                <div><img src="assets/img/profile/'.$user_info['profile_pic'].'" alt="" width="40" height="40"
                        class="rounded-circle border">
                </div>
                <div>&nbsp;&nbsp;&nbsp;</div>
                <div class="d-flex flex-column justify-content-center">
                    <a class="text-decoration-none text-dark" href="#">
                        <h6 style="margin: 0px; font-size: small;">
                        '.$user_info['first_name'].' '.$user_info['last_name'].'
                        </h6>
                    </a>
                    <p style="margin:0px; font-size: small;" class="">'.$user_info['first_name'].' '.$user_info['last_name'].' has followed you</p>
                    <p style="font-size: small;" class="timeago text-small" datetime=""></p>
                </div>
            </div>
            <div class="d-flex align-items-center">
                <div class="p-1 bg-primary rounded-circle '.($seen?'d-none':'').'"></div>
            </div>
        </div>';
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

    if (isset($_GET['getnotification'])) {
        $notifications = getNotifications();
        $notification_list = '';
        foreach ($notifications as $noti) {
            $noti_user = getUser($noti['from_user_Id']);

            $seen = false;
            if ($noti['read_status'] == 1 || $noti['from_user_Id'] == $_SESSION['userdata']['id']) {
                $seen = true;
            }
            $postexist = false;
            if ($noti['post_Id']) {
                $postexist = true;
            } 
            $notification_list .= '<div class="d-flex align-items-center justify-content-between border-bottom '.($postexist?'data-bs-toggle="modal" data-bs-targer="#postview'.$noti['post_Id'].'"':'').'">
                <div class="d-flex align-items-center p-2">
                    <div><img src="assets/img/profile/'.$noti_user['profile_pic'].'" alt="" width="40" height="40"
                            class="rounded-circle border">
                    </div>
                    <div>&nbsp;&nbsp;&nbsp;</div>
                    <div class="d-flex flex-column justify-content-center">
                        <h6 style="margin: 0px;">
                            <a class="text-decoration-none text-dark"
                                href="?module=users&action=profile&name='.$noti_user['username'].'">@'.$noti_user['username'].'</a>
                        </h6>
                        <p style="margin:0px;" class="text-muted">'.$noti['description'].'</p>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <div class="p-1 bg-primary rounded-circle '.($seen?'d-none':'').'"></div>
                </div>
            </div>';
        }

        $json['notificationlist'] = $notification_list;
        echo json_encode($json);
    }

    if (isset($_GET['addnotification'])) {
        $post_Id = $_POST['post_Id'];
        $user_Id = $_POST['user_Id'];
        $type = $_POST['type'];
        $description = '';

        $current_user = getUser($_SESSION['userdata']['id']);

        if ($type == "comment") {
            $description = $current_user['first_name'] . ' ' . $current_user['last_name'] . ' has commented on your post.';
        }        

        if(addNotification($post_Id, $description, $user_Id)) {
            $response['status'] = true;
        } else {
            $response['status'] = false;
        }

        echo json_encode($response);
    }