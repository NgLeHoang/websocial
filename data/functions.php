<?php

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    //Mail service
    function sendMail($to, $subject, $code) {

    //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'nguyenlehoang20034@gmail.com';                     //SMTP username
            $mail->Password   = 'zjfnamcrrxufkidz';                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom('nguyenlehoang20034@gmail.com', 'Le Hoang');
            $mail->addAddress($to);     //Add a recipient

            //Content
            $mail -> CharSet = "UTF-8";
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = 'Your verificatiton code is: <b>'.$code.'</b>';

            //PHPMailer SSL certificate verify failed
            $mail -> SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
                );

            $sendMail = $mail->send();
            if ($sendMail) {
                return $sendMail;
            }

        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    // Create import layouts
    function layouts($layout_name = 'header', $data=[])
    {
        $path = _WEB_PATH_TEMPLATES . '/layouts/' .$layout_name . '.php';
        if (file_exists($path)) {
            require_once ($path);
        }
    }

    //Check method get or post
    function isGet() {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            return true;
        }
        return false;
    }

    function isPost() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            return true;
        }
        return false;
    }


    // Filter Data from method get and post
    function filterData() {
        $filter_array = [];
        if (isGet()) {
            if (!empty($_GET)) {
                foreach ($_GET as $key => $value) {
                    $key = strip_tags($key);

                    if (is_array($value)) {
                        $filter_array[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                    } else {
                        $filter_array[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                    }
                }
            }
        }

        if (isPost()) {
            if (!empty($_POST)) {
                foreach ($_POST as $key => $value) {
                    $key = strip_tags($key);

                    if (is_array($value)) {
                        $filter_array[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                    } else {
                        $filter_array[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                    }
                }
            }
        }

        return $filter_array;
    }
    
    //Alert message
     function alertMsg($msg, $type='success') {
        echo '<div class="alert alert-'.$type.'">';
        echo $msg;
        echo '</div>';
    }

    //Redirect 
    function redirect($path ='') {
        header("Location: $path");
        exit;
    }

    //Alert error in form
    function form_error($field_name, $errors) {
        return (!empty($errors[$field_name])) ? '<span class="msg-error">' . reset($errors[$field_name]) . '</span>' : null;
    }

    // Store data from form
    function store_data_form($field_name, $storeData, $default = null) {
        $result = (!empty($storeData[$field_name])) ? $storeData[$field_name] : $default;
        return $result;
    }

    // Work with SQL
    function query($sql, $data=[], $check= false ) {
        global $connect;
        $result = false;

        try {
            $statement = $connect -> prepare($sql);

            if (!empty($data)) {
                $result = $statement -> execute($data);
            } else {
                $result = $statement -> execute();
            }
        } catch (Exception $ex) {
            echo $ex -> getMessage(). '<br>';
            echo 'File: ' . $ex -> getFile() . '<br>';
            echo 'Line: ' . $ex -> getLine();
            die();
        }

        if ($check) {
            return $statement;
        }

        return $result;
    }

    function insert($table, $data) {
        $key = array_keys($data);
        $field = implode(',', $key);
        $value  = ':' . implode(',:', $key);

        $sql = 'INSERT INTO ' . $table . '(' . $field . ')'.'VALUES('. $value . ')';

        $result = query($sql, $data);
        return $result;
    }

    function update($table, $data, $condition) {
        $update = '';
        foreach ($data as $key => $value) {
            $update .= $key . '= :' . $key . ',';
        }
        $update = trim($update, ',');

        if (!empty($condition)) {
            $sql = 'UPDATE ' . $table . ' SET ' . $update . ' WHERE ' . $condition;
        } else {
            $sql = 'UPDATE ' . $table . ' SET ' . $update;
        }

        $result = query($sql, $data);
        return $result;
    }

    function delete($table, $condition = '') {
        if (empty($condition)) {
            $sql = 'DELETE FROM ' . $table;
        } else {
            $sql = 'DELETE FROM ' . $table . ' WHERE ' . $condition;
        }
        
        $result = query($sql);
        return $result;
    }

    function getRaw($sql) {
        $result = query($sql, '', true);
    
        if (is_object($result)) {
            $dataFetch = $result -> fetchAll(PDO::FETCH_ASSOC);
        }
        
        return $dataFetch;
    }
    
    function getOneRaw($sql) {
        $result = query($sql, '', true);
        if (is_object($result)) {
            $dataFetch = $result -> fetch(PDO::FETCH_ASSOC);
        }
        return $dataFetch;
    }

    function getRows($sql) {
        $result = query($sql, '', true);
        if (!empty($result)) {
            return $result -> rowCount();
        }
    }

    //Checking duplicated email
    function isEmailRegisted($email) {
        $query = "SELECT * FROM users WHERE email = '$email'";
        if (getRows($query) > 0) {
            return true;
        }

        return false;
    }

    //Checking duplicated username
    function isUsernameRegisted($username) {
        $query = "SELECT * FROM users WHERE username = '$username'";
        if (getRows($query) > 0) {
            return true;
        }

        return false;
    }

    //Checking duplicated username by update profile 
    function isUsernameRegistedByOther($username) {
        $userId = $_SESSION['userdata']['id'];
        $query = "SELECT * FROM users WHERE username = '$username' && id != $userId";
        if (getRows($query) > 0) {
            return true;
        }

        return false;
    }

    // Validate form signup
    function validateSignupForm($form_data) {
        $errors = [];

        if (empty($form_data['first_name'])) {
            $errors['first_name']['required'] = 'First name is required input.';
        }

        if (empty($form_data['last_name'])) {
            $errors['last_name']['required'] = 'Last name is required input.';
        }

        if (empty($form_data['email'])) {
            $errors['email']['required'] = 'Email is required input.';
        } else {
            if (isEmailRegisted($form_data['email'])) {
                $errors['email']['exist'] = 'Email is exist in database.';
            }
        }

        if (empty($form_data['username'])) {
            $errors['username']['required'] = 'Username is required input.';
        } else {
            if (isUsernameRegisted($form_data['username'])) {
                $errors['username']['exist'] = 'Username is exist in database.';
            }
        }
    
        if (empty($form_data['password'])) {
            $errors['password']['required'] = 'Password is required input.';
        } else {
            if (strlen($form_data['password']) < 8) {
                $errors['password']['min_length'] = 'Password must have at least 8 characters.';
            }
        }
    
        return $errors; 
    }
    
    // Create user from signup form
    function createUser($form_data) {
        $dataInsert = [
            'first_name' => $form_data['first_name'],
            'last_name' => $form_data['last_name'],
            'gender' => $form_data['gender'],
            'email' =>  $form_data['email'],
            'username' => $form_data['username'],
            'password' => password_hash($form_data['password'], PASSWORD_DEFAULT),
            'created_at' => date('Y-m-d H:i:s')
        ];

        $insertQuery = insert('users', $dataInsert);
        
        if ($insertQuery) {
            return true;
        }

        return false;
    }

    // Check user in database
    function checkUser($login_data) {
        if (!empty($login_data['username_email']) && !empty($login_data['password'])) {
            $username_email = $login_data['username_email'];
            $password = $login_data['password'];

            $userQuery = getOneRaw("SELECT * FROM users WHERE email = '$username_email' || username = '$username_email'");
            
            if (!empty($userQuery)) {
                $_SESSION['userdata'] = $userQuery;
                $passwordHash = $userQuery['password'];
                $userId = $userQuery['id'];
                $status = $userQuery['status'];
                if (password_verify($password, $passwordHash)) {

                    // Check account is login
                    $userLogin = getRows("SELECT * FROM logintoken WHERE user_Id = $userId");
                    if ($userLogin > 0) {
                        setFlashData('msg', 'Account is login another page.');
                        setFlashData('msg_type', 'danger');
                        redirect('?module=auth&action=signin');
                    } else {
                        // Check status
                        if ($status == 0) {
                            setFlashData('msg', 'Account not active, please verify!');
                            setFlashData('msg_type', 'danger');
                        } else if ($status == 2) {
                            redirect('?module=auth&action=blocked');
                        } else {
                            // Create token login
                            $tokenLogin = sha1(uniqid().time());

                            $dataInsert = [
                                'user_Id' => $userId,
                                'token' => $tokenLogin,
                                'create_at' => date('Y-m-d H:i:s')
                            ];

                            $insertQuery = insert('logintoken', $dataInsert);
                            if ($insertQuery) {
                                setSession('logintoken', $tokenLogin);
                                redirect('?module=home&action=dashboard');
                            } else {
                                setFlashData('msg', 'Can not login, please try again later');
                                setFlashData('msg_type', 'danger');
                            }
                        }
                    }
                } else {
                    setFlashData('msg', 'Password is wrong, please reinput.');
                    setFlashData('msg_type', 'danger');
                }
            } else {
                setFlashData('msg', 'Username/email not exist.');
                setFlashData('msg_type', 'danger');
            }
        } else {
            setFlashData('msg', 'Please input email and password.');
            setFlashData('msg_type', 'danger');
        }
        redirect('?module=auth&action=signin');
    }

    // Validate signin form
    function validateSigninForm($form_data) {
        $errors = [];

        if (empty($form_data['username_email'])) {
            $errors['username']['required'] = 'Username/email is required input.';
        }
    
        if (empty($form_data['password'])) {
            $errors['password']['required'] = 'Password is required input.';
        }
    
        return $errors; 
    }
   
    // Check status login
    function isLogin() {
        $checkLogin = false;
        if (getSession('logintoken')) {
            $tokenLogin = getSession('logintoken');

            //Check token similar to token in database
            $queryToken = getOneRaw("SELECT user_Id FROM logintoken WHERE token='$tokenLogin'");
            if (!empty($queryToken)) {
                $checkLogin = true;
            } else {
                removeSession('logintoken');
            }
        }

        return $checkLogin;
    }

    function resetPassword($email, $password) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $dataUpdate = [
            'password' => $passwordHash,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $updateQuery = update('users', $dataUpdate, "email = '$email'");
        if ($updateQuery) {
            return true;
        }

        return false;
    }

    // Validate update form profile
    function validateUpdateProfile($form_data, $image_data) {
        $errors = [];

        if (empty($form_data['first_name'])) {
            $errors['first_name']['required'] = 'First name is required input.';
        }

        if (empty($form_data['last_name'])) {
            $errors['last_name']['required'] = 'Last name is required input.';
        }

        if (empty($form_data['username'])) {
            $errors['username']['required'] = 'Username is required input.';
        } else {
            if (isUsernameRegistedByOther($form_data['username'])) {
                $errors['username']['exist'] = $form_data['username']. ' is already registered.';
            }
        }
    
        if (!empty($form_data['password'])) {
            if (strlen($form_data['password']) < 8) {
                $errors['password']['min_length'] = 'Password must have at least 8 characters.';
            }
        }

        if ($image_data['name']) {
            $image = basename($image_data['name']);
            $type = strtolower(pathinfo($image, PATHINFO_EXTENSION));
            $size = $image_data['size']/1000;

            if ($type != 'jpg' && $type != 'jpeg' && $type != 'png') {
                $errors['profile_pic']['required'] = 'Only jpg, jpeg, png are allowed.';
            }
        }

        if ($size > 1000) {
            $errors['profile_pic']['sizemax'] = 'Upload image less then 1 mb';
        }
    
        return $errors; 
    }

    // Update profile
    function updateProfile($form_data, $image_data) {
        $dataUpdate = [
            'first_name' => $form_data['first_name'],
            'last_name' => $form_data['last_name'],
            'username' => $form_data['username'],
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if (!empty($form_data['password'])) {
            $dataUpdate['password'] = password_hash($form_data['password'], PASSWORD_DEFAULT);
        }

        $profile_pic = "";
        if ($image_data['name']) {
            $image_name = time().basename($image_data['name']);
            $image_dir = "assets/img/profile/$image_name";
            move_uploaded_file($image_data['tmp_name'], $image_dir);

            $profile_pic = $image_name;
            $dataUpdate['profile_pic'] = $profile_pic;
        }

        $userId = $_SESSION['userdata']['id'];
        $condition = "id = $userId";
        $updateQuery = update('users', $dataUpdate, $condition);
        if ($updateQuery) {
            return true;
        }

        return false;
    }

    // Validate add post
    function validatePostImage($image_data) {
        $errors = [];

        if ($image_data['name']) {
            $image = basename($image_data['name']);
            $type = strtolower(pathinfo($image, PATHINFO_EXTENSION));
            $size = $image_data['size']/1000;

            if ($type != 'jpg' && $type != 'jpeg' && $type != 'png') {
                $errors['profile_pic']['required'] = 'Only jpg, jpeg, png are allowed.';
            }
        }

        if ($size > 1000) {
            $errors['profile_pic']['sizemax'] = 'Upload image less then 1 mb';
        }

        return $errors;
    }

    // Create new post
    function createPost($text, $image_data) {
        $userId = $_SESSION['userdata']['id'];
        $dataInsert = [
            'user_Id' => $userId,
            'post_text' => $text,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $post_img = "";
        if ($image_data['name']) {
            $image_name = time().basename($image_data['name']);
            $image_dir = "assets/img/post/$image_name";
            move_uploaded_file($image_data['tmp_name'], $image_dir);

            $post_img = $image_name;
            $dataInsert['post_img'] = $post_img;
        }

        $insertQuery = insert('posts', $dataInsert);
        if ($insertQuery) {
            return true;
        }
        
        return false;
    }

    // Get post
    function getPost() {
        $query = "SELECT posts.id, posts.user_Id, posts.post_img, posts.post_text, posts.created_at, 
        users.first_name, users.last_name, users.username, users.profile_pic FROM posts JOIN users ON
        users.id=posts.user_Id ORDER BY id DESC";
        $getData = getRaw($query);       

        if ($getData) {
            return $getData;
        }

        return false;
    }

    // Get post by id
    function getPostById($userId) {
        $query = "SELECT * FROM posts WHERE user_Id = $userId ORDER BY id DESC";
        $getData = getRaw($query);       

        if ($getData) {
            return $getData;
        }

        return false;
    }

    // Delete post
    function deletePost($user_Id, $post_Id) {
        $condition = "post_Id = $post_Id";
        $deleteLiked = delete('likes', $condition);
        $deleteComment = delete('comments', $condition);

        if ($deleteLiked && $deleteComment) {
            $conditionPost = "user_Id = $user_Id && id = $post_Id";
            $deletePost = delete('posts', $conditionPost);

            if ($deletePost) {
                return true;
            }
        }

        return false;
    }

    //Get user
    function getUser($userId) {
        $query = "SELECT * FROM users WHERE id = $userId";
        $getData = getOneRaw($query);

        if ($getData) {
            return $getData;
        }

        return false;
    }

    function getUserByUsername($username) {
        $query = "SELECT * FROM users WHERE username = '$username'";
        $getData = getOneRaw($query);

        if ($getData) {
            return $getData;
        }

        return false;
    }

    // Check the user is followed by current user or not
    function checkFollowStatus($userId) {
        $current_user = $_SESSION['userdata']['id'];
        $query = "SELECT count(*) as row FROM followlist WHERE follower_id = $current_user && user_Id = $userId";
        $getData = getOneRaw($query);

        if ($getData) {
            return $getData['row'];
        }

        return false;
    }

    // Get user for suggestion accounts
    function getUserFollowSuggestion() {
        $current_user = $_SESSION['userdata']['id'];
        $query = "SELECT * FROM users WHERE id!=$current_user LIMIT 8";
        $getData = getRaw($query);       

        if ($getData) {
            return $getData;
        }

        return false;
    } 

    // Filtering the suggestion list
    function filterFollowSuggestion() {
        $list_user = getUserFollowSuggestion();
        $filter_list = [];
        foreach ($list_user as $user) {
            if (!checkFollowStatus($user['id']) && count($filter_list) < 5) {
                $filter_list[] = $user;
            }
        }

        return $filter_list;
    }

    // Follow user 
    function followUser($userId) {
        $current_user = $_SESSION['userdata']['id'];
        $dataInsert = [
            'follower_id' => $current_user,
            'user_Id' => $userId
        ];

        $insertQuery = insert('followlist', $dataInsert);

        if ($insertQuery) {
            return true;
        }

        return false;
    } 

    // Unfollow user 
    function unfollowUser($userId) {
        $current_user = $_SESSION['userdata']['id'];

        $condition = "follower_id = $current_user && user_Id = $userId";
        $deleteUser = delete('followlist', $condition);

        if ($deleteUser) {
            return true;
        }

        return false;
    } 

    // Get follower number
    function getFollower($userId) {
        $query = "SELECT * FROM followlist WHERE user_Id = $userId";
        $getData = getRaw($query);

        if ($getData) {
            return $getData;
        }

        return false;
    }

    // Get following number
    function getFollowing($userId) {
        $query = "SELECT * FROM followlist WHERE follower_id = $userId";
        $getData = getRaw($query);

        if ($getData) {
            return $getData;
        }

        return false;
    }

    // Filtering the post on dashboard
    function filterHomePost() {
        $list_post = getPost();
        $filter_list = [];
        foreach ($list_post as $post) {
            $is_followed = checkFollowStatus($post['user_Id']);
            $is_own_user = ($post['user_Id'] == $_SESSION['userdata']['id']);
            
            if ($is_followed || $is_own_user) {
                $filter_list[] = $post;
            }
        }

        return $filter_list;
    }

    // Like post
    function likePost($postId) {
        $current_user = $_SESSION['userdata']['id'];
        $dataInsert = [
            'user_Id' => $current_user,
            'post_Id' => $postId
        ];

        $insertQuery = insert('likes', $dataInsert);
        if ($insertQuery) {
            return true;
        }

        return false;
    }

    // Unlike post
    function unlikePost($postId) {
        $current_user = $_SESSION['userdata']['id'];

        $condition = "user_Id = $current_user && post_Id = $postId";
        $deleteLike = delete('likes', $condition);
        
        if ($deleteLike) {
            return true;
        }

        return false;
    }

    // Check the post is like by current user or not
    function checkLikeStatus($postId) {
        $current_user = $_SESSION['userdata']['id'];
        $query = "SELECT count(*) as row FROM likes WHERE user_Id = $current_user && post_Id = $postId";
        $getData = getOneRaw($query);

        if ($getData) {
            return $getData['row'];
        }

        return false;
    }

    // Count like 
    function countLikePost($postId) {
        $query = "SELECT * FROM likes WHERE post_Id = $postId";
        $getData = getRaw($query);

        if ($getData) {
            return $getData;
        }

        return false;
    }

    // Create comment
    function addComment($post_Id, $comment) {
        $current_user = $_SESSION['userdata']['id'];
        $dataInsert = [
            'user_Id' => $current_user,
            'post_Id' => $post_Id,
            'comment' => $comment,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $insertQuery = insert('comments', $dataInsert);
        if ($insertQuery) {
            return true;
        }

        return false;
    }

    // Get comments
    function getComments($post_Id) {
        $query = "SELECT * FROM comments WHERE post_Id = $post_Id";
        $getData = getRaw($query);

        if ($getData) {
            return $getData;
        }

        return false;
    }

    // Get notifications
    function getNotifications() {
        $current_user_id = $_SESSION['userdata']['id'];
        $query = "SELECT * FROM notifications WHERE to_user_Id = $current_user_id ORDER BY id DESC";

        $getData = getRaw($query);
        if ($getData) {
            return $getData;
        }

        return false;
    }

    // Add notification
    function addNotification($post_Id, $description, $user_Id) {
        $current_user_id = $_SESSION['userdata']['id'];

        $dataInsert = [
            'from_user_Id' => $current_user_id,
            'to_user_Id' => $user_Id,
            'description' => $description,
            'read_status' => 0
        ];

        if (!empty($post_Id) && $post_Id != null) {
            $dataInsert['post_Id'] = $post_Id;
        }

        $insertQuery = insert('notifications', $dataInsert);

        if ($insertQuery) {
            return true;
        }

        return false;
    }
    
    // Read notification
    function readNotification($noti_Id) {
        $dataUpdate = [
            'read_status' => 1
        ];
        $condition = "id = $noti_Id";
        $updateQuery = update('notifications', $dataUpdate, $condition);

        if ($updateQuery) {
            return true;
        }

        return false;
    }

    // Count notification not read
    function newNotificationCount() {
        $current_user_id = $_SESSION['userdata']['id'];
        $query = "SELECT count(*) as row FROM notifications WHERE to_user_Id = $current_user_id && read_status = 0";

        $getData = getOneRaw($query);
        if ($getData) {
            return $getData['row'];
        }

        return false;
    }

    function searchUser($keyword) {
        $query = "SELECT * FROM users WHERE username LIKE '%$keyword%'";

        $searchData = getRaw($query);
        if ($searchData) {
            return $searchData;
        }
        
        return false;
    }

    function blockedUser($blocked_user_Id) {
        $current_user_id = $_SESSION['userdata']['id'];
        $dataInsert = [
            'user_Id' => $current_user_id,
            'blocked_user_Id' => $blocked_user_Id 
        ];

        $insertQuery = insert('blockuser', $dataInsert);

        $condition = "follower_id = $current_user_id && user_Id = $blocked_user_Id";
        $deleteFollower = delete('followlist', $condition);

        if ($insertQuery && $deleteFollower) {
            return true;
        }
        
        return false;
    }

    function unblockedUser($blocked_user_Id) {
        $current_user_id = $_SESSION['userdata']['id'];

        $condition = "user_Id = $current_user_id && blocked_user_Id = $blocked_user_Id";
        $deleteBlocked = delete('blockuser', $condition);

        if ($deleteBlocked) {
            return true;
        }

        return false;
    }

    function getBlockedUser() {
        $current_user_id = $_SESSION['userdata']['id'];
        $query = "SELECT * FROM blockuser WHERE user_Id = $current_user_id";

        $getData = getOneRaw($query);
        if ($getData) {
            return $getData;
        }

        return false;
    }

    // Format time
    function getTime($date) {
        return date('H:i - (F jS, Y)', strtotime($date));
    }

    function getTimeOnPost($date) {
        $timestamp = strtotime($date);
        $current_time = time();
        $difference = $current_time - $timestamp;
    
        if ($difference < 60) {
            return $difference . "s ago";
        } elseif ($difference < 3600) {
            $minutes = floor($difference / 60);
            return $minutes . "m ago";
        } elseif ($difference < 86400) {
            $hours = floor($difference / 3600);
            return $hours . "h ago";
        } elseif ($difference < 604800) {
            $days = floor($difference / 86400);
            return $days . "d ago";
        } else {
            $weeks = floor($difference / 604800);
            return $weeks . "w ago";
        }
    }
    

    // Get id with active chat user 
    function getActiveChatUserIds() {
        $current_user_id = $_SESSION['userdata']['id'];
        $query = "SELECT from_user_Id, to_user_Id FROM messages WHERE to_user_Id = $current_user_id || from_user_Id = $current_user_id";

        $getData = getRaw($query);
        if ($getData) {
            $ids = [];
            foreach ($getData as $chat) {
                if ($chat['from_user_Id'] != $current_user_id && !in_array($chat['from_user_Id'], $ids)) {
                    $ids[] = $chat['from_user_Id'];
                }

                if ($chat['to_user_Id'] != $current_user_id && !in_array($chat['to_user_Id'], $ids)) {
                    $ids[] = $chat['to_user_Id'];
                }
            }
            return $ids;
        }

        return false;
    }

    // Get messages
    function getMessages($user_Id) {
        $current_user_id = $_SESSION['userdata']['id'];
        $query = "SELECT * FROM messages WHERE (to_user_Id = $current_user_id && from_user_Id = $user_Id) 
        || (from_user_Id = $current_user_id && to_user_Id = $user_Id) ORDER BY id DESC";
        
        $getData = getRaw($query);
        if ($getData) {
            return $getData;
        }

        return false;
    }

    // Get all messages
    function getAllMessages() {
        $active_chat_ids = getActiveChatUserIds();
        $conversation = [];

        foreach ($active_chat_ids as $index => $id) {
            $conversation[$index]['user_Id'] = $id;
            $conversation[$index]['message'] = getMessages($id); 
        }

        return $conversation;
    }

    // Send message
    function sendMessage($user_Id, $message) {
        $current_user_id = $_SESSION['userdata']['id'];
        $dataInsert = [
            'from_user_Id' => $current_user_id,
            'to_user_Id' => $user_Id,
            'message' => $message
        ];

        $insertQuery = insert('messages', $dataInsert);
        if ($insertQuery) {
            readMessageStatus($user_Id);
            return true;
        }

        return false;
    }

    // Update read message status
    function readMessageStatus($user_Id) {
        $current_user_id = $_SESSION['userdata']['id'];
        $dataUpdate = [
            'read_status' => 1
        ];
        $condition = "to_user_Id = $current_user_id && from_user_Id = $user_Id";

        $updateQuery = update('messages', $dataUpdate, $condition);
        if ($updateQuery) {
            return true;
        }

        return false;
    }

    function newMessageCount() {
        $current_user_id = $_SESSION['userdata']['id'];
        $query = "SELECT count(*) as row FROM messages WHERE to_user_Id = $current_user_id 
        && read_status=0";
        $getData = getOneRaw($query);

        if ($getData) {
            return $getData['row'];
        }

        return false;
    }
