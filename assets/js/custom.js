
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

var noti_id_read = 0;
var chatting_user_id = 0;

function readNotification(noti_id) {
    noti_id_read = noti_id;
}

function popchat(user_Id) {
    $("#userchat").html(`<div class="spinner-border" role="status"></div>`);
    $("#chatter_username").html("");
    $("#chatter_name").text("Loading...");
    $("#chatter_pic").attr('src', 'assets/img/profile/default_image.jpg');

    chatting_user_id = user_Id;

    $("#sendmessage").attr('data-user-id', user_Id);
}

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
                    type = "follow";
                    post_id = null;
                    addNotification(post_id , user_id, type);

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
        var user_id = $(this).data('userId');
        var button = this;
        $(button).attr('disabled', true);

        $.ajax({
            url: '?module=process&action=ajax&like',
            method: 'post',
            dataType: 'json',
            data: {post_Id: post_id, user_Id: user_id},
            success: function(response) {
                if (response.status) {
                    type = "like";
                    addNotification(post_id, user_id, type);

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
        var comment_section_post = $(this).data('csp');
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
                    if (response.commentpost) {
                        $("#" + comment_section_post).append(response.commentpost);
                    }
                    
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

    function addNotification(post_Id = '', user_Id, type) {
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
                $("#notificationlist").html(response.notificationlist);

                if (response.newnotificationcount == 0) {
                    $("#noticounter").hide();
                } else {
                    $("#noticounter").show();
                    $("#noticounter").html("<small>"+ response.newnotificationcount +"</small>");
                }

                $("#notificationlist").find(".notification_item").click(function() {
                    $.ajax({
                        url: '?module=process&action=ajax&readnotification&noti_id='+noti_id_read,
                        method: 'get',
                        dataType: 'json',
                        success: function(response) {
                            if (response.status) {
                            } else {
                                alert('Something is wrong, try again after some minutes...');
                            }
                        }
                    });
                });
            }
        });
    }

    // setInterval(() => {
    //     SyncNotification();
    // }, 3000)

    var searchResults = '';
    $('#searchInput').on('input', function () {
        var keyword = $(this).val();
        if (keyword.length > 0) {
            $.ajax({
                url: '?module=process&action=ajax&search',
                type: 'post',
                dataType: 'json',
                data: {keyword: keyword},
                success: function(response){
                    searchResults = response.searchlist;
                    $('#searchResult').html(response.searchlist).show();
                }
            });
        } else {
            $('#searchResult').hide();
        }
    });
        
    $('#searchInput').focus(function(){
        $('#searchResult').show();
        $('#searchResult').html('<p class="text-muted p-2 m-1">Enter the name to search...</p>');
        if (searchResults) {
            $('#searchResult').html(searchResults).show();
        }
    });

    $('#searchInput').blur(function(){
        setTimeout(function() {
            if (!mouseIsOverResult) {
                $('#searchResult').hide();
            }
        }, 100);
    });

    var mouseIsOverResult = false;

    $('#searchResult').on('mouseenter', function() {
        mouseIsOverResult = true;
    }).on('mouseleave', function() {
        mouseIsOverResult = false;
    });

    $('#searchResult').on('mousedown', 'a', function(event){
        event.preventDefault();
        var href = $(this).attr('href');
        window.location.href = href;
    });

    $('.block-btn').click(function () {
        var blocked_user_Id = $(this).data('blocked-user-id');

        $.ajax({
            url: '?module=process&action=ajax&block',
            method: 'post',
            dataType: 'json',
            data: {blocked_user_Id: blocked_user_Id},
            success: function(response) {
                if (response.status) {
                    location.reload();
                } else {
                    alert('Something is wrong, try again after some minutes...');
                }
            }
        });
    });

    $('.unblock-btn').click(function () {
        var blocked_user_Id = $(this).data('userId');

        $.ajax({
            url: '?module=process&action=ajax&unblock',
            method: 'post',
            dataType: 'json',
            data: {blocked_user_Id: blocked_user_Id},
            success: function(response) {
                if (response.status) {
                    console.log(response);
                    location.reload();
                } else {
                    alert('Something is wrong, try again after some minutes...');
                }
            }
        });
    });

    $("#sendmessage").click(function() {
        var user_Id = chatting_user_id;
        var message = $("#messageinput").val();
        if (!message) return;

        $("#sendmessage").attr('disabled', true);
        $("#messageinput").attr('disabled', true);

        $.ajax({
            url: '?module=process&action=ajax&sendmessage',
            method: 'post',
            dataType: 'json',
            data: {user_Id: user_Id, message: message},
            success: function(response) {
                if (response.status) {
                    $("#sendmessage").attr('disabled', false);
                    $("#messageinput").attr('disabled', false);
                    $("#messageinput").val('');
                } else {
                    alert('Something is wrong, try again after some minutes...');
                }
            }
        });
    })

    function syncMessage() {

        $.ajax({
            url: '?module=process&action=ajax&getmessage',
            method: 'post',
            dataType: 'json',
            data: {chatter_id: chatting_user_id},
            success: function (response) {
                console.log(chatting_user_id);
                $("#chatlist").html(response.chatlist);

                if (response.newmessagecount == 0) {
                    $("#msgcounter").hide();
                } else {
                    $("#msgcounter").show();
                    $("#msgcounter").html("<small>"+ response.newmessagecount +"</small>");
                }

                if (chatting_user_id != 0) {
                    $("#userchat").html(response.chat.message);
                    $("#chatter_username").html(response.chat.userdata.username);
                    $("#chatter_name").html(response.chat.userdata.first_name + " " + response.chat.userdata.last_name);
                    $("#chatter_pic").attr('src', 'assets/img/profile/' + response.chat.userdata.profile_pic);
                    $("#chatter_pic").attr('alt', response.chat.userdata.profile_pic);
                }

                $("#chatlist").find(".chatlist_item").click(function() {
                    $.ajax({
                        url: '?module=process&action=ajax&readmessage&message_id='+chatting_user_id,
                        method: 'get',
                        dataType: 'json',
                        success: function(response) {
                            if (response.status) {
                            } else {
                                alert('Something is wrong, try again after some minutes...');
                            }
                        }
                    });
                });
            }
        });
    }

    // setInterval(() => {
    //     syncMessage();
    // }, 3000)
    
    $(".delete-post").click(function () {
        user_Id = $(this).data('userId');
        post_Id = $(this).data('postId');

        $(".delete-post").attr('disabled', true);

        $.ajax({
            url: '?module=process&action=ajax&deletepost',
            method: 'post',
            dataType: 'json',
            data: {user_Id: user_Id, post_Id: post_Id},
            success: function(response) {
                console.log(response);
                if (response.status) {
                    $(".delete-post").attr('disabled', false);
                    location.reload();
                }
            }
        });
    });
});
