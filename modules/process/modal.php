<?php
    if (isset($_GET['post'])) {
        $text = filterData()['post_text'];
        $image = $_FILES['post_img'];

        $errors = validatePostImage($image);
        if (empty($errors)) {
            $createPost = createPost($text, $image);
            if ($createPost) {
                redirect('?module=home&acion=dashboard');
            } else {
                setFlashData('msg', 'System is error, please try again later');
                setFlashData('msg_type', 'danger');
            }
        } else {
            setFlashData('msg', 'Post status fail, please check again!');
            setFlashData('msg_type', 'danger');
            setFlashData('errors', $errors);
        }
        redirect('?module=home&acion=dashboard');
    }
?>