
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
                    $('#notification-body').html(response.notification);
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
                    
                    var likeCountSpan = $('#likecount' + post_id);
                    var newLikeCount = parseInt(likeCountSpan.text()) + 1;
                    likeCountSpan.text(newLikeCount + (newLikeCount === 1 ? ' like' : ' likes'));
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

                    var likeCountSpan = $('#likecount' + post_id);
                    var newLikeCount = parseInt(likeCountSpan.text()) - 1;
                    likeCountSpan.text(newLikeCount + (newLikeCount === 1 || newLikeCount === 0 ? ' like' : ' likes'));

                } else {
                    $(button).attr('disabled', false);

                    alert('Something is wrong, try again after some minutes...');
                }
            } 
        });
    });

    $(".add-comment").click(function() {
        var post_id = $(this).data('postId');
        var user_id = $(this).data('userId');
        
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
            data: {post_Id: post_id, comment: comment_in, user_Id: user_id},
            success: function(response) {
                if (response.status) {
                    type = "comment";
                    addNotification(post_id, user_id, type);

                    $(button).attr('disabled', false);
                    $(button).siblings('.comment-input').attr('disabled', false);
                    $(button).siblings('.comment-input').val('');
                    $("#" + comment_section).append(response.comment);
                    
                    var commentCountSpan = $('#commentcount' + post_id);
                    var newCommentCount = parseInt(commentCountSpan.text()) + 1;
                    commentCountSpan.text(newCommentCount + (newCommentCount === 1 ? ' comment' : ' comments'));

                    $('.no-comment').hide();
                    
                } else {
                    $(button).attr('disabled', false);

                    alert('Something is wrong, try again after some minutes...');
                }
            } 
        });
    });

    function addNotification(post_Id, user_Id, type) {
        $.ajax({
            url: '?module=process&action=ajax&addnotification',
            method: 'post',
            dataType: 'json',
            data: {post_Id: post_Id, user_Id: user_Id, type: type},
            success: function(response) {
                if (response.status) {
                    console.log(response);

                } else {
                    alert('Something is wrong, try again after some minutes...');
                }
            }
        });
    }

    function SyncNotification() {
        $.ajax({
            url: '?module=process&action=ajax&getnotification',
            method: 'post',
            dataType: 'json',
            success: function (response) {
                console.log(response);
                $("#notificationlist").html(response.notificationlist);
            }
        });
    }

    setInterval(() => {
        SyncNotification();
    }, 3000)
});