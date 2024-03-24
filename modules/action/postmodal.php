<?php
    if (isPost()) {
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
    }
?>

<div class="modal fade" id="postmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img src="" style="display:none" id="post_img" class="w-100 rounded border">
                <form method="post" enctype="multipart/form-data">
                    <div class="my-3">
                        <input class="form-control" name="post_img" type="file" id="select_post_img">
                    </div>
                    <div class="mb-3">
                        <label for="exampleFormControlTextarea1" class="form-label">Say Something</label>
                        <textarea name="post_text" class="form-control" id="exampleFormControlTextarea1" rows="1"></textarea>
                    </div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Post</button>
                </form>
            </div> 
        </div>
    </div>
</div>