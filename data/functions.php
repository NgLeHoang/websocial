<?php
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
        $activeToken = sha1(uniqid().time());
        $dataInsert = [
            'first_name' => $form_data['first_name'],
            'last_name' => $form_data['last_name'],
            'gender' => $form_data['gender'],
            'email' =>  $form_data['email'],
            'username' => $form_data['username'],
            'password' => password_hash($form_data['password'], PASSWORD_DEFAULT),
            'activeToken' => $activeToken,
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

            $userQuery = getOneRaw("SELECT password, id FROM users WHERE email = '$username_email' || username = '$username_email'");

            if (!empty($userQuery)) {
                $passwordHash = $userQuery['password'];
                $userId = $userQuery['id'];
                if (password_verify($password, $passwordHash)) {

                    // Check account is login
                    $userLogin = getRows("SELECT * FROM logintoken WHERE user_Id = $userId");
                    if ($userLogin > 0) {
                        setFlashData('msg', 'Account is login another page.');
                        setFlashData('msg_type', 'danger');
                        redirect('?module=auth&action=signin');
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
   
    