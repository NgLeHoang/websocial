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
            
            $response['commentpost'] = '<div class="d-flex align-items-center p-2">
            <div><img src="assets/img/profile/'.$current_user['profile_pic'].'" alt="" width="40" height="40" class="rounded-circle border">
            </div>
            <div>&nbsp;&nbsp;&nbsp;</div>
            <div style="border-radius: 12px; " class="d-flex flex-column justify-content-start align-items-start bg-secondary p-3">
                <h6 style="margin: 0px;"><a class="text-decoration-none text-light" href="?module=users&action=profile&name='.$current_user['username'].'">@'.$current_user['first_name']. ' ' .$current_user['last_name'].'</a></h6>
                <p style="margin:0px;" class="text-light">'.$_POST['comment'].'</p>
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
            $postexist = false;
            
            if ($noti['read_status'] == 1) {
                $seen = true;
            }
            
            if ($noti['post_Id']) {
                $postexist = true;
            } 
            
            $notification_list .= '<div class="d-flex align-items-center justify-content-between border-bottom notification_item" onclick="readNotification('.$noti['id'].')">
                <div class="d-flex align-items-center p-2">
                    <div><a class="text-decoration-none text-dark"
                    href="?module=users&action=profile&name='.$noti_user['username'].'"><img src="assets/img/profile/'.$noti_user['profile_pic'].'" alt="" width="40" height="40"
                            class="rounded-circle border"></a>
                    </div>
                    <div>&nbsp;&nbsp;&nbsp;</div>
                    <div class="d-flex flex-column justify-content-center">
                        <h6 style="margin: 0px;">
                            <a class="text-decoration-none text-dark"
                                href="?module=users&action=profile&name='.$noti_user['username'].'">@'.$noti_user['username'].'</a>
                        </h6>
                        <a class="text-decoration-none text-muted" href="'.($postexist ? '?module=home&action=postview&id='.$noti['post_Id'].'':'#').'"><p style="margin:0px;">'.$noti['description'].'</p></a>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <div class="p-1 bg-primary rounded-circle '.($seen?'d-none':'').'"></div>
                </div>
            </div>';
        }

        $json['notificationlist'] = $notification_list;

        $json['newnotificationcount'] = newNotificationCount();

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
        } else if ($type == "like") {
            $description = $current_user['first_name'] . ' ' . $current_user['last_name'] . ' has liked on your post.';
        } else if ($type == "follow") {
            $description = $current_user['first_name'] . ' ' . $current_user['last_name'] . ' has followed you.';
        }   

        if(addNotification($post_Id, $description, $user_Id)) {
            $response['status'] = true;
        } else {
            $response['status'] = false;
        }

        echo json_encode($response);
    }

    if (isset($_GET['readnotification'])) {
        $noti_Id = $_GET['noti_id'];
        if (readNotification($noti_Id)) {
            $response['status'] = true;
        } else {
            $response['status'] = false;
        }

        echo json_encode($response);
    }