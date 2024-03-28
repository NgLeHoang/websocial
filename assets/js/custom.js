
// Preview the post image
var $post_img = document.querySelector("#select_post_img");

$post_img.addEventListener("change", previewImage);

function previewImage() {
    var fileObject = this.files[0];
    var fileReader = new FileReader();

    fileReader.readAsDataURL(fileObject);

    fileReader.onload = function() {
        var image_src = fileReader.result;
        var image = document.querySelector("#post_img");

        image.setAttribute('src', image_src);
        image.setAttribute('style', 'display:');
    }
}

// Follower user

$(document).ready(function() {
    $(".follow-btn").click(function() {
        var user_id = $(this).data('userId');
        var button = this;

        $.ajax({
            url: '?module=process&action=ajax&follow',
            method: 'post',
            dataType: 'json',
            data: { user_Id: user_id},
            success: function(response) {
                if (response.status) {
                    $(button).attr('disabled', true);
                    $(button).data('userId', 0);
                    $(button).html('Followed <i class="fa-solid fa-circle-check"></i>');
                } else {
                    $(button).attr('disabled', false);

                    alert('Something is wrong, try again after some minutes...');
                }
            } 
        });
    });

    $(".unfollow-btn").click(function() {
        var user_id = $(this).data('userId');
        var button = this;

        $.ajax({
            url: '?module=process&action=ajax&unfollow',
            method: 'post',
            dataType: 'json',
            data: { user_Id: user_id},
            success: function(response) {
                if (response.status) {
                    $(button).attr('disabled', true);
                    $(button).data('userId', 0);
                    $(button).html('Unfollowed <i class="fa-solid fa-circle-check"></i>');
                } else {
                    $(button).attr('disabled', false);

                    alert('Something is wrong, try again after some minutes...');
                }
            } 
        });
    });

    $(".like-btn").click(function() {
        var post_id = $(this).data('postId');
        var button = this;
        $(button).attr('disabled', true);

        $.ajax({
            url: '?module=process&action=ajax&like',
            method: 'post',
            dataType: 'json',
            data: {post_Id: post_id},
            success: function(response) {
                if (response.status) {
                    $(button).attr('disabled', false);
                    $(button).hide();
                    $(button).siblings('.unlike-btn').show();
                    location.reload();

                } else {
                    $(button).attr('disabled', false);

                    alert('Something is wrong, try again after some minutes...');
                }
            } 
        });
    });

    $(".unlike-btn").click(function() {
        var post_id = $(this).data('postId');
        var button = this;
        $(button).attr('disabled', true);

        $.ajax({
            url: '?module=process&action=ajax&unlike',
            method: 'post',
            dataType: 'json',
            data: {post_Id: post_id},
            success: function(response) {
                if (response.status) {
                    $(button).attr('disabled', false);
                    $(button).hide();
                    $(button).siblings('.like-btn').show();
                    location.reload();

                } else {
                    $(button).attr('disabled', false);

                    alert('Something is wrong, try again after some minutes...');
                }
            } 
        });
    });

    $(".add-comment").click(function() {
        var post_id = $(this).data('postId');
        var button = this;
        var comment_section = $(this).data('cs');
        var comment_in = $(button).siblings('.comment-input').val();
        if (comment_in == '') {
            return 0;
        }
        $(button).attr('disabled', true);
        $(button).siblings('.comment-input').attr('disabled', true);

        $.ajax({
            url: '?module=process&action=ajax&addcomment',
            method: 'post',
            dataType: 'json',
            data: {post_Id: post_id, comment: comment_in},
            success: function(response) {
                if (response.status) {
                    $(button).attr('disabled', false);
                    $(button).siblings('.comment-input').attr('disabled', false);
                    $(button).siblings('.comment-input').val('');
                    $("#" + comment_section).append(response.comment);
                    $('.no-comment').hide();
                    
                } else {
                    $(button).attr('disabled', false);

                    alert('Something is wrong, try again after some minutes...');
                }
            } 
        });
    });
});